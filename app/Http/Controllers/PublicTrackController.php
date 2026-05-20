<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use Illuminate\Http\Request;

class PublicTrackController extends Controller
{
    /**
     * Display the public search tracking page.
     */
    public function index()
    {
        return view('public.track');
    }

    /**
     * Search and display public case particulars and timeline.
     */
    public function search(Request $request)
    {
        $request->validate([
            'case_number' => 'required|string|max:50',
        ]);

        $caseNumber = trim($request->input('case_number'));

        $case = CourtCase::with([
            'judge',
            'hearings' => function ($q) {
                $q->orderBy('hearing_date', 'asc');
            },
            'documents' => function ($q) {
                // Public users can only see FIR and Judgements (not private lawyer exhibits)
                $q->whereIn('document_type', ['fir', 'judgment'])
                  ->whereNull('parent_id')
                  ->orderBy('created_at', 'desc');
            }
        ])->where('case_number', $caseNumber)->first();

        if (!$case) {
            return redirect()->route('public.track')
                ->withInput()
                ->with('error', "No case record was found matching case code '{$caseNumber}'. Please double check the number and try again.");
        }

        return view('public.track_results', compact('case'));
    }
}
