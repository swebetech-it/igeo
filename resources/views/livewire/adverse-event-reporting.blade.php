@section('title', 'Report an Adverse Event | Pharmacovigilance')
@section('meta_description', 'Report a suspected side effect or adverse event electronically to iGEO Pharmacy & Consulting Drug Safety (info@igeo.co.tz).')

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 pb-20">
    <!-- Top Banner (iGEO Styled Reference Header) -->
    <div class="bg-background-dark text-white border-b border-primary/20 shadow-md">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 hover:bg-primary hover:text-background-dark transition-colors" title="Back to Home">
                <span class="material-symbols-outlined text-xl">home</span>
            </a>
            <div class="text-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-primary">iGEO Drug Safety &amp; Pharmacovigilance</p>
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Report an Adverse Event</h1>
            </div>
            <button type="button"
                    wire:click="$toggle('showEmailInstructions')"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-semibold transition-colors"
                    title="View Email Reporting Instructions">
                <span class="material-symbols-outlined text-base text-primary">mail</span>
                <span class="hidden sm:inline">Email Option</span>
            </button>
        </div>
    </div>

    @php
        $mailtoBody = rawurlencode(
            "Drug Safety / Adverse Event Report\n" .
            "----------------------------------\n" .
            "1. Name of the suspected product:\n" .
            "   - Treatment start date:\n" .
            "   - Dosing strength & frequency (e.g., 40mg twice daily):\n" .
            "   - Action taken (e.g., reduced dose, discontinued) & impact on side effect:\n\n" .
            "2. Side effect or adverse event:\n" .
            "   - Description & symptom evolution over time:\n" .
            "   - Treatment needed for the side effect:\n" .
            "   - Healthcare provider name & contact details:\n\n" .
            "3. Patient details:\n" .
            "   - Gender & Age (optional):\n" .
            "   - Ongoing medical conditions:\n" .
            "   - Other medications currently taken (name, dose, frequency, start date):\n\n" .
            "4. Your contact details (optional):\n" .
            "   - Name:\n" .
            "   - Phone / Email:\n" .
            "   - Do you consent to follow-up contact? (Yes / Do not contact):\n"
        );
        $mailtoHref = "mailto:info@igeo.co.tz?subject=" . rawurlencode('Adverse Event / Side Effect Report - iGEO Drug Safety') . "&body=" . $mailtoBody;
    @endphp

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-6">
        <!-- Electronic / Email Reporting Information Card -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-emerald-200/80 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
            <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-emerald-50/70 dark:bg-emerald-950/20">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-emerald-700 dark:text-primary mt-0.5">verified_user</span>
                    <div class="text-sm text-slate-700 dark:text-slate-300">
                        To report a side effect or adverse event electronically, complete the online form below or email our local Drug Safety Responsible Person at
                        <a href="{{ $mailtoHref }}" class="font-bold text-emerald-800 dark:text-primary underline hover:opacity-80">info@igeo.co.tz</a>.
                    </div>
                </div>
                <button type="button"
                        wire:click="$toggle('showEmailInstructions')"
                        class="shrink-0 inline-flex items-center gap-1 text-xs font-bold text-emerald-800 dark:text-primary hover:underline self-start sm:self-center">
                    <span>{{ $showEmailInstructions ? 'Hide e-mail checklist' : 'View e-mail format checklist' }}</span>
                    <span class="material-symbols-outlined text-base">{{ $showEmailInstructions ? 'expand_less' : 'expand_more' }}</span>
                </button>
            </div>

            @if($showEmailInstructions)
                <div class="p-5 sm:p-6 border-t border-emerald-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-300 space-y-4">
                    <p class="font-semibold text-slate-900 dark:text-white">
                        When submitting in an e-mail format, please provide the following information in your email to ensure it is handled in a manner consistent with applicable local laws:
                    </p>
                    <ul class="space-y-3 list-disc pl-5">
                        <li>
                            <span class="font-bold text-slate-900 dark:text-white">Name of the suspected product:</span>
                            <p class="text-slate-600 dark:text-slate-400 mt-0.5">
                                Description of when the patient started the treatment, including dosing strength and frequency (amount and how often, e.g. 40mg twice daily). Any action taken with the product (e.g. reduced dose, discontinued) and impact of that action on the side effect or adverse event.
                            </p>
                        </li>
                        <li>
                            <span class="font-bold text-slate-900 dark:text-white">The side effect or adverse event associated with the product:</span>
                            <p class="text-slate-600 dark:text-slate-400 mt-0.5">
                                Symptom evolution over time. Description of any treatment needed for the side effect or adverse event. Name of healthcare provider who provided treatment and contact information.
                            </p>
                        </li>
                        <li>
                            <span class="font-bold text-slate-900 dark:text-white">Your name (optional):</span>
                            <p class="text-slate-600 dark:text-slate-400 mt-0.5">
                                Please note that your personal identifying information (e.g. name, email address) will not be shared with the health authorities. iGEO Pharmacy &amp; Consulting or its authorized safety partners may contact you for further information about the side effect or adverse event. If you do not wish to be contacted, you should indicate this in your email.
                            </p>
                        </li>
                        <li>
                            <span class="font-bold text-slate-900 dark:text-white">Information about the person who experienced the side effect or adverse event:</span>
                            <p class="text-slate-600 dark:text-slate-400 mt-0.5">
                                Any other medical conditions currently ongoing. Medications currently taken (name, dosing strength, frequency, and when medication was started). Gender and age of the person taking medication (optional).
                            </p>
                        </li>
                    </ul>
                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <a href="{{ $mailtoHref }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-background-dark text-white dark:bg-primary dark:text-background-dark text-xs font-bold hover:opacity-90 transition">
                            <span class="material-symbols-outlined text-base">outgoing_mail</span>
                            Compose Pre-formatted Email to info@igeo.co.tz
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if($submitted)
            <!-- Submission Confirmation View -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-800 p-8 sm:p-12 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-primary mb-5">
                    <span class="material-symbols-outlined text-4xl">task_alt</span>
                </div>
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-700 dark:text-primary">Pharmacovigilance Receipt</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-2">
                    Thank You — Your Report Has Been Recorded
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-xl mx-auto mt-3 text-sm leading-relaxed">
                    Your adverse event report has been securely saved to iGEO Pharmacy &amp; Consulting's Drug Safety system for review by our Responsible Person.
                </p>

                <div class="mt-6 inline-block bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-6 py-4">
                    <span class="block text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Reference Number</span>
                    <span class="text-xl sm:text-2xl font-black tracking-wider text-background-dark dark:text-primary font-mono">{{ $submittedReference }}</span>
                </div>

                <p class="mt-6 text-xs text-slate-500 dark:text-slate-400 max-w-lg mx-auto">
                    Please keep this reference number if you wish to send additional medical information to
                    <a href="mailto:info@igeo.co.tz" class="font-bold underline text-slate-700 dark:text-slate-200">info@igeo.co.tz</a>.
                    If this is a medical emergency, please contact a healthcare professional or emergency services immediately.
                </p>

                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <button type="button" wire:click="downloadPdf" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-slate-300 dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition shadow-sm">
                        <span class="material-symbols-outlined text-lg text-emerald-600">download</span>
                        Download PDF
                    </button>
                    <button type="button" wire:click="startNewReport" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-background-dark text-white dark:bg-primary dark:text-background-dark text-sm font-bold hover:opacity-90 transition">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        Submit Another Report
                    </button>
                </div>
            </div>
        @else
            <!-- 5-Step Progress Stepper (Styled after Reference Wizard) -->
            @php
                $steps = [
                    1 => 'Product',
                    2 => 'Side Effect',
                    3 => 'Patient Details',
                    4 => 'Contact Details',
                    5 => 'Summary',
                ];
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 px-4 sm:px-8 py-6 mb-6">
                <div class="relative flex items-center justify-between">
                    <!-- Connecting Track -->
                    <div class="absolute left-6 right-6 top-4 h-0.5 bg-slate-200 dark:bg-slate-700 -z-0"></div>
                    <div class="absolute left-6 top-4 h-0.5 bg-emerald-600 transition-all duration-300 -z-0"
                         style="width: calc(({{ $currentStep - 1 }} / 4) * (100% - 3rem));"></div>

                    @foreach($steps as $num => $label)
                        @php
                            $isActive = $currentStep === $num;
                            $isCompleted = $currentStep > $num;
                            $isClickable = $num <= $maxStepReached;
                        @endphp
                        <button type="button"
                                wire:click="goToStep({{ $num }})"
                                @disabled(!$isClickable)
                                class="relative z-10 flex flex-col items-center group focus:outline-none {{ $isClickable ? 'cursor-pointer' : 'cursor-not-allowed' }}">
                            <span class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold transition-all shadow-sm
                                @if($isActive)
                                    bg-background-dark text-primary ring-4 ring-emerald-500/20
                                @elseif($isCompleted)
                                    bg-emerald-600 text-white
                                @else
                                    bg-slate-500 text-white dark:bg-slate-700 dark:text-slate-300
                                @endif">
                                @if($isActive)
                                    <span class="material-symbols-outlined text-base">edit</span>
                                @elseif($isCompleted)
                                    <span class="material-symbols-outlined text-base">check</span>
                                @else
                                    {{ $num }}
                                @endif
                            </span>
                            <span class="mt-2 text-[11px] sm:text-xs font-semibold text-center
                                {{ $isActive ? 'text-slate-900 dark:text-white font-extrabold' : 'text-slate-600 dark:text-slate-400' }}">
                                {{ $label }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Validation Error Summary Banner -->
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 p-4 text-sm text-red-800 dark:text-red-200">
                    <div class="flex items-start gap-2.5">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400 shrink-0">error</span>
                        <div>
                            <p class="font-bold">Please review the highlighted fields before continuing:</p>
                            <ul class="mt-1 list-disc pl-5 space-y-0.5 text-xs">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ========================================================= -->
            <!-- STEP 1: PRODUCT                                           -->
            <!-- ========================================================= -->
            @if($currentStep === 1)
                <datalist id="igeo-product-suggestions">
                    @foreach($productSuggestions as $suggestion)
                        <option value="{{ $suggestion }}"></option>
                    @endforeach
                </datalist>

                <div class="space-y-6">
                    @foreach($products as $index => $prod)
                        <div wire:key="ae-product-{{ $index }}" class="bg-white dark:bg-slate-900 rounded-xl shadow-md border border-slate-200 dark:border-slate-800 overflow-hidden">
                            <!-- Collapsible Dark Header Bar (Matches Reference Style) -->
                            <div class="bg-background-dark text-white px-5 sm:px-6 py-4 flex items-center justify-between cursor-pointer select-none"
                                 wire:click="toggleProduct({{ $index }})">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="font-extrabold text-sm sm:text-base tracking-wide uppercase truncate">
                                        {{ !empty(trim($prod['product_name'] ?? '')) ? strtoupper($prod['product_name']) : 'PRODUCT #' . ($index + 1) . ' — ENTER PRODUCT NAME' }}
                                    </span>
                                    @if(!empty($prod['relationship']))
                                        <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold
                                            {{ $prod['relationship'] === 'Suspected' ? 'bg-primary text-background-dark' : 'bg-white/15 text-white' }}">
                                            {{ $prod['relationship'] }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(count($products) > 1)
                                        <button type="button"
                                                wire:click.stop="removeProduct({{ $index }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-500/20 text-red-200 hover:bg-red-500 hover:text-white transition"
                                                title="Remove this product">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    @endif
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full border border-white/60 text-white">
                                        <span class="material-symbols-outlined text-base">
                                            {{ !empty($prod['expanded']) ? 'expand_less' : 'expand_more' }}
                                        </span>
                                    </span>
                                </div>
                            </div>

                            @if(!empty($prod['expanded']))
                                <div class="p-5 sm:p-8 space-y-8">
                                    <!-- Subsection: About this product -->
                                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5 sm:p-6 space-y-6">
                                        <h3 class="text-base font-bold text-emerald-700 dark:text-primary">About this product</h3>

                                        <!-- Product Name -->
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                What is the name of the product you want to report?
                                                <span class="text-red-700 dark:text-red-400 font-bold">* Required</span>
                                                <span class="inline-flex items-center align-middle ml-1 text-slate-500 cursor-help" title="Enter the brand name or generic active ingredient name printed on the package.">
                                                    <span class="material-symbols-outlined text-base">info</span>
                                                </span>
                                            </label>
                                            <input type="text"
                                                   list="igeo-product-suggestions"
                                                   maxlength="70"
                                                   wire:model.live="products.{{ $index }}.product_name"
                                                   placeholder="e.g., BACLOFEN INTRATHECAL or AMOXICILLIN 500MG"
                                                   class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-emerald-600 focus:ring-emerald-600 text-sm py-3 px-4 uppercase placeholder:normal-case">
                                            <div class="mt-1.5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                                <span>Maximum length of the 'Product name' field is 70 characters.</span>
                                                <span class="font-semibold tabular-nums">{{ mb_strlen($prod['product_name'] ?? '') }} / 70</span>
                                            </div>
                                            @error("products.$index.product_name")
                                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/60 px-4 py-2.5 text-xs font-medium text-amber-900 dark:text-amber-200">
                                            Please ensure that at least one product is checked as <strong>“Suspected”</strong>.
                                        </div>

                                        <!-- Relationship to Side Effect -->
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-3">
                                                How is this product related to the side effect?
                                                <span class="text-red-700 dark:text-red-400 font-bold">* Required</span>
                                            </label>
                                            <div class="space-y-3">
                                                @php
                                                    $relationships = [
                                                        'Suspected' => 'The product suspected of causing the side effect or adverse event.',
                                                        'Concomitant product' => 'Another medication or supplement the patient was taking around the same time.',
                                                        'Used for treatment of the side effect' => 'A medication given specifically to treat the reported side effect.',
                                                    ];
                                                @endphp
                                                @foreach($relationships as $relValue => $relHelp)
                                                    <label class="flex items-center gap-3 cursor-pointer group">
                                                        <input type="radio"
                                                               name="product_relationship_{{ $index }}"
                                                               value="{{ $relValue }}"
                                                               wire:model.live="products.{{ $index }}.relationship"
                                                               class="w-4 h-4 text-background-dark focus:ring-emerald-600 border-slate-400">
                                                        <span class="text-sm text-slate-800 dark:text-slate-200 group-hover:text-slate-950 dark:group-hover:text-white">
                                                            {{ $relValue }}
                                                        </span>
                                                        <span class="text-slate-400 hover:text-slate-600 cursor-help" title="{{ $relHelp }}">
                                                            <span class="material-symbols-outlined text-base align-middle">info</span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @error('suspected_product')
                                                <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Subsection: When and why the product was taken -->
                                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5 sm:p-6 space-y-6">
                                        <h3 class="text-base font-bold text-emerald-700 dark:text-primary">When and why the product was taken</h3>

                                        <div>
                                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mb-3">
                                                When did the patient start taking the product?
                                            </p>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <!-- Start Date -->
                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Start Date</label>
                                                    <div class="grid grid-cols-3 gap-2">
                                                        <select wire:model="products.{{ $index }}.start_day" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                                            <option value="">Day</option>
                                                            @for($d = 1; $d <= 31; $d++)
                                                                <option value="{{ $d }}">{{ $d }}</option>
                                                            @endfor
                                                        </select>
                                                        <select wire:model="products.{{ $index }}.start_month" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                                            <option value="">Month</option>
                                                            @foreach(\App\Livewire\AdverseEventReporting::MONTHS as $m)
                                                                <option value="{{ $m }}">{{ $m }}</option>
                                                            @endforeach
                                                        </select>
                                                        <select wire:model="products.{{ $index }}.start_year" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                                            <option value="">Year</option>
                                                            @for($y = (int) date('Y'); $y >= 1980; $y--)
                                                                <option value="{{ $y }}">{{ $y }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- End Date -->
                                                <div>
                                                    <div class="flex items-center justify-between mb-2">
                                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">End Date</label>
                                                        <label class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 cursor-pointer">
                                                            <input type="checkbox" wire:model.live="products.{{ $index }}.is_ongoing" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-600">
                                                            <span>Still ongoing</span>
                                                        </label>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-2 {{ !empty($prod['is_ongoing']) ? 'opacity-40 pointer-events-none' : '' }}">
                                                        <select wire:model="products.{{ $index }}.end_day" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                                            <option value="">Day</option>
                                                            @for($d = 1; $d <= 31; $d++)
                                                                <option value="{{ $d }}">{{ $d }}</option>
                                                            @endfor
                                                        </select>
                                                        <select wire:model="products.{{ $index }}.end_month" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                                            <option value="">Month</option>
                                                            @foreach(\App\Livewire\AdverseEventReporting::MONTHS as $m)
                                                                <option value="{{ $m }}">{{ $m }}</option>
                                                            @endforeach
                                                        </select>
                                                        <select wire:model="products.{{ $index }}.end_year" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                                            <option value="">Year</option>
                                                            @for($y = (int) date('Y') + 1; $y >= 1980; $y--)
                                                                <option value="{{ $y }}">{{ $y }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Indication -->
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                What was the indication?
                                                <span class="text-slate-400 cursor-help ml-1" title="The medical reason or condition for which this medication was prescribed or taken.">
                                                    <span class="material-symbols-outlined text-base align-middle">info</span>
                                                </span>
                                            </label>
                                            <input type="text"
                                                   maxlength="250"
                                                   wire:model.live="products.{{ $index }}.indication"
                                                   placeholder="e.g., Severe spasticity, Hypertension, Bacterial infection"
                                                   class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-emerald-600 focus:ring-emerald-600 text-sm py-2.5 px-4">
                                            <div class="mt-1.5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                                <span>Maximum length of the 'Indication' field is 250 characters.</span>
                                                <span class="font-semibold tabular-nums">{{ mb_strlen($prod['indication'] ?? '') }} / 250</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subsection: How the product was taken -->
                                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5 sm:p-6 space-y-6">
                                        <h3 class="text-base font-bold text-emerald-700 dark:text-primary">How the product was taken</h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                    Which formulation of the product were they taking?
                                                </label>
                                                <select wire:model="products.{{ $index }}.formulation"
                                                        class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3 focus:border-emerald-600 focus:ring-emerald-600">
                                                    <option value="">Please select</option>
                                                    @foreach(\App\Livewire\AdverseEventReporting::FORMULATIONS as $formulation)
                                                        <option value="{{ $formulation }}">{{ $formulation }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                    What was the route of administration?
                                                </label>
                                                <select wire:model="products.{{ $index }}.route"
                                                        class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3 focus:border-emerald-600 focus:ring-emerald-600">
                                                    <option value="">Please select</option>
                                                    @foreach(\App\Livewire\AdverseEventReporting::ROUTES as $routeOpt)
                                                        <option value="{{ $routeOpt }}">{{ $routeOpt }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Dose & Unit -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                    What dose were they taking?
                                                </label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <input type="text"
                                                               maxlength="8"
                                                               wire:model.live="products.{{ $index }}.dose"
                                                               placeholder="Dose (e.g. 40)"
                                                               class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3 focus:border-emerald-600 focus:ring-emerald-600">
                                                    </div>
                                                    <div>
                                                        <select wire:model="products.{{ $index }}.dose_unit"
                                                                class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3 focus:border-emerald-600 focus:ring-emerald-600">
                                                            <option value="">Please select</option>
                                                            @foreach(\App\Livewire\AdverseEventReporting::DOSE_UNITS as $unit)
                                                                <option value="{{ $unit }}">{{ $unit }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mt-1.5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                                    <span>Maximum length of the 'Dose' field is 8 characters.</span>
                                                    <span class="font-semibold tabular-nums">{{ mb_strlen($prod['dose'] ?? '') }} / 8</span>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                    What was the frequency?
                                                </label>
                                                <select wire:model="products.{{ $index }}.frequency"
                                                        class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3 focus:border-emerald-600 focus:ring-emerald-600">
                                                    <option value="">Please select</option>
                                                    @foreach(\App\Livewire\AdverseEventReporting::FREQUENCIES as $freq)
                                                        <option value="{{ $freq }}">{{ $freq }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Other Dosage -->
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                Other Dosage <span class="font-normal text-slate-500">[e.g., 24/26 mg, cycle #3, titration schedule, other]</span>
                                            </label>
                                            <input type="text"
                                                   maxlength="50"
                                                   wire:model.live="products.{{ $index }}.other_dosage"
                                                   placeholder="Optional complex or split dosing regimen"
                                                   class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-4 focus:border-emerald-600 focus:ring-emerald-600">
                                            <div class="mt-1.5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                                <span>Maximum length of the 'Other Dosage' field is 50 characters.</span>
                                                <span class="font-semibold tabular-nums">{{ mb_strlen($prod['other_dosage'] ?? '') }} / 50</span>
                                            </div>
                                        </div>

                                        <!-- Action Taken & Impact -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                    What action was taken as a result of the side effect?
                                                </label>
                                                <select wire:model="products.{{ $index }}.action_taken"
                                                        class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3 focus:border-emerald-600 focus:ring-emerald-600">
                                                    <option value="">Please select</option>
                                                    @foreach(\App\Livewire\AdverseEventReporting::ACTIONS_TAKEN as $act)
                                                        <option value="{{ $act }}">{{ $act }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                    Impact of that action on the side effect
                                                </label>
                                                <select wire:model="products.{{ $index }}.action_impact"
                                                        class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3 focus:border-emerald-600 focus:ring-emerald-600">
                                                    <option value="">Please select</option>
                                                    @foreach(\App\Livewire\AdverseEventReporting::ACTION_IMPACTS as $impact)
                                                        <option value="{{ $impact }}">{{ $impact }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subsection: Additional information -->
                                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5 sm:p-6 space-y-4">
                                        <h3 class="text-base font-bold text-emerald-700 dark:text-primary">Additional information</h3>

                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                                Lot or Batch number:
                                                <span class="text-slate-400 cursor-help ml-1" title="Usually printed on the carton, bottle label, or blister foil next to 'LOT' or 'Batch'.">
                                                    <span class="material-symbols-outlined text-base align-middle">info</span>
                                                </span>
                                            </label>
                                            <input type="text"
                                                   maxlength="35"
                                                   wire:model.live="products.{{ $index }}.lot_batch_number"
                                                   placeholder="e.g., B49201A"
                                                   class="w-full sm:max-w-md rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-4 focus:border-emerald-600 focus:ring-emerald-600">
                                            <div class="mt-1.5 flex items-center justify-between sm:max-w-md text-xs text-slate-500 dark:text-slate-400">
                                                <span>Maximum length of the 'Lot or Batch number' field is 35 characters.</span>
                                                <span class="font-semibold tabular-nums">{{ mb_strlen($prod['lot_batch_number'] ?? '') }} / 35</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Add Concomitant / Additional Product Button -->
                    <div>
                        <button type="button"
                                wire:click="addProduct"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-emerald-600 dark:hover:border-primary text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-primary transition">
                            <span class="material-symbols-outlined text-lg">add_circle</span>
                            Add Another Product (Concomitant or Treatment Medication)
                        </button>
                    </div>
                </div>
            @endif

            <!-- ========================================================= -->
            <!-- STEP 2: SIDE EFFECT                                       -->
            <!-- ========================================================= -->
            @if($currentStep === 2)
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-md border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="bg-background-dark text-white px-6 py-4">
                        <h2 class="font-extrabold text-base uppercase tracking-wide">Side Effect / Adverse Event Details</h2>
                    </div>

                    <div class="p-5 sm:p-8 space-y-8">
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5 sm:p-6 space-y-6">
                            <h3 class="text-base font-bold text-emerald-700 dark:text-primary">Description &amp; Symptom Evolution</h3>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Describe the side effect or adverse event associated with the product
                                    <span class="text-red-700 dark:text-red-400 font-bold">* Required</span>
                                </label>
                                <textarea rows="4"
                                          maxlength="2000"
                                          wire:model.live="reaction_description"
                                          placeholder="Describe all symptoms experienced (e.g., skin rash, dizziness, nausea, muscle weakness)..."
                                          class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm p-3.5 focus:border-emerald-600 focus:ring-emerald-600"></textarea>
                                <div class="mt-1 flex justify-between text-xs text-slate-500">
                                    <span>Include any relevant test results if available.</span>
                                    <span class="font-semibold tabular-nums">{{ mb_strlen($reaction_description) }} / 2000</span>
                                </div>
                                @error('reaction_description')
                                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Symptom evolution over time
                                </label>
                                <textarea rows="3"
                                          maxlength="2000"
                                          wire:model="symptom_evolution"
                                          placeholder="How did the symptoms start and change over hours, days, or weeks?"
                                          class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm p-3.5 focus:border-emerald-600 focus:ring-emerald-600"></textarea>
                            </div>

                            <!-- Onset & Resolution Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">When did the side effect start?</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <select wire:model="onset_day" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                                            <option value="">Day</option>
                                            @for($d = 1; $d <= 31; $d++)
                                                <option value="{{ $d }}">{{ $d }}</option>
                                            @endfor
                                        </select>
                                        <select wire:model="onset_month" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                                            <option value="">Month</option>
                                            @foreach(\App\Livewire\AdverseEventReporting::MONTHS as $m)
                                                <option value="{{ $m }}">{{ $m }}</option>
                                            @endforeach
                                        </select>
                                        <select wire:model="onset_year" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                                            <option value="">Year</option>
                                            @for($y = (int) date('Y'); $y >= 1980; $y--)
                                                <option value="{{ $y }}">{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">When did it stop? (if resolved)</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <select wire:model="resolution_day" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                                            <option value="">Day</option>
                                            @for($d = 1; $d <= 31; $d++)
                                                <option value="{{ $d }}">{{ $d }}</option>
                                            @endfor
                                        </select>
                                        <select wire:model="resolution_month" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                                            <option value="">Month</option>
                                            @foreach(\App\Livewire\AdverseEventReporting::MONTHS as $m)
                                                <option value="{{ $m }}">{{ $m }}</option>
                                            @endforeach
                                        </select>
                                        <select wire:model="resolution_year" class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                                            <option value="">Year</option>
                                            @for($y = (int) date('Y'); $y >= 1980; $y--)
                                                <option value="{{ $y }}">{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Outcome & Seriousness -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                        Current outcome of the side effect
                                        <span class="text-red-700 dark:text-red-400 font-bold">* Required</span>
                                    </label>
                                    <select wire:model="outcome" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3">
                                        <option value="">Please select</option>
                                        @foreach(\App\Livewire\AdverseEventReporting::OUTCOMES as $outcomeOpt)
                                            <option value="{{ $outcomeOpt }}">{{ $outcomeOpt }}</option>
                                        @endforeach
                                    </select>
                                    @error('outcome')
                                        <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                        Did the event result in any of the following? <span class="font-normal text-slate-500">(Optional)</span>
                                    </label>
                                    <div class="space-y-2">
                                        @foreach(\App\Livewire\AdverseEventReporting::SERIOUSNESS_OPTIONS as $crit)
                                            <label class="flex items-center gap-2.5 text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                                                <input type="checkbox" value="{{ $crit }}" wire:model="seriousness_criteria" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-600">
                                                <span>{{ $crit }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Treatment & Healthcare Provider -->
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5 sm:p-6 space-y-6">
                            <h3 class="text-base font-bold text-emerald-700 dark:text-primary">Treatment &amp; Healthcare Provider</h3>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Was any medical treatment needed for the side effect or adverse event?
                                </label>
                                <div class="flex flex-wrap gap-6">
                                    @foreach(['No', 'Yes', 'Unknown'] as $yn)
                                        <label class="inline-flex items-center gap-2 text-sm text-slate-800 dark:text-slate-200 cursor-pointer">
                                            <input type="radio" name="treatment_needed" value="{{ $yn }}" wire:model.live="treatment_needed" class="text-background-dark focus:ring-emerald-600">
                                            <span>{{ $yn }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            @if($treatment_needed === 'Yes')
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                        Description of treatment provided for the side effect
                                    </label>
                                    <textarea rows="2"
                                              wire:model="treatment_description"
                                              placeholder="Describe medications, procedures, or supportive care given..."
                                              class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm p-3"></textarea>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                        Name of healthcare provider / clinic who provided treatment
                                    </label>
                                    <input type="text"
                                           wire:model="hcp_name"
                                           placeholder="e.g., Dr. A. Mwinyi / Muhimbili National Hospital"
                                           class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-4">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                        Healthcare provider contact information
                                    </label>
                                    <input type="text"
                                           wire:model="hcp_contact"
                                           placeholder="Phone number, email, or facility address"
                                           class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ========================================================= -->
            <!-- STEP 3: PATIENT DETAILS                                   -->
            <!-- ========================================================= -->
            @if($currentStep === 3)
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-md border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="bg-background-dark text-white px-6 py-4">
                        <h2 class="font-extrabold text-base uppercase tracking-wide">Information About the Person Who Experienced the Side Effect</h2>
                    </div>

                    <div class="p-5 sm:p-8 space-y-6">
                        <div class="rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 p-4 text-xs text-slate-600 dark:text-slate-300">
                            Patient demographic fields are optional. Do not enter the patient's full name if reporting on someone else's behalf—initials are sufficient for privacy protection.
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Patient Initials <span class="font-normal text-slate-500">(Optional)</span>
                                </label>
                                <input type="text"
                                       maxlength="15"
                                       wire:model="patient_initials"
                                       placeholder="e.g., J.M."
                                       class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-4">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Gender <span class="font-normal text-slate-500">(Optional)</span>
                                </label>
                                <select wire:model="patient_gender" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3">
                                    <option value="">Prefer not to say / Unknown</option>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Age <span class="font-normal text-slate-500">(Optional)</span>
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number"
                                           min="0"
                                           max="130"
                                           wire:model="patient_age"
                                           placeholder="Age"
                                           class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3">
                                    <select wire:model="patient_age_unit" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-2">
                                        <option value="Years">Years</option>
                                        <option value="Months">Months</option>
                                        <option value="Weeks">Weeks</option>
                                        <option value="Days">Days</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                Any other medical conditions currently ongoing
                            </label>
                            <textarea rows="3"
                                      wire:model="ongoing_medical_conditions"
                                      placeholder="e.g., Diabetes, hypertension, asthma, allergies, kidney/liver conditions, pregnancy..."
                                      class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm p-3.5"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                Medications currently taken <span class="font-normal text-slate-500">(if not already entered in Step 1)</span>
                            </label>
                            <textarea rows="3"
                                      wire:model="concomitant_medications_notes"
                                      placeholder="Include medication name, dosing strength, frequency, and when each medication was started..."
                                      class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm p-3.5"></textarea>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ========================================================= -->
            <!-- STEP 4: CONTACT DETAILS                                   -->
            <!-- ========================================================= -->
            @if($currentStep === 4)
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-md border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="bg-background-dark text-white px-6 py-4">
                        <h2 class="font-extrabold text-base uppercase tracking-wide">Reporter Contact Details &amp; Privacy Notice</h2>
                    </div>

                    <div class="p-5 sm:p-8 space-y-6">
                        <div class="rounded-xl bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 p-4 text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                            <p class="font-bold text-slate-900 dark:text-white mb-1">Confidentiality &amp; Follow-Up Notice</p>
                            Please note that your personal identifying information (e.g. name, email address, phone number) will not be shared with health authorities.
                            <strong>iGEO Pharmacy &amp; Consulting</strong> or its authorized Drug Safety agents may contact you for further information about the side effect or adverse event.
                            If you do not wish to be contacted, please select <em>“No, I do not wish to be contacted”</em> below.
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    I am reporting as a
                                    <span class="text-red-700 dark:text-red-400 font-bold">* Required</span>
                                </label>
                                <select wire:model="reporter_type" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-3">
                                    <option value="Patient / Consumer">Patient / Consumer</option>
                                    <option value="Doctor / Physician">Doctor / Physician</option>
                                    <option value="Pharmacist">Pharmacist</option>
                                    <option value="Nurse / Healthcare Professional">Nurse / Other Healthcare Professional</option>
                                    <option value="Caregiver / Relative">Parent / Caregiver / Relative</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Your Name <span class="font-normal text-slate-500">(Optional)</span>
                                </label>
                                <input type="text"
                                       wire:model="reporter_name"
                                       placeholder="Optional full name"
                                       class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-4">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Email Address <span class="font-normal text-slate-500">(Optional)</span>
                                </label>
                                <input type="email"
                                       wire:model="reporter_email"
                                       placeholder="you@example.com"
                                       class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-4">
                                @error('reporter_email')
                                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Phone / Mobile Number <span class="font-normal text-slate-500">(Optional)</span>
                                </label>
                                <input type="text"
                                       wire:model="reporter_phone"
                                       placeholder="+255 7XX XXX XXX"
                                       class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-4">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
                                    Location (Country / City)
                                </label>
                                <input type="text"
                                       wire:model="reporter_location"
                                       placeholder="e.g., Dar es Salaam, Tanzania"
                                       class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm py-2.5 px-4">
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-200 dark:border-slate-800">
                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-3">
                                Follow-up contact preference <span class="text-red-700 dark:text-red-400 font-bold">* Required</span>
                            </label>
                            <div class="space-y-2.5">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="consent_to_contact" value="yes" wire:model.live="consent_to_contact" class="w-4 h-4 text-background-dark focus:ring-emerald-600">
                                    <span class="text-sm text-slate-800 dark:text-slate-200">Yes — iGEO's Drug Safety Responsible Person may contact me if follow-up clinical details are needed.</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="consent_to_contact" value="no" wire:model.live="consent_to_contact" class="w-4 h-4 text-background-dark focus:ring-emerald-600">
                                    <span class="text-sm text-slate-800 dark:text-slate-200">No — I do not wish to be contacted regarding this report.</span>
                                </label>
                            </div>
                            @error('reporter_contact')
                                <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

            <!-- ========================================================= -->
            <!-- STEP 5: SUMMARY & SUBMIT                                  -->
            <!-- ========================================================= -->
            @if($currentStep === 5)
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-md border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="bg-background-dark text-white px-6 py-4">
                        <h2 class="font-extrabold text-base uppercase tracking-wide">Review Summary Before Submitting</h2>
                    </div>

                    <div class="p-5 sm:p-8 space-y-6 text-sm">
                        <!-- Summary Section 1: Products -->
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-bold text-base text-slate-900 dark:text-white">1. Product(s)</h3>
                                <button type="button" wire:click="goToStep(1)" class="text-xs font-bold text-emerald-700 dark:text-primary hover:underline">Edit</button>
                            </div>
                            <div class="space-y-3">
                                @foreach($products as $idx => $p)
                                    <div class="bg-slate-50 dark:bg-slate-800/70 rounded-lg p-3.5 border border-slate-100 dark:border-slate-700">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <span class="font-extrabold text-slate-900 dark:text-white uppercase">{{ $p['product_name'] ?: 'Unnamed Product' }}</span>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ ($p['relationship'] ?? '') === 'Suspected' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                                {{ $p['relationship'] ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 text-xs text-slate-600 dark:text-slate-300">
                                            <div><strong>Indication:</strong> {{ $p['indication'] ?: 'Not specified' }}</div>
                                            <div><strong>Formulation / Route:</strong> {{ $p['formulation'] ?: '-' }} / {{ $p['route'] ?: '-' }}</div>
                                            <div><strong>Dose &amp; Frequency:</strong> {{ trim(($p['dose'] ?? '') . ' ' . ($p['dose_unit'] ?? '')) ?: '-' }} {{ !empty($p['other_dosage']) ? '(' . $p['other_dosage'] . ')' : '' }} &bull; {{ $p['frequency'] ?: '-' }}</div>
                                            <div><strong>Action Taken:</strong> {{ $p['action_taken'] ?: '-' }}</div>
                                            <div><strong>Lot / Batch:</strong> {{ $p['lot_batch_number'] ?: '-' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Summary Section 2: Side Effect -->
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-bold text-base text-slate-900 dark:text-white">2. Side Effect / Adverse Event</h3>
                                <button type="button" wire:click="goToStep(2)" class="text-xs font-bold text-emerald-700 dark:text-primary hover:underline">Edit</button>
                            </div>
                            <p class="text-slate-800 dark:text-slate-200 whitespace-pre-line">{{ $reaction_description }}</p>
                            @if($symptom_evolution)
                                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400"><strong>Evolution:</strong> {{ $symptom_evolution }}</p>
                            @endif
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600 dark:text-slate-300">
                                <div><strong>Outcome:</strong> {{ $outcome ?: '-' }}</div>
                                <div><strong>Treatment Needed:</strong> {{ $treatment_needed }}</div>
                                @if(!empty($seriousness_criteria))
                                    <div class="sm:col-span-2 text-red-700 dark:text-red-400"><strong>Seriousness Criteria:</strong> {{ implode(', ', $seriousness_criteria) }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Summary Section 3 & 4 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-bold text-base text-slate-900 dark:text-white">3. Patient Details</h3>
                                    <button type="button" wire:click="goToStep(3)" class="text-xs font-bold text-emerald-700 dark:text-primary hover:underline">Edit</button>
                                </div>
                                <div class="space-y-1 text-xs text-slate-600 dark:text-slate-300">
                                    <div><strong>Initials:</strong> {{ $patient_initials ?: 'Not provided' }}</div>
                                    <div><strong>Gender:</strong> {{ $patient_gender ?: 'Not provided' }}</div>
                                    <div><strong>Age:</strong> {{ $patient_age !== '' ? $patient_age . ' ' . $patient_age_unit : 'Not provided' }}</div>
                                    <div><strong>Ongoing Conditions:</strong> {{ $ongoing_medical_conditions ?: 'None listed' }}</div>
                                </div>
                            </div>

                            <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-bold text-base text-slate-900 dark:text-white">4. Contact Details</h3>
                                    <button type="button" wire:click="goToStep(4)" class="text-xs font-bold text-emerald-700 dark:text-primary hover:underline">Edit</button>
                                </div>
                                <div class="space-y-1 text-xs text-slate-600 dark:text-slate-300">
                                    <div><strong>Role:</strong> {{ $reporter_type }}</div>
                                    <div><strong>Name:</strong> {{ $reporter_name ?: 'Anonymous' }}</div>
                                    <div><strong>Email / Phone:</strong> {{ $reporter_email ?: '-' }} / {{ $reporter_phone ?: '-' }}</div>
                                    <div><strong>Follow-up Permitted:</strong> {{ $consent_to_contact === 'yes' ? 'Yes' : 'No (Do not contact)' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Good Faith Confirmation -->
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="confirm_good_faith" class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600">
                                <span class="text-xs sm:text-sm text-slate-700 dark:text-slate-200">
                                    I confirm that the information in this adverse event report is submitted in good faith for Drug Safety monitoring by <strong>iGEO Pharmacy &amp; Consulting</strong> in accordance with applicable pharmacovigilance regulations.
                                </span>
                            </label>
                            @error('confirm_good_faith')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

            <!-- Wizard Action Footer (Matches Floating / Pill Save & Continue Style) -->
            <div class="mt-8 flex items-center justify-between gap-4">
                <div>
                    @if($currentStep > 1)
                        <button type="button"
                                wire:click="previousStep"
                                class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>
                            Previous Step
                        </button>
                    @endif
                </div>

                <div class="ml-auto">
                    @if($currentStep < 5)
                        <button type="button"
                                wire:click="saveAndContinue"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-background-dark text-white hover:bg-slate-900 dark:bg-primary dark:text-background-dark text-sm font-extrabold shadow-lg hover:shadow-xl transition">
                            <span class="material-symbols-outlined text-lg text-primary dark:text-background-dark">check_circle</span>
                            <span wire:loading.remove wire:target="saveAndContinue">Save &amp; Continue</span>
                            <span wire:loading wire:target="saveAndContinue">Validating...</span>
                        </button>
                    @else
                        <button type="button"
                                wire:click="submitReport"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-primary text-background-dark hover:brightness-95 text-sm font-black shadow-lg hover:shadow-xl transition">
                            <span class="material-symbols-outlined text-lg">send</span>
                            <span wire:loading.remove wire:target="submitReport">Submit Adverse Event Report</span>
                            <span wire:loading wire:target="submitReport">Submitting Report...</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
