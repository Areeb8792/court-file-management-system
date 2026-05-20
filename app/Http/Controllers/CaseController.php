<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CaseController extends Controller
{
    /**
     * Display a listing of cases with advanced search & filters.
     */
    public function index(Request $request)
    {
        $query = CourtCase::with(['judge', 'lawyer', 'clerk']);

        // Global search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('petitioner', 'like', "%{$search}%")
                  ->orWhere('respondent', 'like', "%{$search}%")
                  ->orWhere('fir_number', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('judge', function ($jq) use ($search) {
                      $jq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('lawyer', function ($lq) use ($search) {
                      $lq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Custom Quick Filter options
        if ($request->input('filter') === 'open') {
            $query->where('status', '!=', 'closed');
        } elseif ($request->input('filter') === 'closed') {
            $query->where('status', 'closed');
        } elseif ($request->input('filter') === 'urgent') {
            $query->where('priority', 'urgent')->where('status', '!=', 'closed');
        }

        // Role-based scoping (Judge can only see their cases, Lawyer can only see their cases)
        $user = auth()->user();
        if ($user->isJudge()) {
            $query->where('judge_id', $user->id);
        } elseif ($user->isLawyer()) {
            $query->where('lawyer_id', $user->id);
        }

        $cases = $query->latest()->paginate(10)->withQueryString();

        return view('cases.index', compact('cases'));
    }

    /**
     * Show the details of a specific court case.
     */
    public function show(CourtCase $case)
    {
        // Security check
        $user = auth()->user();
        if ($user->isJudge() && $case->judge_id !== $user->id) {
            abort(403, 'Access denied. You are not the assigned judge for this case.');
        }
        if ($user->isLawyer() && $case->lawyer_id !== $user->id) {
            abort(403, 'Access denied. You are not a registered attorney on this case.');
        }

        $case->load([
            'judge',
            'lawyer',
            'clerk',
            'hearings' => function($q) { $q->orderBy('hearing_date', 'desc'); },
            'documents' => function($q) { $q->whereNull('parent_id')->orderBy('created_at', 'desc'); },
            'activityLogs' => function($q) { $q->with('user')->orderBy('created_at', 'desc'); }
        ]);

        $judges = User::where('role', 'judge')->orderBy('name')->get();
        $lawyers = User::where('role', 'lawyer')->orderBy('name')->get();

        return view('cases.show', compact('case', 'judges', 'lawyers'));
    }

    /**
     * Show form to create a new case (Accessible to all authenticated roles).
     */
    public function create()
    {
        $judges = User::where('role', 'judge')->orderBy('name')->get();
        $lawyers = User::where('role', 'lawyer')->orderBy('name')->get();

        return view('cases.create', compact('judges', 'lawyers'));
    }

    /**
     * Store a newly created case (Accessible to all authenticated roles).
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'fir_number' => 'nullable|string|max:100',
            'petitioner' => 'required|string|max:255',
            'respondent' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'judge_id' => 'nullable|exists:users,id',
            'lawyer_id' => 'nullable|exists:users,id',
        ]);

        // Generate unique Judicial Case Number: CRT-YYYY-XXXX
        $year = Carbon::now()->year;
        $count = CourtCase::whereYear('created_at', $year)->count();
        $case_number = 'CRT-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($request, $case_number, $user) {
            // Apply role-based defaults
            $judgeId = $request->judge_id;
            $lawyerId = $request->lawyer_id;
            $petitioner = $request->petitioner;

            if ($user->isLawyer()) {
                $lawyerId = $user->id; // Auto-assign advocate
            } elseif ($user->isPublic()) {
                $judgeId = null;   // No auto-bench
                $lawyerId = null;  // No auto-lawyer
                $petitioner = $user->name; // Citizen is the petitioner
            }

            $case = CourtCase::create([
                'case_number' => $case_number,
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'fir_number' => $request->fir_number,
                'petitioner' => $petitioner,
                'respondent' => $request->respondent,
                'priority' => $request->priority,
                'judge_id' => $judgeId,
                'lawyer_id' => $lawyerId,
                'clerk_id' => $user->role === 'clerk' ? $user->id : null,
                'status' => 'filed',
                'filed_at' => Carbon::now(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'court_case_id' => $case->id,
                'action' => 'Case Filed',
                'details' => "Case registered successfully by {$user->name} ({$user->role}) with code {$case_number}.",
                'ip_address' => $request->ip(),
            ]);

            if ($judgeId) {
                $judgeName = User::find($judgeId)->name;
                ActivityLog::create([
                    'user_id' => $user->id,
                    'court_case_id' => $case->id,
                    'action' => 'Judge Assigned',
                    'details' => "Hon. {$judgeName} assigned as the presiding officer.",
                    'ip_address' => $request->ip(),
                ]);
            }

            if ($lawyerId) {
                $lawyerName = User::find($lawyerId)->name;
                ActivityLog::create([
                    'user_id' => $user->id,
                    'court_case_id' => $case->id,
                    'action' => 'Lawyer Assigned',
                    'details' => "Adv. {$lawyerName} assigned to the case record.",
                    'ip_address' => $request->ip(),
                ]);
            }
        });

        return redirect()->route('cases.index')->with('success', "Case {$case_number} registered successfully.");
    }

    /**
     * Show form to edit case details (Clerks & Admins only).
     */
    public function edit(CourtCase $case)
    {
        if (!auth()->user()->isClerk() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $judges = User::where('role', 'judge')->orderBy('name')->get();
        $lawyers = User::where('role', 'lawyer')->orderBy('name')->get();

        return view('cases.edit', compact('case', 'judges', 'lawyers'));
    }

    /**
     * Update case details (Clerks & Admins only).
     */
    public function update(Request $request, CourtCase $case)
    {
        if (!auth()->user()->isClerk() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'fir_number' => 'nullable|string|max:100',
            'petitioner' => 'required|string|max:255',
            'respondent' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $case->update($request->only([
            'title', 'description', 'category', 'fir_number', 'petitioner', 'respondent', 'priority'
        ]));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'court_case_id' => $case->id,
            'action' => 'Case Updated',
            'details' => 'Case particulars and details updated in register.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('cases.show', $case)->with('success', 'Case particulars updated successfully.');
    }

    /**
     * Custom route to assign or change Judge and/or Lawyer.
     */
    public function assignStaff(Request $request, CourtCase $case)
    {
        if (!auth()->user()->isClerk() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'judge_id' => 'nullable|exists:users,id',
            'lawyer_id' => 'nullable|exists:users,id',
        ]);

        $oldJudgeId = $case->judge_id;
        $oldLawyerId = $case->lawyer_id;

        $case->update([
            'judge_id' => $request->judge_id,
            'lawyer_id' => $request->lawyer_id,
        ]);

        if ($oldJudgeId != $request->judge_id) {
            $judgeName = $request->judge_id ? User::find($request->judge_id)->name : 'Unassigned';
            ActivityLog::create([
                'user_id' => auth()->id(),
                'court_case_id' => $case->id,
                'action' => 'Judge Assigned',
                'details' => "Presiding officer changed to: {$judgeName}.",
                'ip_address' => $request->ip(),
            ]);
        }

        if ($oldLawyerId != $request->lawyer_id) {
            $lawyerName = $request->lawyer_id ? User::find($request->lawyer_id)->name : 'Unassigned';
            ActivityLog::create([
                'user_id' => auth()->id(),
                'court_case_id' => $case->id,
                'action' => 'Lawyer Assigned',
                'details' => "Defense counsel changed to: {$lawyerName}.",
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()->route('cases.show', $case)->with('success', 'Assignments updated successfully.');
    }

    /**
     * Custom route to transition workflow status (Judge, Clerk, or Admin).
     */
    public function updateStatus(Request $request, CourtCase $case)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isClerk() && !$user->isJudge()) {
            abort(403, 'Unauthorized to transition case lifecycle status.');
        }

        $request->validate([
            'status' => 'required|in:filed,under_review,hearing_scheduled,evidence_submitted,judgment_pending,closed',
        ]);

        $oldStatus = $case->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return redirect()->back();
        }

        $case->update([
            'status' => $newStatus,
            'closed_at' => $newStatus === 'closed' ? Carbon::now() : $case->closed_at,
        ]);

        $statusLabels = [
            'filed' => 'Filed',
            'under_review' => 'Under Review',
            'hearing_scheduled' => 'Hearing Scheduled',
            'evidence_submitted' => 'Evidence Submitted',
            'judgment_pending' => 'Judgment Pending',
            'closed' => 'Closed',
        ];

        ActivityLog::create([
            'user_id' => $user->id,
            'court_case_id' => $case->id,
            'action' => 'Status Changed',
            'details' => "Lifecycle state advanced from '{$statusLabels[$oldStatus]}' to '{$statusLabels[$newStatus]}'.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('cases.show', $case)->with('success', "Case status transitioned to {$statusLabels[$newStatus]}.");
    }

    /**
     * Custom route to write final judgment and close case (Judge only).
     */
    public function addJudgment(Request $request, CourtCase $case)
    {
        if (!auth()->user()->isJudge() || $case->judge_id !== auth()->id()) {
            abort(403, 'Only the assigned Judge can draft a final ruling.');
        }

        $request->validate([
            'judgment' => 'required|string|min:10',
        ]);

        DB::transaction(function () use ($request, $case) {
            $case->update([
                'judgment' => $request->judgment,
                'status' => 'closed',
                'closed_at' => Carbon::now(),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'court_case_id' => $case->id,
                'action' => 'Judgment Rendered',
                'details' => 'Presiding Judge Hon. ' . auth()->user()->name . ' pronounced the final judgement decree and closed the court file.',
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()->route('cases.show', $case)->with('success', 'Final judgement ruling recorded and case file archived.');
    }
}
