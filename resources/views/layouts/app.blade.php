<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: true, sidebarOpen: false }" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>JurisFlow - Court File Management Portal</title>

        <!-- Tailwind Play CDN Fallback (Ensures instant styling if Vite server is offline) -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Fonts & Iconify -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            slate: {
                                850: '#0f172a',
                                900: '#0f172a',
                                950: '#020617'
                            }
                        }
                    }
                }
            }
        </script>

        <style type="text/tailwindcss">
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #020617;
                color: #f8fafc;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Space Grotesk', sans-serif;
            }
            .glass-card {
                background: rgba(30, 41, 59, 0.4);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            .sidebar-link-active {
                background: #1d4ed8;
                box-shadow: 0 0 20px rgba(29, 78, 216, 0.35);
                color: #ffffff !important;
            }
            ::-webkit-scrollbar {
                width: 6px;
            }
            ::-webkit-scrollbar-track {
                background: #020617;
            }
            ::-webkit-scrollbar-thumb {
                background: #334155;
                border-radius: 10px;
            }
        </style>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-[#020617] text-[#f8fafc] h-screen overflow-hidden">
        <!-- Toast Alerts -->
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4500)" 
             class="fixed top-4 right-4 z-50 max-w-sm">
            @if(session('success'))
                <div class="flex items-center p-4 mb-4 text-emerald-300 border border-emerald-800/80 rounded-2xl bg-emerald-950/90 shadow-2xl backdrop-blur-md" role="alert">
                    <iconify-icon icon="lucide:check-circle" class="text-xl text-emerald-450 mr-2"></iconify-icon>
                    <div class="ms-3 text-xs font-semibold pr-2">
                        {{ session('success') }}
                    </div>
                    <button type="button" @click="show = false" class="ms-auto text-slate-400 hover:text-white rounded-lg p-1.5 inline-flex items-center justify-center">
                        <iconify-icon icon="lucide:x" class="text-xs"></iconify-icon>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="flex items-center p-4 mb-4 text-rose-305 border border-rose-800/85 rounded-2xl bg-rose-950/90 shadow-2xl backdrop-blur-md" role="alert">
                    <iconify-icon icon="lucide:alert-circle" class="text-xl text-rose-450 mr-2"></iconify-icon>
                    <div class="ms-3 text-xs font-semibold pr-2">
                        {{ $errors->first() }}
                    </div>
                    <button type="button" @click="show = false" class="ms-auto text-slate-400 hover:text-white rounded-lg p-1.5 inline-flex items-center justify-center">
                        <iconify-icon icon="lucide:x" class="text-xs"></iconify-icon>
                    </button>
                </div>
            @endif
        </div>

        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar (Responsive md: viewport and larger) -->
            <aside class="hidden md:flex md:flex-col md:w-72 bg-[#020617] border-r border-slate-800 flex-shrink-0">
                <!-- Branding logo header -->
                <div class="p-8 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/30">
                        <iconify-icon icon="lucide:scale" class="text-2xl text-white"></iconify-icon>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-white">JurisFlow</h1>
                        <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-widest leading-none mt-1">Court e-Portal</p>
                    </div>
                </div>

                <!-- Navigation List -->
                <nav class="flex-1 px-4 py-2 space-y-1.5 overflow-y-auto">
                    <!-- Dashboard Link -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <iconify-icon icon="lucide:layout-dashboard" class="text-xl"></iconify-icon>
                        <span class="font-semibold text-xs">Dashboard</span>
                    </a>

                    <!-- Case Filing Tab (Separate Tab immediately after Dashboard) -->
                    <a href="{{ route('cases.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('cases.create') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <iconify-icon icon="lucide:file-plus" class="text-xl"></iconify-icon>
                        <span class="font-semibold text-xs">Case Filing</span>
                    </a>

                    <!-- Cases Register -->
                    <a href="{{ route('cases.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('cases.index') || request()->routeIs('cases.show') || request()->routeIs('cases.edit') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <iconify-icon icon="lucide:briefcase" class="text-xl"></iconify-icon>
                        <span class="font-semibold text-xs">
                            @if(auth()->user()->isPublic())
                                Public Case Tracking
                            @else
                                Active Cases
                            @endif
                        </span>
                    </a>

                    <!-- Hearing Schedule -->
                    <a href="{{ route('hearings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('hearings.*') ? 'sidebar-link-active' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <iconify-icon icon="lucide:calendar-range" class="text-xl"></iconify-icon>
                        <span class="font-semibold text-xs">Hearing Schedule</span>
                    </a>

                    @if(auth()->user()->isAdmin())
                        <div class="pt-6 pb-2">
                            <p class="px-4 text-[9px] font-bold text-slate-600 uppercase tracking-widest">Administration</p>
                        </div>
                        
                        <a href="{{ route('cases.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                            <iconify-icon icon="lucide:users" class="text-xl"></iconify-icon>
                            <span class="font-semibold text-xs">Staff Directory</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                            <iconify-icon icon="lucide:settings" class="text-xl"></iconify-icon>
                            <span class="font-semibold text-xs">System Settings</span>
                        </a>
                    @endif


                </nav>

                <!-- Profile Footer Section with Log Out Feature -->
                <div class="p-4 border-t border-slate-800">
                    <div class="flex items-center gap-3 p-3 bg-slate-900/50 rounded-2xl border border-slate-850">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-650 flex items-center justify-center font-bold text-sm text-white uppercase shadow-md shadow-blue-500/20">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="flex-1 overflow-hidden text-left">
                            <p class="text-sm font-semibold truncate text-white leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-500 truncate capitalize font-semibold mt-0.5">{{ auth()->user()->role }}</p>
                        </div>
                        
                        <!-- Secure Log Out trigger button -->
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="inline">
                            @csrf
                            <button type="submit" class="p-1 text-slate-500 hover:text-red-400 transition-colors" title="Sign Out">
                                <iconify-icon icon="lucide:log-out" class="text-lg"></iconify-icon>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Sidebar (Mobile View Drawer) -->
            <div x-show="sidebarOpen" class="fixed inset-0 z-50 flex md:hidden" style="display: none;">
                <!-- Backdrop -->
                <div @click="sidebarOpen = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity duration-300"></div>

                <!-- Menu Content -->
                <aside class="relative flex flex-col w-full max-w-xs bg-[#020617] h-full border-r border-slate-800">
                    <div class="p-8 flex items-center justify-between border-b border-slate-850">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                                <iconify-icon icon="lucide:scale" class="text-2xl text-white"></iconify-icon>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold tracking-tight text-white">JurisFlow</h1>
                            </div>
                        </div>
                        <button @click="sidebarOpen = false" class="p-1 text-slate-400 hover:text-white">
                            <iconify-icon icon="lucide:x" class="text-2xl"></iconify-icon>
                        </button>
                    </div>

                    <nav class="flex-1 px-4 py-4 space-y-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'sidebar-link-active' : 'text-slate-400' }}">
                            <iconify-icon icon="lucide:layout-dashboard" class="text-xl"></iconify-icon>
                            <span class="font-semibold text-xs">Dashboard</span>
                        </a>
                        <a href="{{ route('cases.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('cases.create') ? 'sidebar-link-active' : 'text-slate-400' }}">
                            <iconify-icon icon="lucide:file-plus" class="text-xl"></iconify-icon>
                            <span class="font-semibold text-xs">Case Filing</span>
                        </a>
                        <a href="{{ route('cases.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('cases.index') ? 'sidebar-link-active' : 'text-slate-400' }}">
                            <iconify-icon icon="lucide:briefcase" class="text-xl"></iconify-icon>
                            <span class="font-semibold text-xs">Cases Registry</span>
                        </a>
                        <a href="{{ route('hearings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800">
                            <iconify-icon icon="lucide:calendar-range" class="text-xl"></iconify-icon>
                            <span class="font-semibold text-xs">Hearings Schedule</span>
                        </a>
                    </nav>

                    <div class="p-4 border-t border-slate-850">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white text-xs font-bold rounded-xl transition-all">
                                Log Out System
                            </button>
                        </form>
                    </div>
                </aside>
            </div>

            <!-- Page Workspace Wrapper -->
            <div class="flex flex-col flex-1 min-w-0 overflow-hidden bg-[#020617]">
                <!-- Top Header -->
                <header class="h-20 border-b border-slate-800 px-8 flex items-center justify-between sticky top-0 bg-[#020617]/90 backdrop-blur-md z-40">
                    <!-- Hamburger Mobile Trigger -->
                    <button @click="sidebarOpen = true" class="p-2 -ml-2 text-slate-400 rounded-lg hover:bg-slate-800 md:hidden">
                        <iconify-icon icon="lucide:menu" class="text-2xl"></iconify-icon>
                    </button>

                    <!-- Search Bar -->
                    <div class="hidden md:flex items-center gap-4 w-full max-w-xl">
                        <form method="GET" action="{{ route('cases.index') }}" class="relative w-full">
                            <iconify-icon icon="lucide:search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></iconify-icon>
                            <input type="text" name="search" placeholder="Search case ID, petitioner, or file number..." 
                                   class="w-full bg-slate-900 border border-slate-800 rounded-xl py-2.5 pl-11 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 text-white font-semibold placeholder-slate-550">
                        </form>
                    </div>

                    <!-- Right Elements: Notification Bell, Tracking portal shortcut -->
                    <div class="flex items-center gap-4">
                        <!-- Tracking portal public link -->
                        <a href="{{ route('public.track') }}" target="_blank" title="Launch Citizen Portal"
                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-600 transition-all text-slate-300">
                            <iconify-icon icon="lucide:globe" class="text-xl"></iconify-icon>
                        </a>

                        <!-- Notification Alert Bell -->
                        <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-600 transition-all relative">
                            <iconify-icon icon="lucide:bell" class="text-xl text-slate-350"></iconify-icon>
                            <span class="absolute top-2.5 right-3 w-2 h-2 bg-blue-500 rounded-full border-2 border-slate-900"></span>
                        </button>

                        <!-- Clerk/Admin case filing quick button -->
                        @if(auth()->user()->isClerk() || auth()->user()->isAdmin())
                            <a href="{{ route('cases.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition-all shadow-lg shadow-blue-900/20 active:scale-[0.98]">
                                <iconify-icon icon="lucide:plus" class="text-base"></iconify-icon>
                                <span class="hidden sm:inline">New Filing</span>
                            </a>
                        @endif
                    </div>
                </header>

                <!-- Page Body Content View (Scrollable Workspace) -->
                <main class="flex-1 overflow-y-auto p-8 bg-[#020617]">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
