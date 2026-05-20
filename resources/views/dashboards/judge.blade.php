<x-app-layout>
    <!-- Welcome Banner -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold mb-1">Good Morning, {{ auth()->user()->name }}</h2>
            <p class="text-slate-400 font-medium">Honorable Presiding Officer bench directory. You have {{ $today_hearings->count() }} proceedings listed today.</p>
        </div>
        <div class="flex items-center shrink-0">
            <span class="text-xs font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-3.5 py-1.5 rounded-2xl">
                Bench Active
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:briefcase" class="text-2xl text-blue-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-blue-500/10 text-blue-450 px-2 py-1 rounded-full">Active</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Presiding Caseload</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $active_cases_count }}</h3>
        </div>
        
        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:file-check" class="text-2xl text-emerald-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-emerald-500/10 text-emerald-450 px-2 py-1 rounded-full">Decreed</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Decided Bench Rulings</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $closed_cases_count }}</h3>
        </div>

        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:alert-circle" class="text-2xl text-rose-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-rose-500/10 text-rose-455 px-2 py-1 rounded-full animate-pulse">Urgent</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Urgent Litigations</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $urgent_cases_count }}</h3>
        </div>
    </div>

    <!-- Main Workspace columns -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left: Case docket listings -->
        <div class="xl:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h4 class="text-xl font-bold">Your Presiding Cases</h4>
                <a href="{{ route('cases.index') }}" class="text-sm text-blue-400 hover:underline font-semibold">View Master Register</a>
            </div>

            <div class="glass-card rounded-3xl overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-900/30">
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Case Code & Title</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Parties</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Workflow Stage</th>
                            <th class="py-4 px-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40 text-xs font-semibold">
                        @forelse($cases as $case)
                            <tr class="hover:bg-slate-800/30 transition-colors cursor-pointer group" onclick="window.location='{{ route('cases.show', $case->id) }}'">
                                <td class="py-5 px-6">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-white text-[13px]">{{ $case->case_number }}</span>
                                        <span class="text-slate-500 text-[11px] font-medium truncate max-w-[200px] mt-0.5">{{ $case->title }}</span>
                                    </div>
                                </td>
                                <td class="py-5 px-6 text-slate-350 leading-normal">
                                    <p><span class="text-slate-500 text-[10px] uppercase font-bold">P:</span> {{ $case->petitioner }}</p>
                                    <p><span class="text-slate-500 text-[10px] uppercase font-bold">R:</span> {{ $case->respondent }}</p>
                                </td>
                                <td class="py-5 px-6">
                                    @php
                                        $statuses = [
                                            'filed' => 'bg-blue-500/10 text-blue-450 border-blue-500/20',
                                            'under_review' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                            'hearing_scheduled' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            'evidence_submitted' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                            'judgment_pending' => 'bg-pink-500/10 text-pink-400 border-pink-500/20',
                                            'closed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        ];
                                        $cls = $statuses[$case->status] ?? 'bg-slate-500/10 text-slate-400';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 border rounded-full text-[9px] font-extrabold uppercase tracking-wider {{ $cls }}">
                                        {{ str_replace('_', ' ', $case->status) }}
                                    </span>
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <iconify-icon icon="lucide:chevron-right" class="text-slate-600 group-hover:text-blue-400 transition-colors text-base"></iconify-icon>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 px-6 text-center text-slate-500 font-semibold">No cases assigned to your bench roster.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side: Today's Trial Calendar -->
        <div class="space-y-8">
            <!-- Today's listed schedules -->
            <div>
                <h4 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">Presiding Today</h4>
                <div class="space-y-4">
                    @forelse($today_hearings as $hearing)
                        <div class="glass-card p-4 rounded-2xl flex gap-4 border-l-4 border-l-amber-500 cursor-pointer" onclick="window.location='{{ route('cases.show', $hearing->courtCase->id) }}'">
                            <div class="flex flex-col items-center justify-center min-w-[50px] border-r border-slate-800">
                                <span class="text-xs font-extrabold text-slate-300">{{ $hearing->hearing_date->format('H:i') }}</span>
                                <span class="text-[9px] text-slate-500 uppercase font-bold mt-0.5">{{ $hearing->hearing_date->format('A') }}</span>
                            </div>
                            <div class="flex-1 text-xs">
                                <p class="font-extrabold text-white">{{ $hearing->courtCase->case_number }}</p>
                                <p class="text-slate-450 line-clamp-1 mt-0.5">{{ $hearing->courtroom }} • {{ $hearing->courtCase->title }}</p>
                                <span class="inline-block text-[9px] font-bold text-amber-400 mt-2 bg-amber-500/10 px-2 py-0.5 rounded-full capitalize">{{ $hearing->status }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 font-semibold italic">No hearing sessions listed for today.</p>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming hearing schedules -->
            <div>
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Upcoming Bench Roster</h4>
                <div class="space-y-3">
                    @forelse($upcoming_hearings as $h)
                        <div class="p-3.5 bg-slate-900/40 rounded-2xl border border-slate-900 flex justify-between items-center text-xs">
                            <div>
                                <p class="font-bold text-slate-200">{{ $h->courtCase->case_number }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">{{ $h->courtroom }} • {{ $h->hearing_date->format('M d, Y') }}</p>
                            </div>
                            <span class="text-[9px] font-bold text-indigo-400 uppercase">{{ $h->hearing_date->format('h:i A') }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 font-semibold italic">No upcoming sessions listed.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
