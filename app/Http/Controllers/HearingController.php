<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use App\Models\CourtCase;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HearingController extends Controller
{
    /**
     * Display hearing schedules and calendars.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Hearing::with(['courtCase', 'judge']);

        // Filter based on roles
        if ($user->isJudge()) {
            $query->where('judge_id', $user->id);
        } elseif ($user->isLawyer()) {
            $query->whereHas('courtCase', function($q) use ($user) {
                $q->where('lawyer_id', $user->id);
            });
        }

        // Search options
        if ($request->filled('courtroom')) {
            $query->where('courtroom', $request->input('courtroom'));
        }

        $hearings = $query->orderBy('hearing_date', 'asc')->paginate(15);
        
        // Fetch all hearings this month for a basic Blade calendar display
        $calendarHearings = Hearing::with('courtCase')
            ->whereBetween('hearing_date', [
                Carbon::now()->startOfMonth()->subDays(7),
                Carbon::now()->endOfMonth()->addDays(7)
            ])
            ->get();

        return view('hearings.index', compact('hearings', 'calendarHearings'));
    }

    /**
     * Store a newly scheduled hearing.
     */
    public function store(Request $request)
    {
        $request->validate([
            'court_case_id' => 'required|exists:court_cases,id',
            'hearing_date' => 'required|date|after:now',
            'courtroom' => 'required|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $case = CourtCase::findOrFail($request->court_case_id);
        
        // Ensure there is a judge assigned to the case before scheduling
        if (!$case->judge_id) {
            return redirect()->back()->withErrors([
                'court_case_id' => 'You must assign a presiding Judge to the case before scheduling a hearing.'
            ]);
        }

        $hearingDateTime = Carbon::parse($request->hearing_date);

        // Enterprise Check: Courtroom double-booking check within +/- 30 minutes
        $conflict = Hearing::where('courtroom', $request->courtroom)
            ->whereIn('status', ['scheduled'])
            ->whereBetween('hearing_date', [
                $hearingDateTime->copy()->subMinutes(29),
                $hearingDateTime->copy()->addMinutes(29)
            ])
            ->first();

        if ($conflict) {
            $conflictCase = $conflict->courtCase->case_number;
            $conflictTime = $conflict->hearing_date->format('g:i A');
            return redirect()->back()->withInput()->withErrors([
                'courtroom' => "Courtroom collision! {$request->courtroom} is already booked for case {$conflictCase} at {$conflictTime}."
            ]);
        }

        DB::transaction(function () use ($request, $case, $hearingDateTime) {
            $hearing = Hearing::create([
                'court_case_id' => $case->id,
                'hearing_date' => $hearingDateTime,
                'courtroom' => $request->courtroom,
                'judge_id' => $case->judge_id,
                'status' => 'scheduled',
                'remarks' => $request->remarks,
            ]);

            // Advance case status if it is currently 'filed' or 'under_review'
            if (in_array($case->status, ['filed', 'under_review'])) {
                $case->update(['status' => 'hearing_scheduled']);
            }

            // Log activity
            $formattedDate = $hearingDateTime->format('F d, Y \a\t g:i A');
            ActivityLog::create([
                'user_id' => auth()->id(),
                'court_case_id' => $case->id,
                'action' => 'Hearing Scheduled',
                'details' => "Scheduled hearing at {$request->courtroom} for {$formattedDate}.",
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->route('cases.show', $case)->with('success', 'Hearing scheduled successfully.');
    }

    /**
     * Reschedule an existing hearing.
     */
    public function reschedule(Request $request, Hearing $hearing)
    {
        $request->validate([
            'hearing_date' => 'required|date|after:now',
            'courtroom' => 'required|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $newHearingDateTime = Carbon::parse($request->hearing_date);

        // Conflict check excluding the current hearing
        $conflict = Hearing::where('courtroom', $request->courtroom)
            ->where('id', '!=', $hearing->id)
            ->whereIn('status', ['scheduled'])
            ->whereBetween('hearing_date', [
                $newHearingDateTime->copy()->subMinutes(29),
                $newHearingDateTime->copy()->addMinutes(29)
            ])
            ->first();

        if ($conflict) {
            $conflictCase = $conflict->courtCase->case_number;
            $conflictTime = $conflict->hearing_date->format('g:i A');
            return redirect()->back()->withErrors([
                'reschedule_courtroom' => "Courtroom collision! {$request->courtroom} is already booked for case {$conflictCase} at {$conflictTime}."
            ]);
        }

        $oldDate = $hearing->hearing_date->format('M d, Y h:i A');
        $newDate = $newHearingDateTime->format('M d, Y h:i A');

        DB::transaction(function () use ($hearing, $newHearingDateTime, $request, $oldDate, $newDate) {
            $hearing->update([
                'hearing_date' => $newHearingDateTime,
                'courtroom' => $request->courtroom,
                'status' => 'rescheduled',
                'remarks' => "Rescheduled from {$oldDate}. Reason/Remarks: " . $request->remarks,
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'court_case_id' => $hearing->court_case_id,
                'action' => 'Hearing Rescheduled',
                'details' => "Rescheduled hearing from {$oldDate} to {$newDate} at {$request->courtroom}.",
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->back()->with('success', 'Hearing rescheduled successfully.');
    }

    /**
     * Update hearing remarks and status (Judge, Clerk, or Admin).
     */
    public function updateRemarks(Request $request, Hearing $hearing)
    {
        $request->validate([
            'status' => 'required|in:scheduled,held,cancelled,rescheduled',
            'remarks' => 'nullable|string',
        ]);

        $hearing->update($request->only(['status', 'remarks']));

        $statusLabels = [
            'scheduled' => 'Scheduled',
            'held' => 'Held',
            'cancelled' => 'Cancelled',
            'rescheduled' => 'Rescheduled',
        ];

        ActivityLog::create([
            'user_id' => auth()->id(),
            'court_case_id' => $hearing->court_case_id,
            'action' => 'Hearing Updated',
            'details' => "Hearing status marked as '{$statusLabels[$request->status]}'. Remarks: {$request->remarks}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Hearing parameters updated successfully.');
    }
}
