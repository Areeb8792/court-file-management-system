<x-app-layout>
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">Hearings Calendar</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Registry of scheduled trial dates, courtroom allocations, and hearing remarks.</p>
        </div>
        <div class="flex space-x-3">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300">
                Total Trials: {{ $hearings->total() }}
            </span>
        </div>
    </div>

    <!-- Filter drawer -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm mb-8">
        <form method="GET" action="{{ route('hearings.index') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1 max-w-sm">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2">Filter by Courtroom Location</label>
                <select name="courtroom" onchange="this.form.submit()" class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 dark:bg-slate-850 dark:border-slate-850 rounded-2xl focus:outline-none">
                    <option value="">All Courtrooms</option>
                    <option value="Courtroom A" {{ request('courtroom') === 'Courtroom A' ? 'selected' : '' }}>Courtroom A</option>
                    <option value="Courtroom B" {{ request('courtroom') === 'Courtroom B' ? 'selected' : '' }}>Courtroom B</option>
                    <option value="Courtroom 1A" {{ request('courtroom') === 'Courtroom 1A' ? 'selected' : '' }}>Courtroom 1A</option>
                    <option value="Courtroom 3B" {{ request('courtroom') === 'Courtroom 3B' ? 'selected' : '' }}>Courtroom 3B</option>
                </select>
            </div>
            <div>
                <a href="{{ route('hearings.index') }}" class="text-xs font-bold text-slate-650 dark:text-slate-400 hover:underline">Reset Filters</a>
            </div>
        </form>
    </div>

    <!-- Hearings Docket List -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="pb-3 font-semibold">Date & Time</th>
                        <th class="pb-3 font-semibold">Case Reference</th>
                        <th class="pb-3 font-semibold">Courtroom</th>
                        <th class="pb-3 font-semibold">Presiding Judge</th>
                        <th class="pb-3 font-semibold">Remarks & Notes</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/40 text-xs font-medium">
                    @forelse($hearings as $hearing)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                            <!-- Date & Time -->
                            <td class="py-4 pr-3 font-extrabold text-slate-800 dark:text-slate-200">
                                <p>{{ $hearing->hearing_date->format('M d, Y') }}</p>
                                <p class="text-[10px] text-indigo-650 dark:text-indigo-400 font-extrabold mt-0.5">{{ $hearing->hearing_date->format('h:i A') }}</p>
                            </td>
                            <!-- Case Reference -->
                            <td class="py-4 pr-3">
                                <a href="{{ route('cases.show', $hearing->courtCase->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-bold">{{ $hearing->courtCase->case_number }}</a>
                                <p class="text-[10px] text-slate-450 dark:text-slate-500 font-bold truncate max-w-[150px] mt-0.5">{{ $hearing->courtCase->title }}</p>
                            </td>
                            <!-- Courtroom -->
                            <td class="py-4 pr-3 font-bold text-slate-750 dark:text-slate-350">
                                {{ $hearing->courtroom }}
                            </td>
                            <!-- Judge -->
                            <td class="py-4 pr-3 text-slate-600 dark:text-slate-400 font-semibold">
                                {{ $hearing->judge->name ?? 'Unassigned' }}
                            </td>
                            <!-- Remarks -->
                            <td class="py-4 pr-3 max-w-[200px]">
                                @if($hearing->remarks)
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 italic truncate" title="{{ $hearing->remarks }}">
                                        {{ $hearing->remarks }}
                                    </p>
                                @else
                                    <span class="text-[10px] text-slate-400">No trial remarks logged</span>
                                @endif
                            </td>
                            <!-- Status -->
                            <td class="py-4 pr-3">
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-lg 
                                    {{ $hearing->status === 'scheduled' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400' : 
                                       ($hearing->status === 'held' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400' : 
                                       ($hearing->status === 'rescheduled' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455')) }}">
                                    {{ $hearing->status }}
                                </span>
                            </td>
                            <!-- Actions -->
                            <td class="py-4 text-right">
                                <a href="{{ route('cases.show', $hearing->courtCase->id) }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Open file</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-450 dark:text-slate-550 font-semibold">No hearings scheduled on current docket calendar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 border-t border-slate-100 dark:border-slate-800/60 pt-6">
            {{ $hearings->links() }}
        </div>
    </div>
</x-app-layout>
