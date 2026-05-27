<x-app-layout>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight">Edit Case Particulars</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Modify active registry lawsuit files and particulars for: <span class="text-indigo-600 dark:text-indigo-400 font-extrabold">{{ $case->case_number }}</span></p>
    </div>

    <!-- Filing Form Container -->
    <div class="max-w-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
        <form method="POST" action="{{ route('cases.update', $case->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Lawsuit Particulars -->
            <div class="space-y-4">
                <h3 class="text-sm font-extrabold uppercase tracking-widest text-indigo-650 dark:text-indigo-400">1. Lawsuit Particulars</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Case Title -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-450 mb-2">Lawsuit Title / Suit Name</label>
                        <input type="text" name="title" required value="{{ old('title', $case->title) }}" placeholder="e.g. State vs. Walter White" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all text-slate-800 dark:text-slate-100">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-450 mb-2">Category classification</label>
                        <select name="category" required class="w-full text-xs font-semibold px-4 py-3 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all text-slate-750 dark:text-slate-300">
                            <option value="Criminal" {{ old('category', $case->category) === 'Criminal' ? 'selected' : '' }}>Criminal Case</option>
                            <option value="Civil" {{ old('category', $case->category) === 'Civil' ? 'selected' : '' }}>Civil Suit</option>
                            <option value="Constitutional" {{ old('category', $case->category) === 'Constitutional' ? 'selected' : '' }}>Constitutional Writ</option>
                            <option value="Family" {{ old('category', $case->category) === 'Family' ? 'selected' : '' }}>Family Petition</option>
                        </select>
                    </div>

                    <!-- FIR Number -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-450 mb-2">FIR/Police Report Code (Optional)</label>
                        <input type="text" name="fir_number" value="{{ old('fir_number', $case->fir_number) }}" placeholder="e.g. FIR-2026-987" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all text-slate-800 dark:text-slate-100">
                    </div>
                </div>
            </div>

            <!-- Section 2: Litigants & Priorities -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-850 space-y-4">
                <h3 class="text-sm font-extrabold uppercase tracking-widest text-indigo-650 dark:text-indigo-400">2. Parties & Litigant Hierarchy</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Petitioner -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-450 mb-2">Petitioner / Plaintiff (Prosecutor)</label>
                        <input type="text" name="petitioner" required value="{{ old('petitioner', $case->petitioner) }}" placeholder="Petitioner Full Name" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all text-slate-800 dark:text-slate-100">
                    </div>

                    <!-- Respondent -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-450 mb-2">Respondent / Defendant</label>
                        <input type="text" name="respondent" required value="{{ old('respondent', $case->respondent) }}" placeholder="Respondent Full Name" 
                               class="w-full text-xs font-semibold px-4 py-3 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all text-slate-800 dark:text-slate-100">
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-450 mb-2">Filing Priority Listing</label>
                        <select name="priority" required class="w-full text-xs font-semibold px-4 py-3 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all text-slate-750 dark:text-slate-300">
                            <option value="low" {{ old('priority', $case->priority) === 'low' ? 'selected' : '' }}>Low Priority</option>
                            <option value="medium" {{ old('priority', $case->priority) === 'medium' ? 'selected' : '' }}>Medium Priority</option>
                            <option value="high" {{ old('priority', $case->priority) === 'high' ? 'selected' : '' }}>High Priority</option>
                            <option value="urgent" {{ old('priority', $case->priority) === 'urgent' ? 'selected' : '' }}>Urgent Priority (Fast Track)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-850">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-450 mb-2">Brief Lawsuit Statement / Description</label>
                <textarea name="description" rows="4" placeholder="Draft brief lawsuit summary..." 
                          class="w-full text-xs font-semibold px-4 py-3 bg-slate-50 focus:bg-white dark:bg-slate-850 dark:focus:bg-slate-800 border border-slate-200 focus:border-indigo-500 dark:border-slate-800 dark:focus:border-indigo-500 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all text-slate-800 dark:text-slate-100">{{ old('description', $case->description) }}</textarea>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100 dark:border-slate-850">
                <a href="{{ route('cases.show', $case->id) }}" class="px-6 py-3 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 text-slate-700 dark:text-slate-300 font-bold rounded-2xl text-xs transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-xs shadow-lg shadow-indigo-500/10 active:scale-[0.98] transition-all">Update Case File</button>
            </div>
        </form>
    </div>
</x-app-layout>
