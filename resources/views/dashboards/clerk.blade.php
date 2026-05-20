<x-app-layout>
    <!-- Welcome Banner -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold mb-1">Good Morning, {{ auth()->user()->name }}</h2>
        <p class="text-slate-400 font-medium">Registry Desk is active. You have {{ $today_hearings_count }} case management hearings scheduled for today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:briefcase" class="text-2xl text-blue-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-blue-500/10 text-blue-400 px-2 py-1 rounded-full">Active</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Total Active Cases</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $active_cases_count }}</h3>
        </div>
        
        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:file-plus" class="text-2xl text-emerald-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-emerald-500/10 text-emerald-450 px-2 py-1 rounded-full">New Filings</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Filed Status Cases</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $filed_cases_count }}</h3>
        </div>

        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:clock" class="text-2xl text-amber-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-amber-500/10 text-amber-450 px-2 py-1 rounded-full">Today</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Today's Hearings</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $today_hearings_count }}</h3>
        </div>

        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:alert-triangle" class="text-2xl text-rose-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-rose-500/10 text-rose-450 px-2 py-1 rounded-full animate-pulse">Fast Track</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Urgent Priority Cases</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $urgent_cases_count }}</h3>
        </div>
    </div>

    <!-- Main Content Workspace -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left: Case Filings Table -->
        <div class="xl:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h4 class="text-xl font-bold">Recent Case Filings</h4>
                <a href="{{ route('cases.index') }}" class="text-sm text-blue-400 hover:underline font-semibold">View Registry Directory</a>
            </div>
            
            <div class="glass-card rounded-3xl overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-900/30">
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Case Code & Particulars</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Workflow Status</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Assigned Judge</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Filed At</th>
                            <th class="py-4 px-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40 text-xs font-semibold">
                        @forelse($recent_cases as $case)
                            <tr class="hover:bg-slate-800/30 transition-colors cursor-pointer group" onclick="window.location='{{ route('cases.show', $case->id) }}'">
                                <td class="py-5 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-white text-[13px]">{{ $case->case_number }}</span>
                                        <span class="text-slate-500 text-[11px] font-medium truncate max-w-[200px] mt-0.5">{{ $case->title }}</span>
                                    </div>
                                </td>
                                <td class="py-5 px-6">
                                    @php
                                        $statuses = [
                                            'filed' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                            'under_review' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                            'hearing_scheduled' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            'evidence_submitted' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                            'judgment_pending' => 'bg-pink-500/10 text-pink-400 border-pink-500/20',
                                            'closed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        ];
                                        $cls = $statuses[$case->status] ?? 'bg-slate-500/10 text-slate-400';
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold border uppercase tracking-wider {{ $cls }}">
                                        {{ str_replace('_', ' ', $case->status) }}
                                    </span>
                                </td>
                                <td class="py-5 px-6 text-slate-350 font-medium">
                                    {{ $case->judge->name ?? 'Bench Unassigned' }}
                                </td>
                                <td class="py-5 px-6 text-slate-450 font-medium">
                                    {{ $case->filed_at->format('M d, Y') }}
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <iconify-icon icon="lucide:chevron-right" class="text-slate-650 group-hover:text-blue-400 transition-colors text-base"></iconify-icon>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 px-6 text-center text-slate-500 font-semibold">No recent filings recorded in register.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side: Sidebar Widgets -->
        <div class="space-y-8">
            <!-- New Case filing action widget -->
            <div class="glass-card p-6 rounded-3xl border-dashed border-2 border-slate-700 bg-slate-900/10 hover:bg-slate-900/30 transition-all cursor-pointer group" onclick="window.location='{{ route('cases.create') }}'">
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-full bg-slate-800/80 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <iconify-icon icon="lucide:file-plus-2" class="text-2xl text-slate-500 group-hover:text-blue-500 transition-colors"></iconify-icon>
                    </div>
                    <h5 class="text-base font-bold mb-1">New Case Filing</h5>
                    <p class="text-[11px] text-slate-500 px-4 leading-normal">Register lawsuit particulars, define petitioner/respondent, and lodge official court files.</p>
                    <a href="{{ route('cases.create') }}" class="mt-4 text-xs font-bold text-blue-500 hover:underline">Launch Filing Chamber</a>
                </div>
            </div>

            <!-- Auditor Activity Stream -->
            <div>
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Your Recent Actions</h4>
                <div class="space-y-4">
                    @forelse($recent_activities as $log)
                        <div class="flex gap-3 text-xs leading-normal">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center shrink-0">
                                <iconify-icon icon="lucide:activity" class="text-indigo-400"></iconify-icon>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-300">
                                    {{ $log->action }} on 
                                    @if($log->courtCase)
                                        <a href="{{ route('cases.show', $log->courtCase->id) }}" class="text-white hover:underline">{{ $log->courtCase->case_number }}</a>
                                    @else
                                        Registry
                                    @endif
                                </p>
                                <p class="text-[10px] text-slate-500 mt-0.5 leading-snug">{{ $log->details }}</p>
                                <p class="text-[9px] text-slate-600 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 font-semibold italic">No actions logged during current session.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
