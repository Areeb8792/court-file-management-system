<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tracking Results | COURTFLOW</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen flex flex-col justify-between transition-colors duration-200">
        <!-- Top Navbar -->
        <header class="w-full max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="{{ route('public.track') }}" class="flex items-center space-x-2">
                <span class="p-2 rounded-xl bg-gradient-to-tr from-amber-500 to-indigo-600 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </span>
                <span class="font-extrabold text-lg bg-gradient-to-r from-amber-600 to-indigo-600 bg-clip-text text-transparent tracking-wider">COURTFLOW</span>
            </a>
            
            <div class="flex items-center space-x-4">
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                        class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-900 dark:text-slate-400 focus:outline-none transition-colors">
                    <svg x-show="!darkMode" class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 12.728A9 9 0 115.636 5.636m12.728 12.728A9 9 0 015.636 5.636"></path>
                    </svg>
                    <svg x-show="darkMode" class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
                <a href="{{ route('public.track') }}" class="text-xs font-bold text-slate-650 dark:text-slate-400 hover:underline">New Lookup</a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8 space-y-8">
            <!-- Case Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-extrabold px-3 py-1 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 rounded-xl">Judicial Registry Code: {{ $case->case_number }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 capitalize">
                            {{ $case->priority }} Priority
                        </span>
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight mt-2 leading-tight">{{ $case->title }}</h1>
                </div>
            </div>

            <!-- Workflow Progress Timeline -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-450 dark:text-slate-550 mb-6">Workflow Lifecycle Timeline</h3>
                
                @php
                    $statuses = ['filed', 'under_review', 'hearing_scheduled', 'evidence_submitted', 'judgment_pending', 'closed'];
                    $statusLabels = [
                        'filed' => 'Filed',
                        'under_review' => 'Under Review',
                        'hearing_scheduled' => 'Hearing Scheduled',
                        'evidence_submitted' => 'Evidence Submitted',
                        'judgment_pending' => 'Judgment Pending',
                        'closed' => 'Closed / Decided'
                    ];
                    $currentIdx = array_search($case->status, $statuses);
                @endphp

                <div class="relative flex flex-col md:flex-row md:items-center justify-between space-y-6 md:space-y-0 md:space-x-4">
                    <div class="absolute hidden md:block left-0 right-0 top-1/2 -translate-y-4 h-1 bg-slate-100 dark:bg-slate-800 z-0"></div>
                    @if($currentIdx > 0)
                        <div class="absolute hidden md:block left-0 top-1/2 -translate-y-4 h-1 bg-gradient-to-r from-indigo-500 to-indigo-650 z-0" 
                             style="width: {{ ($currentIdx / (count($statuses) - 1)) * 100 }}%"></div>
                    @endif

                    @foreach($statuses as $index => $status)
                        @php
                            $isCompleted = $index < $currentIdx;
                            $isActive = $index === $currentIdx;
                            $isUpcoming = $index > $currentIdx;
                        @endphp
                        <div class="flex md:flex-col items-center flex-1 relative z-10 space-x-4 md:space-x-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full transition-all duration-300 
                                {{ $isCompleted ? 'bg-indigo-600 text-white shadow-md' : 
                                   ($isActive ? 'bg-gradient-to-tr from-amber-500 to-indigo-600 text-white ring-4 ring-indigo-500/20 animate-pulse' : 
                                   'bg-slate-100 dark:bg-slate-800 text-slate-400') }}">
                                @if($isCompleted)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.0" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <div class="text-left md:text-center mt-0 md:mt-3">
                                <p class="text-xs font-bold leading-tight {{ $isActive ? 'text-indigo-600 dark:text-indigo-400 font-extrabold' : ($isUpcoming ? 'text-slate-400' : 'text-slate-800 dark:text-slate-200') }}">{{ $statusLabels[$status] }}</p>
                                <p class="text-[9px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">{{ $isActive ? 'Active Phase' : ($isUpcoming ? 'Pending' : 'Completed') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Content Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Case Details Card -->
                <div class="space-y-8">
                    <!-- Particulars -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-lg font-bold mb-4">Litigation Particulars</h3>
                        
                        <div class="space-y-4 text-xs font-medium">
                            <div>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">Suit Name</p>
                                <p class="text-slate-800 dark:text-slate-200 font-bold mt-1 leading-snug">{{ $case->title }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100 dark:border-slate-850">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Litigation Class</p>
                                    <p class="text-slate-800 dark:text-slate-200 font-bold mt-0.5 capitalize">{{ $case->category }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Filing Date</p>
                                    <p class="text-slate-800 dark:text-slate-200 font-bold mt-0.5">{{ $case->filed_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 dark:border-slate-850 space-y-2">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Petitioner (Plaintiff)</p>
                                    <p class="text-slate-800 dark:text-slate-200 font-bold mt-0.5">{{ $case->petitioner }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Respondent (Defendant)</p>
                                    <p class="text-slate-800 dark:text-slate-200 font-bold mt-0.5">{{ $case->respondent }}</p>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 dark:border-slate-850 flex items-center justify-between">
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Presiding Judge</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $case->judge->name ?? 'Unassigned' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Final decree pronounced (if closed) -->
                    @if($case->status === 'closed' && $case->judgment)
                        <div class="bg-emerald-50/50 dark:bg-emerald-950/10 border border-emerald-250 dark:border-emerald-900 rounded-3xl p-6 shadow-sm">
                            <span class="text-[9px] font-extrabold uppercase bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-450 px-2 py-0.5 rounded-lg">Official Decree Pronounced</span>
                            <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-100 mt-3 mb-2">Final Judgment ruling</h3>
                            <p class="text-xs font-semibold leading-relaxed text-slate-650 dark:text-slate-400 italic">
                                "{{ $case->judgment }}"
                            </p>
                            <p class="text-[9px] font-bold text-slate-400 mt-4 uppercase">Hon. Presiding Officer bench decree • {{ $case->closed_at->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Right: Hearings & public documents vault -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Hearings calendar -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-lg font-bold mb-1">Trial Hearings</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mb-4">Trial docket listings and schedules.</p>

                        <div class="space-y-4">
                            @forelse($case->hearings as $hearing)
                                <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-100 dark:border-slate-800/30 text-xs">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-bold text-slate-700 dark:text-slate-200">{{ $hearing->courtroom }}</span>
                                        <span class="text-[10px] font-extrabold uppercase text-slate-400">{{ $hearing->hearing_date->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-slate-200 text-slate-750 dark:bg-slate-700 dark:text-slate-300">{{ $hearing->status }}</span>
                                    @if($hearing->remarks)
                                        <p class="text-[11px] text-slate-500 mt-2 italic"><span class="font-bold">Remarks:</span> {{ $hearing->remarks }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-450 text-center py-6">No trials scheduled for this filing.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Public Documents Vault -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-lg font-bold mb-1">Public File Vault</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mb-4">Official documents open to public inspection.</p>

                        <div class="space-y-3">
                            @forelse($case->documents as $doc)
                                <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800/30 flex items-center justify-between text-xs">
                                    <div>
                                        <span class="text-[9px] font-extrabold uppercase bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 px-2 py-0.5 rounded-lg">{{ $doc->document_type }}</span>
                                        <h4 class="font-bold mt-1 text-slate-800 dark:text-slate-200">{{ $doc->name }}</h4>
                                    </div>
                                    <a href="{{ route('documents.download', $doc->id) }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Download</a>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-450 text-center py-4">No public records currently published.</p>
                            @endforelse

                            <div class="p-4 bg-indigo-50/20 dark:bg-indigo-950/5 border border-indigo-100/50 dark:border-indigo-900/10 rounded-2xl flex items-start space-x-3 text-[10px] font-bold text-indigo-650 dark:text-indigo-400">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span>Confidential exhibits and advocate motion transcripts are locked from public view under federal privacy act Section 14.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full border-t border-slate-100 dark:border-slate-900 py-6 text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-12">
            © 2026 Federal Judiciary Grid. Central Court registry system. Secure AES-256.
        </footer>
    </body>
</html>
