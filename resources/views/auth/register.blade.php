<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JurisFlow - Register Secure Account</title>
    
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

    <div class="w-full max-w-xl z-10 space-y-6 py-8">
        <!-- Logo and Brand Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex w-14 h-14 bg-blue-600 rounded-2xl items-center justify-center shadow-xl shadow-blue-900/30">
                <iconify-icon icon="lucide:user-plus" class="text-3xl text-white"></iconify-icon>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Create JurisFlow Account</h1>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mt-1">Register a new litigation & court tracking profile</p>
            </div>
        </div>

        <!-- Standalone Glassmorphic Form Card -->
        <div class="glass-card rounded-3xl p-8 shadow-2xl relative">
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-800 text-rose-350 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Full Name Input -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-455 mb-2">Full Legal Name</label>
                    <div class="relative">
                        <iconify-icon icon="lucide:user" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></iconify-icon>
                        <input type="text" name="name" id="name" required autofocus value="{{ old('name') }}" 
                               placeholder="e.g. Walter White / Adv. Saul Goodman" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white pl-11 placeholder-slate-650">
                    </div>
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-455 mb-2">Registry Email Address</label>
                    <div class="relative">
                        <iconify-icon icon="lucide:mail" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></iconify-icon>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}" 
                               placeholder="walter.white@example.com" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white pl-11 placeholder-slate-650">
                    </div>
                </div>

                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-xs font-bold text-slate-455 mb-2">Select Your Role Profile</label>
                    <div class="relative">
                        <iconify-icon icon="lucide:briefcase" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></iconify-icon>
                        <select name="role" id="role" required 
                                class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-slate-300 pl-11">
                            <option value="public" {{ old('role') === 'public' ? 'selected' : '' }}>Citizen Observer / Public Client</option>
                            <option value="lawyer" {{ old('role') === 'lawyer' ? 'selected' : '' }}>Advocate / Representing Counsel</option>
                        </select>
                    </div>
                    <span class="block text-[10px] text-slate-500 font-medium mt-1.5">Note: System Admin, Clerk, and Judge profiles are allocated directly by court staff.</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-455 mb-2">Secure Password</label>
                        <div class="relative">
                            <iconify-icon icon="lucide:lock" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></iconify-icon>
                            <input type="password" name="password" id="password" required autocomplete="new-password" 
                                   placeholder="••••••••" 
                                   class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white pl-11 placeholder-slate-650">
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-455 mb-2">Confirm Password</label>
                        <div class="relative">
                            <iconify-icon icon="lucide:shield-check" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></iconify-icon>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" 
                                   placeholder="••••••••" 
                                   class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white pl-11 placeholder-slate-650">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full mt-4 py-3 bg-blue-600 hover:bg-blue-750 text-white font-bold rounded-xl text-xs active:scale-[0.98] transition-all shadow-lg shadow-blue-500/10">
                    Register Secure Profile
                </button>
            </form>
        </div>

        <!-- Back to login channel link -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-white font-semibold transition-colors">
                <iconify-icon icon="lucide:arrow-left-circle" class="text-sm"></iconify-icon>
                Already have an active account? Authorize here
            </a>
        </div>
    </div>
</body>
</html>
