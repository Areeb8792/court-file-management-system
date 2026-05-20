<x-app-layout>
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 space-y-4 md:space-y-0">
        <div>
            <div class="flex items-center space-x-3">
                <span class="text-xs font-extrabold px-3 py-1 bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 rounded-xl">Case Code: {{ $case->case_number }}</span>
                <span class="text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded-full 
                    {{ $case->priority === 'urgent' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 ring-1 ring-rose-500/20' : 
                       ($case->priority === 'high' ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400') }}">
                    {{ $case->priority }} Priority
                </span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mt-2">{{ $case->title }}</h1>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('cases.index') }}" class="px-4 py-2.5 bg-white hover:bg-slate-50 dark:bg-slate-900 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-bold transition-all">
                Back to Register
            </a>
            @if(auth()->user()->isClerk() || auth()->user()->isAdmin())
                <a href="{{ route('cases.edit', $case->id) }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-500/10 transition-all">
                    Edit Particulars
                </a>
            @endif
        </div>
    </div>

    <!-- 1. Visual Progress Workflow Timeline -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm mb-8">
        <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-450 dark:text-slate-550 mb-6">Workflow Lifecycle Progress</h3>
        
        @php
            $statuses = ['filed', 'under_review', 'hearing_scheduled', 'evidence_submitted', 'judgment_pending', 'closed'];
            $statusLabels = [
                'filed' => 'Case Filed',
                'under_review' => 'Under Review',
                'hearing_scheduled' => 'Hearing Scheduled',
                'evidence_submitted' => 'Evidence Submitted',
                'judgment_pending' => 'Judgment Pending',
                'closed' => 'Closed / Decided'
            ];
            $currentIdx = array_search($case->status, $statuses);
        @endphp

        <!-- Visual Progress Stepper -->
        <div class="relative flex flex-col md:flex-row md:items-center justify-between space-y-6 md:space-y-0 md:space-x-4">
            <!-- Connecting line for desktop -->
            <div class="absolute hidden md:block left-0 right-0 top-1/2 -translate-y-4 h-1 bg-slate-100 dark:bg-slate-800 z-0"></div>
            <!-- Dynamic progress highlight line -->
            @if($currentIdx > 0)
                <div class="absolute hidden md:block left-0 top-1/2 -translate-y-4 h-1 bg-gradient-to-r from-indigo-500 to-indigo-600 z-0" 
                     style="width: {{ ($currentIdx / (count($statuses) - 1)) * 100 }}%"></div>
            @endif

            @foreach($statuses as $index => $status)
                @php
                    $isCompleted = $index < $currentIdx;
                    $isActive = $index === $currentIdx;
                    $isUpcoming = $index > $currentIdx;
                @endphp
                <div class="flex md:flex-col items-center flex-1 relative z-10 space-x-4 md:space-x-0">
                    <!-- Circle -->
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
                    <!-- Label -->
                    <div class="text-left md:text-center mt-0 md:mt-3">
                        <p class="text-xs font-bold leading-tight {{ $isActive ? 'text-indigo-600 dark:text-indigo-400 font-extrabold' : ($isUpcoming ? 'text-slate-400' : 'text-slate-800 dark:text-slate-200') }}">{{ $statusLabels[$status] }}</p>
                        <p class="text-[9px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">{{ $isActive ? 'Active Phase' : ($isUpcoming ? 'Pending' : 'Completed') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Main Workspace columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Case Overview & Admin Actions -->
        <div class="space-y-8">
            <!-- Details Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                <h3 class="text-lg font-bold mb-4">Case Registry Index</h3>
                
                <div class="space-y-4 text-xs font-medium">
                    <!-- Title/Description -->
                    <div>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">Title particulars</p>
                        <p class="text-slate-800 dark:text-slate-200 font-bold mt-1 leading-snug">{{ $case->title }}</p>
                        @if($case->description)
                            <p class="text-slate-500 dark:text-slate-400 mt-1 leading-relaxed text-[11px]">{{ $case->description }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100 dark:border-slate-850">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Litigation Class</p>
                            <p class="text-slate-800 dark:text-slate-200 font-bold mt-0.5 capitalize">{{ $case->category }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">FIR Brief</p>
                            <p class="text-slate-850 dark:text-slate-200 font-bold mt-0.5">{{ $case->fir_number ?: 'Not Applicable' }}</p>
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

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-850 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Presiding Judge</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $case->judge->name ?? 'Unassigned' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Counsel Represented</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $case->lawyer->name ?? 'Unassigned' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Registrar Clerk</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $case->clerk->name ?? 'System' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Court Officers Actions (Clerk / Admin / Judge) -->
            @if(auth()->user()->isClerk() || auth()->user()->isAdmin() || auth()->user()->isJudge())
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold">Judicial Actions</h3>

                    <!-- 1. Assign Staff (Clerk / Admin only) -->
                    @if(auth()->user()->isClerk() || auth()->user()->isAdmin())
                        <div x-data="{ open: false }" class="border-b border-slate-100 dark:border-slate-850 pb-4">
                            <button @click="open = !open" class="flex items-center justify-between w-full text-xs font-bold text-slate-700 dark:text-slate-350 hover:text-indigo-600 transition-colors">
                                <span>Assign Judge / Attorney</span>
                                <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" class="mt-4" style="display: none;">
                                <form method="POST" action="{{ route('cases.assign', $case->id) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Presiding Judge</label>
                                        <select name="judge_id" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 dark:bg-slate-800 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:outline-none">
                                            <option value="">Unassigned</option>
                                            @foreach($judges as $j)
                                                <option value="{{ $j->id }}" {{ $case->judge_id == $j->id ? 'selected' : '' }}>{{ $j->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Counsel retained</label>
                                        <select name="lawyer_id" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 dark:bg-slate-800 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:outline-none">
                                            <option value="">Unassigned</option>
                                            @foreach($lawyers as $l)
                                                <option value="{{ $l->id }}" {{ $case->lawyer_id == $l->id ? 'selected' : '' }}>{{ $l->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-750 text-white text-xs font-bold rounded-xl active:scale-[0.98] transition-all">Update Allocation</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- 2. Transition Status -->
                    <div x-data="{ open: false }" class="border-b border-slate-100 dark:border-slate-850 pb-4">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-xs font-bold text-slate-700 dark:text-slate-350 hover:text-indigo-600 transition-colors">
                            <span>Advance Workflow State</span>
                            <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" class="mt-4" style="display: none;">
                            <form method="POST" action="{{ route('cases.status', $case->id) }}" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Workflow Status</label>
                                    <select name="status" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 dark:bg-slate-800 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:outline-none">
                                        @foreach(['filed' => 'Filed', 'under_review' => 'Under Review', 'hearing_scheduled' => 'Hearing Scheduled', 'evidence_submitted' => 'Evidence Submitted', 'judgment_pending' => 'Judgment Pending', 'closed' => 'Closed'] as $val => $lbl)
                                            <option value="{{ $val }}" {{ $case->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-750 text-white text-xs font-bold rounded-xl active:scale-[0.98] transition-all">Transition State</button>
                            </form>
                        </div>
                    </div>

                    <!-- 3. Write Judgement (Presiding Judge only) -->
                    @if(auth()->user()->isJudge() && $case->judge_id === auth()->id())
                        <div x-data="{ open: false }" class="pb-2">
                            <button @click="open = !open" class="flex items-center justify-between w-full text-xs font-bold text-rose-600 dark:text-rose-450 hover:underline">
                                <span>Pronounce Judgement Ruling</span>
                                <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" class="mt-4" style="display: none;">
                                <form method="POST" action="{{ route('cases.judgment', $case->id) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Final Decree Transcript</label>
                                        <textarea name="judgment" required rows="5" placeholder="Enter final judgment decree, custody particulars, or compensation awards..." 
                                                  class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 dark:bg-slate-800 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:outline-none focus:border-indigo-500"></textarea>
                                    </div>
                                    <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl active:scale-[0.98] transition-all shadow-md shadow-rose-600/10">Archive & Close Case File</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Right side: Document Vault, Hearing docket, activity log tabs -->
        <div class="lg:col-span-2 space-y-8">
            <div x-data="{ tab: 'documents' }" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden flex flex-col">
                <!-- Navigation tabs -->
                <div class="flex border-b border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900">
                    <button @click="tab = 'documents'" :class="tab === 'documents' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 bg-white dark:bg-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-4 text-center border-b-2 text-xs font-bold transition-all">
                        Document Vault
                    </button>
                    <button @click="tab = 'hearings'" :class="tab === 'hearings' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 bg-white dark:bg-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-4 text-center border-b-2 text-xs font-bold transition-all">
                        Hearing Calendar
                    </button>
                    <button @click="tab = 'audit'" :class="tab === 'audit' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 bg-white dark:bg-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-4 text-center border-b-2 text-xs font-bold transition-all">
                        Filing Audit logs
                    </button>
                </div>

                <div class="p-6">
                    <!-- Tab 1: Document Vault -->
                    <div x-show="tab === 'documents'" class="space-y-6">
                        <!-- Uploader component (Accessible by Clerks, Admins, or assigned Lawyers) -->
                        @if(auth()->user()->isClerk() || auth()->user()->isAdmin() || (auth()->user()->isLawyer() && $case->lawyer_id === auth()->id()))
                            <div class="p-4 bg-slate-50 dark:bg-slate-800/30 rounded-2xl border border-slate-100 dark:border-slate-800/30">
                                <h4 class="text-xs font-extrabold uppercase tracking-widest text-slate-450 mb-3">Upload Primary Filings / Exhibits</h4>
                                <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                                    @csrf
                                    <input type="hidden" name="court_case_id" value="{{ $case->id }}">
                                    <div>
                                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Friendly Name</label>
                                        <input type="text" name="name" required placeholder="e.g. Defense Exhibits" 
                                               class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Document Class</label>
                                        <select name="document_type" class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none">
                                            @if(auth()->user()->isClerk() || auth()->user()->isAdmin())
                                                <option value="fir">Police FIR copy</option>
                                                <option value="petition">Lawsuit Petition</option>
                                            @endif
                                            <option value="evidence">Physical Evidence Exhibit</option>
                                            <option value="motion">Advocate Motion Petition</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-1">
                                        <input type="file" name="file" required class="text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-950/40 dark:file:text-indigo-400 hover:file:bg-indigo-100 cursor-pointer">
                                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl active:scale-[0.98]">Upload Secure Document</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Document index table -->
                        <div class="space-y-4">
                            @forelse($case->documents as $doc)
                                <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-100 dark:border-slate-800/30 flex flex-col space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-[9px] font-extrabold tracking-widest uppercase px-2 py-0.5 bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 rounded-lg">{{ $doc->document_type }}</span>
                                                <span class="text-[10px] font-bold text-slate-400">Ver: {{ $doc->version }}</span>
                                            </div>
                                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 mt-2">{{ $doc->name }}</h4>
                                            <p class="text-[10px] text-slate-400 font-bold mt-1">Uploader: {{ $doc->user->name ?? 'System' }} • Size: {{ round($doc->file_size / 1024) }} KB</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- Download -->
                                            <a href="{{ route('documents.download', $doc->id) }}" class="p-2 bg-white hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-all border border-slate-200 dark:border-slate-700" title="Download">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </a>
                                            <!-- Preview -->
                                            <a href="{{ route('documents.preview', $doc->id) }}" target="_blank" class="p-2 bg-white hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-all border border-slate-200 dark:border-slate-700" title="Preview Inline">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 01-6 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Version History & Upload replacement version -->
                                    <div class="pt-3 border-t border-slate-100 dark:border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <!-- Other versions -->
                                        <div class="text-[10px] text-slate-400 font-semibold space-y-1">
                                            @if($doc->versions->count() > 0)
                                                <p class="font-extrabold uppercase tracking-wider">Older Versions:</p>
                                                @foreach($doc->versions as $v)
                                                    <p>
                                                        <a href="{{ route('documents.download', $v->id) }}" class="text-indigo-650 dark:text-indigo-400 hover:underline">Ver {{ $v->version }} ({{ $v->created_at->format('M d, Y') }})</a>
                                                    </p>
                                                @endforeach
                                            @else
                                                <p>Original version. No previous histories.</p>
                                            @endif
                                        </div>

                                        <!-- Upload replacement version -->
                                        @if(auth()->user()->isClerk() || auth()->user()->isAdmin() || (auth()->user()->isLawyer() && $case->lawyer_id === auth()->id()))
                                            <form method="POST" action="{{ route('documents.version', $doc->id) }}" enctype="multipart/form-data" class="flex items-center space-x-2">
                                                @csrf
                                                <input type="file" name="file" required class="text-[9px] w-36 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-slate-200/60 dark:file:bg-slate-700 file:text-[9px] file:font-semibold">
                                                <button type="submit" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-900 text-white dark:bg-slate-750 dark:hover:bg-slate-700 text-[9px] font-bold rounded-lg transition-colors">Replace</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-450 text-center py-6">No evidentiary document logs registered in vault.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab 2: Hearing docket -->
                    <div x-show="tab === 'hearings'" class="space-y-6" style="display: none;">
                        <!-- Scheduler (Clerks & Admins only) -->
                        @if(auth()->user()->isClerk() || auth()->user()->isAdmin())
                            <div class="p-4 bg-slate-50 dark:bg-slate-800/30 rounded-2xl border border-slate-100 dark:border-slate-800/30">
                                <h4 class="text-xs font-extrabold uppercase tracking-widest text-slate-450 mb-3">Schedule New Hearing</h4>
                                <form method="POST" action="{{ route('hearings.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                                    @csrf
                                    <input type="hidden" name="court_case_id" value="{{ $case->id }}">
                                    <div>
                                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Hearing Date & Time</label>
                                        <input type="datetime-local" name="hearing_date" required 
                                               class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Courtroom Allocation</label>
                                        <select name="courtroom" class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none">
                                            <option value="Courtroom A">Courtroom A</option>
                                            <option value="Courtroom B">Courtroom B</option>
                                            <option value="Courtroom 1A">Courtroom 1A</option>
                                            <option value="Courtroom 3B">Courtroom 3B</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Remarks</label>
                                        <input type="text" name="remarks" placeholder="Opening Arguments" 
                                               class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none">
                                    </div>
                                    <div class="sm:col-span-3 flex justify-end">
                                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl active:scale-[0.98]">Schedule Hearing Docket</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Hearing index timeline -->
                        <div class="space-y-4">
                            @forelse($case->hearings as $hearing)
                                <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-100 dark:border-slate-800/30 flex flex-col space-y-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-[9px] font-extrabold uppercase px-2.5 py-0.5 rounded-lg 
                                                    {{ $hearing->status === 'scheduled' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400' : 
                                                       ($hearing->status === 'held' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' : 
                                                       ($hearing->status === 'rescheduled' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-455')) }}">
                                                    {{ $hearing->status }}
                                                </span>
                                                <span class="text-[10px] font-bold text-slate-400">{{ $hearing->courtroom }}</span>
                                            </div>
                                            <h4 class="text-xs font-bold text-slate-850 dark:text-slate-200 mt-2">{{ $hearing->hearing_date->format('F d, Y \a\t g:i A') }}</h4>
                                            @if($hearing->remarks)
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 italic"><span class="font-bold">Remarks:</span> {{ $hearing->remarks }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions (Reschedule form / update remarks for Judge/Clerk/Admin) -->
                                    @if(auth()->user()->isClerk() || auth()->user()->isAdmin() || auth()->user()->isJudge())
                                        <div class="pt-3 border-t border-slate-100 dark:border-slate-850 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <!-- Update remarks form -->
                                            <form method="POST" action="{{ route('hearings.remarks', $hearing->id) }}" class="flex items-center space-x-2">
                                                @csrf
                                                <select name="status" class="text-[10px] font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none">
                                                    <option value="scheduled" {{ $hearing->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                    <option value="held" {{ $hearing->status === 'held' ? 'selected' : '' }}>Held</option>
                                                    <option value="cancelled" {{ $hearing->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                    <option value="rescheduled" {{ $hearing->status === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                                </select>
                                                <input type="text" name="remarks" value="{{ $hearing->remarks }}" placeholder="Docket updates..." 
                                                       class="flex-1 text-[10px] px-2.5 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none">
                                                <button type="submit" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-900 text-white dark:bg-slate-750 text-[9px] font-bold rounded-lg transition-colors">Update</button>
                                            </form>

                                            <!-- Reschedule form (Clerks & Admins only) -->
                                            @if(auth()->user()->isClerk() || auth()->user()->isAdmin())
                                                <form method="POST" action="{{ route('hearings.reschedule', $hearing->id) }}" class="flex items-center space-x-2 justify-end">
                                                    @csrf
                                                    <input type="datetime-local" name="hearing_date" required class="text-[9px] bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none">
                                                    <input type="hidden" name="courtroom" value="{{ $hearing->courtroom }}">
                                                    <input type="hidden" name="remarks" value="Rescheduling docket.">
                                                    <button type="submit" class="px-2.5 py-1 bg-amber-600 hover:bg-amber-700 text-white text-[9px] font-bold rounded-lg transition-colors">Reschedule</button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-450 text-center py-6">No hearing calendar dates scheduled for this case.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab 3: Filing Audit logs -->
                    <div x-show="tab === 'audit'" class="space-y-4" style="display: none;">
                        @forelse($case->activityLogs as $log)
                            <div class="flex items-start space-x-3 text-xs border-b border-slate-50 dark:border-slate-850 pb-3">
                                <span class="mt-1 flex h-1.5 w-1.5 rounded-full bg-indigo-500 flex-shrink-0"></span>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center">
                                        <p class="font-extrabold text-slate-800 dark:text-slate-200">{{ $log->action }}</p>
                                        <span class="text-[9px] font-bold text-slate-400">{{ $log->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">{{ $log->details }}</p>
                                    <p class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest mt-1">Actor: {{ $log->user->name ?? 'System' }} • IP: {{ $log->ip_address }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-450 text-center py-6">No registry audit trails compiled for this file.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
