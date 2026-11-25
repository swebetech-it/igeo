@extends('layouts.web')

@section('title', 'Regulatory Affairs & Pharmaceutical Compliance Services')

@section('content')
<!-- Executive Positioning Hero -->
<section class="relative bg-[#0b1d33] text-white py-20 lg:py-28 px-6 lg:px-16 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="max-w-4xl">
            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-400/10 border border-emerald-400/30 px-4 py-1.5 mb-6">
                <span class="material-symbols-outlined text-emerald-400 text-base">verified_user</span>
                <span class="text-xs font-bold uppercase tracking-[0.16em] text-emerald-300">Tanzania Pharmaceutical Regulatory Partner</span>
            </div>
            <h1 class="font-heading text-3xl sm:text-5xl lg:text-[54px] font-extrabold tracking-tight text-white leading-[1.12] mb-6">
                {{ $positioning['headline'] }}
            </h1>
            <p class="text-lg sm:text-xl text-slate-200 leading-relaxed mb-5">
                {{ $positioning['lead'] }}
            </p>
            <p class="text-base text-slate-300/90 leading-relaxed mb-8">
                {{ $positioning['opportunity'] }}
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="#service-lines" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-[#0b1d33] font-bold px-7 py-4 text-sm sm:text-base shadow-lg transition-all">
                    <span>Select a Service Line</span>
                    <span class="material-symbols-outlined text-[20px]">expand_more</span>
                </a>
                <a href="#consultation" class="inline-flex items-center gap-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold px-7 py-4 text-sm sm:text-base transition-all">
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                    <span>Request Consultation</span>
                </a>
            </div>
        </div>

        <!-- Regulatory Authorities Strip -->
        <div class="mt-14 pt-10 border-t border-white/10">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400 mb-4">
                Institutional &amp; Regulatory Frameworks Navigated
            </p>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($positioning['authorities'] as $auth)
                    <div class="rounded-xl bg-white/5 border border-white/10 px-4 py-3 hover:border-emerald-400/40 transition-colors">
                        <div class="font-heading text-sm font-extrabold text-emerald-400">{{ $auth['code'] }}</div>
                        <div class="text-[11.5px] text-slate-300 leading-snug mt-0.5">{{ $auth['name'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Interactive Drop-Line Service Selector & Details Preview -->
<section id="service-lines" x-data="{ activeSlug: '{{ $regulatoryServices[0]['slug'] }}' }" class="py-20 px-6 lg:px-16 bg-slate-50 dark:bg-background-dark">
    <div class="max-w-7xl mx-auto">
        <div class="max-w-3xl mb-12">
            <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700 dark:text-primary">Eight Core Regulatory Service Lines</span>
            <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-2 mb-4">
                Select Any Service Line to Inspect Scope &amp; Deliverables
            </h2>
            <p class="text-slate-600 dark:text-gray-300 text-base leading-relaxed">
                {{ $positioning['focus'] }}
            </p>
        </div>

        <!-- Clean Drop-Line Accordion List -->
        <div class="space-y-4">
            @foreach($regulatoryServices as $service)
                @php
                    $isOpenVar = "activeSlug === '{$service['slug']}'";
                @endphp
                <div class="rounded-2xl border transition-all duration-200 bg-white dark:bg-slate-900 overflow-hidden shadow-sm"
                     :class="{{ $isOpenVar }} ? 'border-emerald-600 ring-1 ring-emerald-600/20 shadow-md' : 'border-slate-200/90 dark:border-gray-800 hover:border-slate-300'">
                    <!-- Drop-Line Header Row -->
                    <button type="button"
                            @click="activeSlug = ({{ $isOpenVar }} ? '' : '{{ $service['slug'] }}')"
                            class="w-full flex items-center justify-between gap-4 px-6 sm:px-8 py-5 text-left transition-colors hover:bg-slate-50/70 dark:hover:bg-white/5">
                        <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                            <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl font-heading text-sm font-extrabold transition-colors"
                                  :class="{{ $isOpenVar }} ? 'bg-[#0b1d33] text-emerald-400' : 'bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-200'">
                                {{ $service['number'] }}
                            </span>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 dark:text-primary">
                                        {{ $service['category_badge'] }}
                                    </span>
                                    @foreach($service['authorities'] as $authority)
                                        <span class="inline-flex items-center rounded-md bg-slate-100 dark:bg-gray-800 px-2 py-0.5 text-[10.5px] font-semibold text-slate-600 dark:text-gray-300">
                                            {{ $authority }}
                                        </span>
                                    @endforeach
                                </div>
                                <h3 class="font-heading text-lg sm:text-xl font-bold text-slate-900 dark:text-white leading-snug">
                                    {{ $service['name'] }}
                                </h3>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-slate-500 transition-transform duration-200 shrink-0"
                              :class="{{ $isOpenVar }} ? 'rotate-180 text-emerald-700' : ''">
                            expand_more
                        </span>
                    </button>

                    <!-- Expanded Drop-Line Content -->
                    <div x-show="{{ $isOpenVar }}" x-cloak class="border-t border-slate-100 dark:border-gray-800 px-6 sm:px-8 py-7 bg-slate-50/40 dark:bg-slate-900/60">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            <div class="lg:col-span-8">
                                <p class="text-slate-700 dark:text-gray-200 text-[15.5px] leading-relaxed mb-6">
                                    {{ $service['description'] }}
                                </p>
                                <h4 class="font-heading text-xs font-extrabold uppercase tracking-[0.15em] text-slate-500 dark:text-gray-400 mb-4">
                                    Services Offered ({{ count($service['features']) }} Deliverables)
                                </h4>
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($service['features'] as $feature)
                                        <li class="flex items-start gap-2.5 text-[14.5px] text-slate-700 dark:text-gray-200 leading-relaxed">
                                            <span class="material-symbols-outlined text-emerald-600 text-[18px] mt-0.5 shrink-0">check_circle</span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Action & Client Scope Card -->
                            <div class="lg:col-span-4">
                                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/90 dark:border-gray-700 p-6 shadow-sm space-y-5">
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Designed For</p>
                                        <p class="mt-1.5 text-sm text-slate-700 dark:text-gray-200 leading-relaxed">
                                            {{ $service['target_clients'] }}
                                        </p>
                                    </div>
                                    <div class="rounded-xl bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-200/70 dark:border-emerald-800/40 p-3.5 text-xs text-emerald-900 dark:text-emerald-200 leading-relaxed">
                                        {{ $service['highlight_note'] }}
                                    </div>
                                    <div class="flex flex-col gap-2.5 pt-1">
                                        <a href="{{ route('public.service.detail', ['slug' => $service['slug']]) }}"
                                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0b1d33] hover:bg-emerald-700 text-white font-bold px-5 py-3.5 text-sm transition-colors shadow-sm">
                                            <span>Open Full Service Details</span>
                                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                        </a>
                                        <a href="{{ route('public.service.detail', ['slug' => $service['slug']]) }}#consultation"
                                           class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-emerald-600 text-emerald-800 dark:text-primary hover:bg-emerald-600 hover:text-white font-bold px-5 py-3 text-sm transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                                            <span>REQUEST CONSULTATION</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Case Study Section -->
<section class="py-20 px-6 lg:px-16 bg-white dark:bg-slate-950 border-t border-slate-200/80 dark:border-gray-800">
    <div class="max-w-7xl mx-auto">
        <div class="rounded-3xl bg-[#0b1d33] text-white p-8 sm:p-12 lg:p-14 shadow-xl relative overflow-hidden">
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7 space-y-5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/15 border border-emerald-400/30 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-300">
                        <span class="material-symbols-outlined text-sm">clinical_notes</span>
                        {{ $caseStudy['badge'] }}
                    </span>
                    <h2 class="font-heading text-2xl sm:text-3xl font-extrabold leading-snug">
                        {{ $caseStudy['title'] }}
                    </h2>
                    <p class="text-slate-200 text-base leading-relaxed">
                        {{ $caseStudy['narrative'] }}
                    </p>
                    <div class="flex flex-wrap gap-2 pt-1">
                        @foreach($caseStudy['products'] as $product)
                            <span class="rounded-lg bg-white/10 border border-white/15 px-3.5 py-1.5 text-xs font-semibold text-emerald-200">
                                {{ $product }}
                            </span>
                        @endforeach
                    </div>
                </div>
                <div class="lg:col-span-5 space-y-3.5">
                    @foreach($caseStudy['pillars'] as $pillar)
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                            <h3 class="font-heading text-sm font-bold text-emerald-400 mb-1">{{ $pillar['title'] }}</h3>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">{{ $pillar['detail'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Direct Request Consultation Form -->
<section id="consultation" class="py-20 px-6 lg:px-16 bg-slate-50 dark:bg-background-dark border-t border-slate-200/80 dark:border-gray-800">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-gray-800 p-8 sm:p-12 shadow-lg">
            <div class="mb-8">
                <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700 dark:text-primary">Regulatory Advisory Desk</span>
                <h2 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-2">
                    Request a Regulatory Consultation
                </h2>
                <p class="text-slate-600 dark:text-gray-300 text-sm sm:text-base mt-2 leading-relaxed">
                    Select the regulatory service line you need and share brief details about your products, facility or market-entry timeline in Tanzania.
                </p>
            </div>

            @if(session('consultation_success'))
                <div class="mb-8 rounded-2xl bg-emerald-50 border border-emerald-300 p-5 flex items-start gap-3.5 text-emerald-900">
                    <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0">task_alt</span>
                    <div class="text-sm font-medium leading-relaxed">
                        {{ session('consultation_success') }}
                    </div>
                </div>
            @endif

            <form action="{{ route('consultation.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label for="service_name" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Regulatory Service Line *</label>
                        <select id="service_name" name="service_name" required class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600">
                            @foreach($regulatoryServices as $optionService)
                                <option value="{{ $optionService['name'] }}" {{ old('service_name', request('service')) === $optionService['name'] ? 'selected' : '' }}>
                                    {{ $optionService['number'] }}. {{ $optionService['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Your Full Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Dr. / Mr. / Ms. Full Name" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600"/>
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="company" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Company / Organization / MAH</label>
                        <input type="text" id="company" name="company" value="{{ old('company') }}" placeholder="Manufacturer, Importer, Hospital or CRO" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600"/>
                        @error('company') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Business Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="name@company.com" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600"/>
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Phone / WhatsApp Number</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+255 ... or International" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600"/>
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="product_category" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Product / Operational Category</label>
                        <select id="product_category" name="product_category" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600">
                            <option value="Pharmaceuticals / Prescription Medicines">Pharmaceuticals / Prescription &amp; OTC Medicines</option>
                            <option value="Biologicals / Biosimilars / Vaccines">Biologicals / Biosimilars / Vaccines</option>
                            <option value="Medical Devices & IVDs">Medical Devices &amp; In-Vitro Diagnostics (IVDs)</option>
                            <option value="Controlled / Critical-Care Medicines">Controlled / Neonatal / Critical-Care Medicines</option>
                            <option value="Warehouse / GSDP / GMP Facility">Warehouse, Wholesale Premises or Manufacturing Facility</option>
                            <option value="Clinical Trial / Research Protocol">Clinical Trial / Research Protocol</option>
                            <option value="Multi-Product Market Entry">Multi-Product Market Entry &amp; Feasibility</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="message" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Regulatory Scope &amp; Inquiry Details *</label>
                        <textarea id="message" name="message" rows="4" required placeholder="Briefly describe your product(s), target regulatory pathway, current dossier/licensing status or timeline..." class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600">{{ old('message') }}</textarea>
                        @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                    <p class="text-xs text-slate-500 dark:text-gray-400">
                        All regulatory inquiries and product portfolios are handled under strict professional confidentiality.
                    </p>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0b1d33] hover:bg-emerald-700 text-white font-bold px-8 py-4 text-sm tracking-wide shadow-md transition-all shrink-0">
                        <span class="material-symbols-outlined text-[19px]">send</span>
                        <span>REQUEST CONSULTATION</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
