<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JurisFlow - Authenticated Access Gate</title>
    
    <!-- Tailwind Play CDN Fallback -->
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
        h1, h2, h3, h4 {
            font-family: 'Space Grotesk', sans-serif;
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="antialiased bg-[#020617] text-[#f8fafc] min-h-screen flex items-center justify-center p-4 relative overflow-y-auto">
    <!-- Glowing background elements -->
    <div class="absolute top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] rounded-full bg-blue-600/10 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 translate-x-1/2 translate-y-1/2 w-[400px] h-[400px] rounded-full bg-indigo-650/10 blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-xl z-10 space-y-6">
        <!-- Logo and Brand Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex w-14 h-14 bg-blue-600 rounded-2xl items-center justify-center shadow-xl shadow-blue-900/30">
                <iconify-icon icon="lucide:scale" class="text-3xl text-white"></iconify-icon>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white">JurisFlow e-Portal</h1>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mt-1">Digital Court File & Litigation Workflow System</p>
            </div>
        </div>

        <!-- Standalone Glassmorphic Form Card -->
        <div class="glass-card rounded-3xl p-8 shadow-2xl relative">
            <!-- Toast notification messages -->
            @if(session('status'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-950/80 border border-emerald-800 text-emerald-400 text-xs font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-800 text-rose-350 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-455 mb-2">Registry Email Address</label>
                    <div class="relative">
                        <iconify-icon icon="lucide:mail" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></iconify-icon>
                        <input type="email" name="email" id="email" required autofocus value="{{ old('email') }}" 
                               placeholder="clerk@court.gov" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white pl-11 placeholder-slate-650">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold text-slate-455">Secure Key / Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] text-blue-450 hover:underline font-semibold">Forgot Key?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <iconify-icon icon="lucide:lock" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></iconify-icon>
                        <input type="password" name="password" id="password" required autocomplete="current-password" 
                               placeholder="••••••••" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white pl-11 placeholder-slate-650">
                    </div>
                </div>

                <!-- Remember check -->
                <div class="flex items-center justify-between text-xs">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer text-slate-400 font-semibold select-none">
                        <input id="remember_me" type="checkbox" name="remember" class="mr-2 rounded bg-slate-950 border-slate-800 text-blue-600 focus:ring-blue-600 focus:ring-offset-slate-900">
                        Keep session active
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-750 text-white font-bold rounded-xl text-xs active:scale-[0.98] transition-all shadow-lg shadow-blue-500/10">
                    Authorize & Login
                </button>
            </form>

            <!-- Quick-Login Helper Matrix section -->
            <div class="mt-8 pt-6 border-t border-slate-800/80">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider text-center mb-4">Click to login directly with role</h4>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    <button onclick="quickAuth('admin@court.gov')" class="flex flex-col items-center justify-center p-3 bg-slate-900/50 hover:bg-blue-600/10 border border-slate-800 hover:border-blue-500/30 rounded-xl transition-all group">
                        <iconify-icon icon="lucide:shield" class="text-lg text-blue-400 mb-1 group-hover:scale-105 transition-transform"></iconify-icon>
                        <span class="text-[10px] font-extrabold text-white">System Admin</span>
                        <span class="text-[8px] text-slate-500 font-medium">admin@court.gov</span>
                    </button>

                    <button onclick="quickAuth('clerk@court.gov')" class="flex flex-col items-center justify-center p-3 bg-slate-900/50 hover:bg-emerald-600/10 border border-slate-800 hover:border-emerald-500/30 rounded-xl transition-all group">
                        <iconify-icon icon="lucide:file-plus" class="text-lg text-emerald-400 mb-1 group-hover:scale-105 transition-transform"></iconify-icon>
                        <span class="text-[10px] font-extrabold text-white">Registry Clerk</span>
                        <span class="text-[8px] text-slate-500 font-medium">clerk@court.gov</span>
                    </button>

                    <button onclick="quickAuth('judge@court.gov')" class="flex flex-col items-center justify-center p-3 bg-slate-900/50 hover:bg-amber-600/10 border border-slate-800 hover:border-amber-500/30 rounded-xl transition-all group">
                        <iconify-icon icon="lucide:gavel" class="text-lg text-amber-400 mb-1 group-hover:scale-105 transition-transform"></iconify-icon>
                        <span class="text-[10px] font-extrabold text-white">Bench Judge</span>
                        <span class="text-[8px] text-slate-500 font-medium">judge@court.gov</span>
                    </button>

                    <button onclick="quickAuth('lawyer@court.gov')" class="flex flex-col items-center justify-center p-3 bg-slate-900/50 hover:bg-indigo-600/10 border border-slate-800 hover:border-indigo-500/30 rounded-xl transition-all group">
                        <iconify-icon icon="lucide:briefcase" class="text-lg text-indigo-400 mb-1 group-hover:scale-105 transition-transform"></iconify-icon>
                        <span class="text-[10px] font-extrabold text-white">Attorney</span>
                        <span class="text-[8px] text-slate-500 font-medium">lawyer@court.gov</span>
                    </button>

                    <button onclick="quickAuth('public@court.gov')" class="col-span-2 sm:col-span-1 flex flex-col items-center justify-center p-3 bg-slate-900/50 hover:bg-pink-600/10 border border-slate-800 hover:border-pink-500/30 rounded-xl transition-all group">
                        <iconify-icon icon="lucide:user" class="text-lg text-pink-400 mb-1 group-hover:scale-105 transition-transform"></iconify-icon>
                        <span class="text-[10px] font-extrabold text-white">Citizen</span>
                        <span class="text-[8px] text-slate-500 font-medium">public@court.gov</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Anonymous Citizen Tracking shortcut Link -->
        <div class="text-center">
            <a href="{{ route('public.track') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-white font-semibold transition-colors">
                <iconify-icon icon="lucide:arrow-right-circle" class="text-sm"></iconify-icon>
                Want anonymous track search? Open Public Chamber
            </a>
        </div>
    </div>

    <!-- Quick login utility Javascript -->
    <script>
        function quickAuth(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
            document.getElementById('login-form').submit();
        }
    </script>
</body>
</html>
