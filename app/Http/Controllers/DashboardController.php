<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Models\Hearing;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Redirect root dashboard route to role-based dashboard.
     */
    public function index()
    {
        $role = auth()->user()->role;
        return redirect()->route($role . '.dashboard');
    }

    /**
     * Admin Dashboard View
     */
    public function admin()
    {
        $active_cases_count = CourtCase::where('status', '!=', 'closed')->count();
        $closed_cases_count = CourtCase::where('status', 'closed')->count();
        $urgent_cases_count = CourtCase::where('status', '!=', 'closed')->where('priority', 'urgent')->count();
        $pending_hearings_count = Hearing::where('status', 'scheduled')->count();
        $today_hearings_count = Hearing::whereDate('hearing_date', Carbon::today())->count();
        
        $activity_logs = ActivityLog::with(['user', 'courtCase'])->latest()->take(8)->get();
        $cases = CourtCase::with(['judge', 'lawyer'])->latest()->take(5)->get();
        
        // Distribution for visual charts
        $categories_chart = CourtCase::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->get();
            
        $status_chart = CourtCase::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        return view('dashboards.admin', compact(
            'active_cases_count',
            'closed_cases_count',
            'urgent_cases_count',
            'pending_hearings_count',
            'today_hearings_count',
            'activity_logs',
            'cases',
            'categories_chart',
            'status_chart'
        ));
    }

    /**
     * Clerk Dashboard View
     */
    public function clerk()
    {
        $active_cases_count = CourtCase::where('status', '!=', 'closed')->count();
        $filed_cases_count = CourtCase::where('status', 'filed')->count();
        $today_hearings_count = Hearing::whereDate('hearing_date', Carbon::today())->count();
        $urgent_cases_count = CourtCase::where('status', '!=', 'closed')->where('priority', 'urgent')->count();
        
        $recent_cases = CourtCase::with(['judge', 'lawyer'])->latest()->take(5)->get();
        $recent_activities = ActivityLog::with(['user', 'courtCase'])
            ->where('user_id', auth()->id())
            ->latest()
            ->take(6)
            ->get();

        return view('dashboards.clerk', compact(
            'active_cases_count',
            'filed_cases_count',
            'today_hearings_count',
            'urgent_cases_count',
            'recent_cases',
            'recent_activities'
        ));
    }

    /**
     * Judge Dashboard View
     */
    public function judge()
    {
        $judgeId = auth()->id();
        $active_cases_count = CourtCase::where('judge_id', $judgeId)->where('status', '!=', 'closed')->count();
        $closed_cases_count = CourtCase::where('judge_id', $judgeId)->where('status', 'closed')->count();
        $urgent_cases_count = CourtCase::where('judge_id', $judgeId)->where('status', '!=', 'closed')->where('priority', 'urgent')->count();
        
        $today_hearings = Hearing::with('courtCase')
            ->where('judge_id', $judgeId)
            ->whereDate('hearing_date', Carbon::today())
            ->get();
            
        $upcoming_hearings = Hearing::with('courtCase')
            ->where('judge_id', $judgeId)
            ->where('hearing_date', '>', Carbon::now())
            ->where('status', 'scheduled')
            ->orderBy('hearing_date')
            ->take(5)
            ->get();
            
        $cases = CourtCase::where('judge_id', $judgeId)->latest()->get();

        return view('dashboards.judge', compact(
            'active_cases_count',
            'closed_cases_count',
            'urgent_cases_count',
            'today_hearings',
            'upcoming_hearings',
            'cases'
        ));
    }

    /**
     * Lawyer Dashboard View
     */
    public function lawyer()
    {
        $lawyerId = auth()->id();
        $active_cases_count = CourtCase::where('lawyer_id', $lawyerId)->where('status', '!=', 'closed')->count();
        $closed_cases_count = CourtCase::where('lawyer_id', $lawyerId)->where('status', 'closed')->count();
        $urgent_cases_count = CourtCase::where('lawyer_id', $lawyerId)->where('status', '!=', 'closed')->where('priority', 'urgent')->count();
        
        $upcoming_hearings = Hearing::with('courtCase')
            ->whereHas('courtCase', function($q) use ($lawyerId) {
                $q->where('lawyer_id', $lawyerId);
            })
            ->where('hearing_date', '>', Carbon::now())
            ->where('status', 'scheduled')
            ->orderBy('hearing_date')
            ->take(5)
            ->get();
            
        $cases = CourtCase::with('judge')->where('lawyer_id', $lawyerId)->latest()->get();

        return view('dashboards.lawyer', compact(
            'active_cases_count',
            'closed_cases_count',
            'urgent_cases_count',
            'upcoming_hearings',
            'cases'
        ));
    }

    /**
     * Public Dashboard View
     */
    public function public()
    {
        // Simple public dashboard allowing tracking and viewing general public notices
        $recent_cases = CourtCase::where('status', '!=', 'closed')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.public', compact('recent_cases'));
    }
}
