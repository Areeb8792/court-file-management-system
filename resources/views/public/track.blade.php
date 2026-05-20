<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>National Case Tracking Portal | COURTFLOW</title>

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
            <div class="flex items-center space-x-2">
                <span class="p-2 rounded-xl bg-gradient-to-tr from-amber-500 to-indigo-600 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </span>
                <span class="font-extrabold text-lg bg-gradient-to-r from-amber-600 to-indigo-600 bg-clip-text text-transparent tracking-wider">COURTFLOW</span>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Theme switch -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                        class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-900 dark:text-slate-400 focus:outline-none transition-colors">
                    <svg x-show="!darkMode" class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 12.728A9 9 0 115.636 5.636m12.728 12.728A9 9 0 015.636 5.636"></path>
                    </svg>
                    <svg x-show="darkMode" class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-indigo-600">Secure Staff Login</a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-5 gap-12 items-center">
                <!-- Left: Branding text -->
                <div class="lg:col-span-2 space-y-6 text-left">
                    <span class="text-xs font-bold px-3 py-1 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 rounded-full border border-indigo-200 dark:border-indigo-800">
                        National Judicial Data Grid
                    </span>
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight">
                        Public Docket Directory
                    </h1>
                    <p class="text-sm text-slate-550 dark:text-slate-450 leading-relaxed font-medium">
                        Search and inspect judicial proceeding timelines, next courtroom listed dates, and public legal decrees from the centralized federal judicial register.
                    </p>
                </div>

                <!-- Right: Search Card -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
                        <!-- Glow effect -->
                        <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 h-48 w-48 rounded-full bg-indigo-500/5 blur-3xl"></div>
                        
                        <h2 class="text-2xl font-extrabold mb-2 leading-tight">Lodge Case Lookup</h2>
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mb-6">Input the unique judicial filing number to pull live records.</p>
                        
                        @if(session('error'))
                            <div class="p-4 mb-6 text-rose-800 border border-rose-250 bg-rose-50 dark:bg-rose-950/20 dark:text-rose-455 dark:border-rose-900 rounded-2xl text-xs font-semibold">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('public.track.search') }}" class="space-y-4">
                            @csrf
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" name="case_number" placeholder="Filing Code (e.g., CRT-2026-0001)" required value="{{ old('case_number') }}"
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all font-semibold text-xs text-slate-800 dark:text-slate-100">
                            </div>
                            
                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-750 text-white font-bold rounded-2xl active:scale-[0.98] transition-all text-xs shadow-lg shadow-indigo-500/10">
                                Pull Registry Records
                            </button>
                        </form>
                    </div>

                    <!-- Help widgets -->
                    <div class="bg-indigo-50/50 dark:bg-indigo-950/10 rounded-2xl p-5 border border-indigo-100/50 dark:border-indigo-900/30 text-xs font-medium text-slate-600 dark:text-slate-400">
                        <p class="font-bold text-indigo-700 dark:text-indigo-400 mb-1">Public Register Privacy Guideline</p>
                        <p class="text-[11px] leading-relaxed">
                            Under transparency guidelines, public tracking is anonymous. However, private attorney-client filings, sealed evidence exhibits, and case transcripts are restricted to verified legal counsel. Only police reports (FIR) and final decrees are made public.
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full border-t border-slate-100 dark:border-slate-900 py-6 text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest">
            © 2026 Federal Judiciary Grid. Central Court registry system. Secure AES-256.
        </footer>
    </body>
</html>
