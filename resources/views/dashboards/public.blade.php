<x-app-layout>
    <!-- Welcome Banner -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold mb-1">Good Morning, Observer</h2>
        <p class="text-slate-400 font-medium">Public Access Center. Search public listings, check next trial hearing dates, and inspect legal notices.</p>
    </div>

    <!-- Main Workspace columns -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column: Search box & General listings -->
        <div class="xl:col-span-2 space-y-8">
            <!-- Glass search console -->
            <div class="glass-card p-6 rounded-3xl relative overflow-hidden">
                <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 h-36 w-36 rounded-full bg-blue-500/5 blur-3xl"></div>
                <h3 class="text-xl font-bold mb-2">Registry Case Lookup</h3>
                <p class="text-xs text-slate-500 mb-6">Input any unique case registry code (e.g. CRT-2026-0001) to track proceedings progress.</p>
                
                <form method="POST" action="{{ route('public.track.search') }}" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <div class="relative flex-1">
                        <iconify-icon icon="lucide:search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></iconify-icon>
                        <input type="text" name="case_number" placeholder="Enter Case ID (e.g., CRT-2026-0001)" required 
                               class="w-full bg-slate-950/80 border border-slate-800 rounded-xl py-3 pl-11 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 text-white font-semibold">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-750 text-white font-bold px-6 py-3 rounded-xl text-xs active:scale-[0.98] transition-all shrink-0">
                        Search Case Registry
                    </button>
                </form>
            </div>

            <!-- Table of recent public cases -->
            <div class="space-y-4">
                <h4 class="text-lg font-bold">Recent Registered Listings</h4>
                
                <div class="glass-card rounded-3xl overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-900/30 text-xs font-bold text-slate-500">
                                <th class="py-4 px-6 uppercase tracking-wider">Case Code</th>
                                <th class="py-4 px-6 uppercase tracking-wider">Litigation Class</th>
                                <th class="py-4 px-6 uppercase tracking-wider">Status</th>
                                <th class="py-4 px-6"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40 text-xs font-semibold">
                            @forelse($recent_cases as $case)
                                <tr class="hover:bg-slate-800/30 transition-colors cursor-pointer group" onclick="window.location='{{ route('cases.show', $case->id) }}'">
                                    <td class="py-5 px-6">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-white text-[13px]">{{ $case->case_number }}</span>
                                            <span class="text-slate-500 text-[11px] font-medium truncate max-w-[250px] mt-0.5">{{ $case->title }}</span>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6 text-slate-350 capitalize">
                                        {{ $case->category }}
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
                                        <iconify-icon icon="lucide:chevron-right" class="text-slate-650 group-hover:text-blue-400 transition-colors text-base"></iconify-icon>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 px-6 text-center text-slate-500 font-semibold">No active cases registered in public domain.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar Notices -->
        <div class="space-y-6">
            <h4 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Procedural Notices</h4>
            
            <div class="glass-card p-5 rounded-2xl text-xs space-y-4 font-medium leading-normal text-slate-400">
                <div class="border-b border-slate-800 pb-3">
                    <p class="font-bold text-white mb-1">🏛️ Online Trial Bookings</p>
                    <p class="text-[11px]">Courtroom trial hearing grids are updated daily. Citizens have access to attend listed public trials unless ordered closed by presiding benches.</p>
                </div>
                <div class="border-b border-slate-800 pb-3">
                    <p class="font-bold text-white mb-1">⚖️ Free Legal Assistance clinics</p>
                    <p class="text-[11px]">Counsel mediation chambers operate on weekends in Room 3B from 09:00 AM to 01:00 PM. No bookings needed.</p>
                </div>
                <div>
                    <p class="font-bold text-white mb-1">🔐 File Confidentiality Act</p>
                    <p class="text-[11px]">Sealed evidentiary items, expert testimonies, and family proceedings transcripts require authenticated legal counsel sign-in to view.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
