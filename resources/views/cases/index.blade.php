<x-app-layout>
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">Cases Directory</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Official database register of active and archived litigation filings.</p>
        </div>
        @if(auth()->user()->isClerk() || auth()->user()->isAdmin())
            <div>
                <a href="{{ route('cases.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/10 active:scale-[0.98] transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Register New Case
                </a>
            </div>
        @endif
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm mb-8">
        <form method="GET" action="{{ route('cases.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2 relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Case Code, Title, FIR, Litigant, Attorney..."
                           class="w-full pl-11 pr-4 py-2.5 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all font-semibold text-xs text-slate-800 dark:text-slate-100">
                </div>

                <!-- Category -->
                <div>
                    <select name="category" class="w-full px-4 py-2.5 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all font-semibold text-xs text-slate-700 dark:text-slate-300">
                        <option value="">All Categories</option>
                        <option value="Criminal" {{ request('category') === 'Criminal' ? 'selected' : '' }}>Criminal</option>
                        <option value="Civil" {{ request('category') === 'Civil' ? 'selected' : '' }}>Civil</option>
                        <option value="Constitutional" {{ request('category') === 'Constitutional' ? 'selected' : '' }}>Constitutional</option>
                        <option value="Family" {{ request('category') === 'Family' ? 'selected' : '' }}>Family</option>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <select name="priority" class="w-full px-4 py-2.5 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all font-semibold text-xs text-slate-700 dark:text-slate-300">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-2 border-t border-slate-100 dark:border-slate-800/60 gap-4">
                <!-- Status filters -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('cases.index', array_merge(request()->except('status'), ['status' => ''])) }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('status') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/15' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-750' }}">
                        All Statuses
                    </a>
                    @foreach(['filed' => 'Filed', 'under_review' => 'Under Review', 'hearing_scheduled' => 'Hearing Scheduled', 'evidence_submitted' => 'Evidence Submitted', 'judgment_pending' => 'Judgment Pending', 'closed' => 'Closed'] as $val => $label)
                        <a href="{{ route('cases.index', array_merge(request()->except('status'), ['status' => $val])) }}" 
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('status') === $val ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/15' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-750' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <!-- Submit / Reset Buttons -->
                <div class="flex items-center space-x-2 justify-end">
                    <a href="{{ route('cases.index') }}" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:underline">Clear Filters</a>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-bold rounded-xl text-xs hover:bg-indigo-700 active:scale-[0.98] transition-all">Filter Register</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Case Listings Container -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3 font-semibold">Case ID</th>
                        <th class="pb-3 font-semibold">Title Particulars</th>
                        <th class="pb-3 font-semibold">Priority</th>
                        <th class="pb-3 font-semibold">Litigants</th>
                        <th class="pb-3 font-semibold">Presiding Officer</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/40 text-xs font-medium">
                    @forelse($cases as $case)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                            <!-- Case ID -->
                            <td class="py-4 pr-3 font-extrabold text-indigo-600 dark:text-indigo-400">
                                {{ $case->case_number }}
                            </td>
                            <!-- Title Particulars -->
                            <td class="py-4 pr-3 max-w-[200px]">
                                <div class="truncate">
                                    <p class="text-slate-800 dark:text-slate-200 font-bold text-[13px]">{{ $case->title }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold capitalize mt-0.5">{{ $case->category }} • Filed: {{ $case->filed_at->format('M d, Y') }}</p>
                                </div>
                            </td>
                            <!-- Priority -->
                            <td class="py-4 pr-3">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full 
                                    {{ $case->priority === 'urgent' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 ring-1 ring-rose-500/20 animate-pulse' : 
                                       ($case->priority === 'high' ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400' : 
                                       ($case->priority === 'medium' ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400')) }}">
                                    {{ $case->priority }}
                                </span>
                            </td>
                            <!-- Litigants -->
                            <td class="py-4 pr-3 text-[11px] text-slate-550 dark:text-slate-400">
                                <p><span class="font-bold text-slate-400">P:</span> {{ $case->petitioner }}</p>
                                <p><span class="font-bold text-slate-400">R:</span> {{ $case->respondent }}</p>
                            </td>
                            <!-- Presiding Officer -->
                            <td class="py-4 pr-3 text-slate-600 dark:text-slate-400 font-semibold">
                                {{ $case->judge->name ?? 'Unassigned' }}
                            </td>
                            <!-- Status -->
                            <td class="py-4 pr-3">
                                @php
                                    $statusClasses = [
                                        'filed' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400',
                                        'under_review' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-400',
                                        'hearing_scheduled' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400',
                                        'evidence_submitted' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-400',
                                        'judgment_pending' => 'bg-pink-50 text-pink-700 dark:bg-pink-950/30 dark:text-pink-400',
                                        'closed' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400',
                                    ];
                                    $statusLabels = [
                                        'filed' => 'Filed',
                                        'under_review' => 'Under Review',
                                        'hearing_scheduled' => 'Hearing Scheduled',
                                        'evidence_submitted' => 'Evidence Submitted',
                                        'judgment_pending' => 'Judgment Pending',
                                        'closed' => 'Closed',
                                    ];
                                    $cls = $statusClasses[$case->status] ?? 'bg-slate-50 text-slate-700';
                                    $lbl = $statusLabels[$case->status] ?? ucfirst($case->status);
                                @endphp
                                <span class="text-[10px] font-extrabold tracking-wide uppercase px-2.5 py-1 rounded-xl {{ $cls }}">
                                    {{ $lbl }}
                                </span>
                            </td>
                            <!-- Actions -->
                            <td class="py-4 text-right">
                                <a href="{{ route('cases.show', $case->id) }}" class="inline-flex items-center justify-center px-3.5 py-1.5 bg-slate-50 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 dark:bg-slate-800 dark:hover:bg-indigo-950/40 dark:text-slate-300 dark:hover:text-indigo-400 rounded-xl font-bold transition-colors">
                                    View File
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-450 dark:text-slate-550 font-semibold">No cases match the specified parameters in court registry.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 border-t border-slate-100 dark:border-slate-800/60 pt-6">
            {{ $cases->links() }}
        </div>
    </div>
</x-app-layout>
