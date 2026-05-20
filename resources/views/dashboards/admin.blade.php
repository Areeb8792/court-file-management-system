<x-app-layout>
    <!-- Welcome Banner -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold mb-1">Good Morning, Admin</h2>
        <p class="text-slate-400 font-medium font-jakarta">Security logs are active. Central registry metrics and database channels are fully operational.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:gavel" class="text-2xl text-blue-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-blue-500/10 text-blue-400 px-2 py-1 rounded-full">Active</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Active Litigation</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $active_cases_count }}</h3>
        </div>
        
        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:file-check" class="text-2xl text-emerald-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-emerald-500/10 text-emerald-450 px-2 py-1 rounded-full">Decided</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Closed Decrees</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $closed_cases_count }}</h3>
        </div>

        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:clock" class="text-2xl text-amber-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-amber-500/10 text-amber-450 px-2 py-1 rounded-full">Schedules</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Pending Hearings</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $pending_hearings_count }}</h3>
        </div>

        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center">
                    <iconify-icon icon="lucide:alert-circle" class="text-2xl text-rose-500"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold bg-rose-500/10 text-rose-455 px-2 py-1 rounded-full animate-pulse">Urgent</span>
            </div>
            <p class="text-slate-400 text-sm font-semibold">Urgent Case Files</p>
            <h3 class="text-3xl font-bold mt-1 tracking-tight">{{ $urgent_cases_count }}</h3>
        </div>
    </div>

    <!-- Analytics Graphs Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Chart 1: Litigation Category Allocation -->
        <div class="glass-card p-6 rounded-3xl">
            <h4 class="text-lg font-bold mb-1">Litigation Class Distribution</h4>
            <p class="text-xs text-slate-500 mb-6">Database metrics compiled by legal class.</p>
            
            <div class="space-y-4">
                @foreach($categories_chart as $c)
                    @php
                        $percentage = $active_cases_count > 0 ? round(($c->count / ($active_cases_count + $closed_cases_count)) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-slate-300 light:text-slate-700 capitalize">{{ $c->category }}</span>
                            <span class="text-indigo-400 font-extrabold">{{ $c->count }} cases ({{ $percentage }}%)</span>
                        </div>
                        <div class="h-2 bg-slate-900 light:bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-650 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Chart 2: Case status -->
        <div class="glass-card p-6 rounded-3xl">
            <h4 class="text-lg font-bold mb-1">Workflow Lifecycle Index</h4>
            <p class="text-xs text-slate-500 mb-6">Proceedings progress aggregated across registries.</p>
            
            <div class="space-y-4">
                @foreach($status_chart as $s)
                    @php
                        $percentage = $active_cases_count > 0 ? round(($s->count / ($active_cases_count + $closed_cases_count)) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-slate-300 light:text-slate-700 capitalize">{{ str_replace('_', ' ', $s->status) }}</span>
                            <span class="text-emerald-450 font-extrabold">{{ $s->count }} cases ({{ $percentage }}%)</span>
                        </div>
                        <div class="h-2 bg-slate-900 light:bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-emerald-500 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Workspace columns -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left: Case Table -->
        <div class="xl:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h4 class="text-xl font-bold">Recent Case Registries</h4>
                <a href="{{ route('cases.index') }}" class="text-sm text-blue-400 hover:underline font-semibold">View Register Registry</a>
            </div>

            <div class="glass-card rounded-3xl overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-900/30">
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Case Code & Particulars</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Presiding Bench</th>
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
                                <td class="py-5 px-6 text-slate-350 font-medium">
                                    {{ $case->judge->name ?? 'Unassigned Bench' }}
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <iconify-icon icon="lucide:chevron-right" class="text-slate-600 group-hover:text-blue-400 transition-colors text-base"></iconify-icon>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 px-6 text-center text-slate-500 font-semibold">No cases logged in system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Audited Log Feeds -->
        <div class="space-y-6">
            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Global System Audit Feed</h4>
            
            <div class="space-y-4">
                @forelse($activity_logs as $log)
                    <div class="glass-card p-4 rounded-2xl text-xs leading-normal">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-extrabold text-slate-300 light:text-slate-800">{{ $log->action }}</span>
                            <span class="text-[9px] text-slate-500">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-450 mt-0.5">{{ $log->details }}</p>
                        <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-slate-800/40 text-[9px] text-indigo-400 font-bold">
                            <span>Actor: {{ $log->user->name ?? 'System' }}</span>
                            <span>IP: {{ $log->ip_address }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 font-semibold italic">No central logs generated in active registry.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
