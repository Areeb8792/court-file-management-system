<x-app-layout>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-white">Filing New Case Register</h1>
        <p class="text-slate-400 font-medium mt-1">Lodge a new litigation file, record litigants, and allocate courtroom personnel.</p>
    </div>

    <!-- Filing Form Container -->
    <div class="max-w-3xl glass-card rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 h-36 w-36 rounded-full bg-blue-500/5 blur-3xl"></div>
        
        <form method="POST" action="{{ route('cases.store') }}" class="space-y-6">
            @csrf

            <!-- Section 1: Lawsuit Particulars -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-400">1. Lawsuit Particulars</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Case Title -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-450 mb-2">Lawsuit Title / Suit Name</label>
                        <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. State vs. Walter White (Aggravated Burglary)" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white placeholder-slate-600">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-bold text-slate-455 mb-2">Category classification</label>
                        <select name="category" required class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-slate-300">
                            <option value="Criminal" {{ old('category') === 'Criminal' ? 'selected' : '' }}>Criminal Case</option>
                            <option value="Civil" {{ old('category') === 'Civil' ? 'selected' : '' }}>Civil Suit</option>
                            <option value="Constitutional" {{ old('category') === 'Constitutional' ? 'selected' : '' }}>Constitutional Writ</option>
                            <option value="Family" {{ old('category') === 'Family' ? 'selected' : '' }}>Family Petition</option>
                        </select>
                    </div>

                    <!-- FIR Number -->
                    <div>
                        <label class="block text-xs font-bold text-slate-455 mb-2">FIR/Police Report Code (Optional)</label>
                        <input type="text" name="fir_number" value="{{ old('fir_number') }}" placeholder="e.g. FIR-2026-987" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white placeholder-slate-600">
                    </div>
                </div>
            </div>

            <!-- Section 2: Litigants & Priorities -->
            <div class="pt-6 border-t border-slate-800/60 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-400">2. Parties & Litigant Hierarchy</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Petitioner -->
                    <div>
                        <label class="block text-xs font-bold text-slate-455 mb-2">Petitioner / Plaintiff (Prosecutor)</label>
                        <input type="text" name="petitioner" required value="{{ old('petitioner') }}" placeholder="Petitioner Full Name" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white placeholder-slate-600">
                    </div>

                    <!-- Respondent -->
                    <div>
                        <label class="block text-xs font-bold text-slate-455 mb-2">Respondent / Defendant</label>
                        <input type="text" name="respondent" required value="{{ old('respondent') }}" placeholder="Respondent Full Name" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white placeholder-slate-600">
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-xs font-bold text-slate-455 mb-2">Filing Priority Listing</label>
                        <select name="priority" required class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-slate-300">
                            <option value="low">Low Priority</option>
                            <option value="medium" selected>Medium Priority</option>
                            <option value="high">High Priority</option>
                            <option value="urgent">Urgent Priority (Fast Track)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 3: Court Personnel Allocation -->
            <div class="pt-6 border-t border-slate-800/60 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-400">3. Court Officer Allocations</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Presiding Judge -->
                    <div>
                        <label class="block text-xs font-bold text-slate-455 mb-2">Presiding Judge (Bench)</label>
                        <select name="judge_id" class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-slate-300">
                            <option value="">Select Presiding Judge (Or leave unassigned)</option>
                            @foreach($judges as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lawyer -->
                    <div>
                        <label class="block text-xs font-bold text-slate-455 mb-2">Defense/Representing Advocate</label>
                        <select name="lawyer_id" class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-slate-300">
                            <option value="">Select Attorney (Or leave unassigned)</option>
                            @foreach($lawyers as $l)
                                <option value="{{ $l->id }}">{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="pt-6 border-t border-slate-800/60">
                <label class="block text-xs font-bold text-slate-455 mb-2">Brief Lawsuit Statement / Description</label>
                <textarea name="description" rows="4" placeholder="Draft brief lawsuit summary, core complaints, compensations sought or police charges detail..." 
                          class="w-full text-xs font-semibold px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all text-white placeholder-slate-600">{{ old('description') }}</textarea>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-800/60">
                <a href="{{ route('cases.index') }}" class="px-6 py-3 bg-slate-900 border border-slate-800 hover:border-slate-600 text-slate-300 font-bold rounded-xl text-xs transition-colors">Cancel Filing</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-750 text-white font-bold rounded-xl text-xs active:scale-[0.98] transition-all shadow-lg shadow-blue-500/10">Lodge Case File</button>
            </div>
        </form>
    </div>
</x-app-layout>
