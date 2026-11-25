@extends('layouts.web')

@section('title', $service['name'])

@section('content')
<div x-data="{ consultationModalOpen: false }">
    <!-- Service Hero Banner -->
    <section class="relative bg-[#0b1d33] text-white py-16 lg:py-22 px-6 lg:px-16 overflow-hidden">
        <div class="absolute inset-0 opacity-10 data-grid-line"></div>
        <div class="absolute -top-20 right-0 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Breadcrumb -->
            <nav class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-slate-300/80 mb-6">
                <a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('public.services') }}" class="hover:text-emerald-400 transition-colors">Services</a>
                <span>/</span>
                <span class="text-emerald-300 font-semibold">{{ $service['short_name'] }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
                <div class="lg:col-span-8">
                    <div class="flex flex-wrap items-center gap-2.5 mb-4">
                        <span class="inline-flex items-center justify-center rounded-lg bg-emerald-500 text-[#0b1d33] px-3 py-1 font-heading text-xs font-extrabold uppercase tracking-wider">
                            Service Line {{ $service['number'] }}
                        </span>
                        <span class="inline-flex items-center rounded-lg bg-white/10 border border-white/15 px-3 py-1 text-xs font-semibold text-emerald-300">
                            {{ $service['category_badge'] }}
                        </span>
                        @foreach($service['authorities'] as $authority)
                            <span class="inline-flex items-center rounded-lg bg-white/5 border border-white/10 px-2.5 py-1 text-xs font-medium text-slate-200">
                                {{ $authority }}
                            </span>
                        @endforeach
                    </div>

                    <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-[1.15] mb-5">
                        {{ $service['name'] }}
                    </h1>

                    <p class="text-base sm:text-lg text-slate-200 leading-relaxed max-w-3xl">
                        {{ $service['description'] }}
                    </p>
                </div>

                <!-- Hero Primary Action: REQUEST CONSULTATION -->
                <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3 lg:items-end">
                    <a href="#consultation"
                       @click.prevent="document.getElementById('consultation').scrollIntoView({ behavior: 'smooth' })"
                       class="inline-flex w-full lg:w-auto items-center justify-center gap-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-[#0b1d33] font-extrabold px-7 py-4 text-sm sm:text-base tracking-wide shadow-lg transition-all">
                        <span class="material-symbols-outlined text-[20px]">event_available</span>
                        <span>REQUEST CONSULTATION</span>
                    </a>
                    @if(!empty($service['secondary_cta']))
                        <a href="{{ route($service['secondary_cta']['route']) }}"
                           class="inline-flex w-full lg:w-auto items-center justify-center gap-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold px-6 py-3.5 text-sm transition-all">
                            <span class="material-symbols-outlined text-[18px]">health_and_safety</span>
                            <span>{{ $service['secondary_cta']['label'] }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Main Service Details + Drop-Line Switcher Sidebar -->
    <section class="py-16 lg:py-20 px-6 lg:px-16 bg-slate-50 dark:bg-background-dark">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left / Center: Detailed Scope & Deliverables -->
            <div class="lg:col-span-8 space-y-8">
                @if(session('consultation_success'))
                    <div class="rounded-2xl bg-emerald-50 border border-emerald-300 p-5 flex items-start gap-3.5 text-emerald-900 shadow-sm">
                        <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0">task_alt</span>
                        <div class="text-sm sm:text-base font-medium leading-relaxed">
                            {{ session('consultation_success') }}
                        </div>
                    </div>
                @endif

                <!-- Target Clients & Key Regulatory Value -->
                <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-gray-800 p-6 sm:p-8 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="font-heading text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700 dark:text-primary mb-2">
                                Who We Support
                            </h2>
                            <p class="text-[15px] text-slate-700 dark:text-gray-200 leading-relaxed">
                                {{ $service['target_clients'] }}
                            </p>
                        </div>
                        <div class="rounded-xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200/70 dark:border-emerald-800/40 p-4">
                            <h3 class="font-heading text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-800 dark:text-emerald-300 mb-1.5">
                                Operational Advantage
                            </h3>
                            <p class="text-sm text-emerald-950 dark:text-emerald-100 leading-relaxed">
                                {{ $service['highlight_note'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Complete Scope of Services Offered -->
                <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-gray-800 p-6 sm:p-10 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-4 pb-6 mb-6 border-b border-slate-100 dark:border-gray-800">
                        <div>
                            <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700 dark:text-primary">Detailed Scope</span>
                            <h2 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                                Services Offered
                            </h2>
                        </div>
                        <span class="rounded-full bg-slate-100 dark:bg-gray-800 px-3.5 py-1.5 text-xs font-bold text-slate-700 dark:text-gray-300">
                            {{ count($service['features']) }} Core Deliverables
                        </span>
                    </div>

                    <ul class="space-y-4">
                        @foreach($service['features'] as $idx => $feature)
                            <li class="flex items-start gap-4 rounded-xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-100 dark:border-gray-800 p-4 hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
                                <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#0b1d33] text-emerald-400 text-xs font-extrabold mt-0.5">
                                    {{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-[15.5px] font-medium text-slate-800 dark:text-gray-100 leading-relaxed">
                                    {{ $feature }}
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Mid-page Consultation Prompt -->
                    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="font-heading text-base font-bold text-slate-900 dark:text-white">
                                Ready to engage iGEO for {{ $service['short_name'] }}?
                            </p>
                            <p class="text-sm text-slate-600 dark:text-gray-400">
                                Speak directly with our regulatory specialists for a tailored timeline and dossier/compliance checklist.
                            </p>
                        </div>
                        <a href="#consultation"
                           @click.prevent="document.getElementById('consultation').scrollIntoView({ behavior: 'smooth' })"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0b1d33] hover:bg-emerald-700 text-white font-bold px-6 py-3.5 text-sm tracking-wide transition-colors shrink-0 shadow-sm">
                            <span>REQUEST CONSULTATION</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_downward</span>
                        </a>
                    </div>
                </div>

                <!-- Case Study Highlight (shown on Market Access Advisory & Product Registration) -->
                @if(!empty($service['includes_case_study']) || $service['number'] === '01')
                    <div class="rounded-2xl bg-[#0b1d33] text-white p-7 sm:p-9 shadow-lg">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/15 border border-emerald-400/30 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-300 mb-4">
                            <span class="material-symbols-outlined text-sm">clinical_notes</span>
                            {{ $caseStudy['badge'] }}
                        </span>
                        <h3 class="font-heading text-xl sm:text-2xl font-extrabold mb-3">
                            {{ $caseStudy['title'] }}
                        </h3>
                        <p class="text-slate-200 text-[15px] leading-relaxed mb-5">
                            {{ $caseStudy['narrative'] }}
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                            @foreach($caseStudy['pillars'] as $pillar)
                                <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                                    <div class="font-heading text-xs font-bold uppercase tracking-wider text-emerald-400 mb-1">{{ $pillar['title'] }}</div>
                                    <p class="text-xs text-slate-300 leading-relaxed">{{ $pillar['detail'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Embedded REQUEST CONSULTATION Form for this Specific Service -->
                <div id="consultation" class="scroll-mt-28 rounded-2xl bg-white dark:bg-slate-900 border-2 border-emerald-600/30 dark:border-emerald-700/40 p-6 sm:p-10 shadow-md">
                    <div class="mb-7">
                        <span class="inline-flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700 dark:text-primary">
                            <span class="material-symbols-outlined text-base">assignment_turned_in</span>
                            Request Consultation
                        </span>
                        <h2 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                            Consult With Us on {{ $service['short_name'] }}
                        </h2>
                        <p class="text-sm sm:text-base text-slate-600 dark:text-gray-300 mt-1.5 leading-relaxed">
                            Complete the form below and our Regulatory Affairs &amp; Compliance team will respond with pathway guidance and next steps.
                        </p>
                    </div>

                    <form action="{{ route('consultation.submit') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="service_slug" value="{{ $service['slug'] }}"/>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label for="detail_service_name" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Selected Service Line *</label>
                                <select id="detail_service_name" name="service_name" required class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm font-medium py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600">
                                    @foreach($allServices as $optionService)
                                        <option value="{{ $optionService['name'] }}" {{ old('service_name', $service['name']) === $optionService['name'] ? 'selected' : '' }}>
                                            {{ $optionService['number'] }}. {{ $optionService['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="detail_name" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Full Name *</label>
                                <input type="text" id="detail_name" name="name" value="{{ old('name') }}" required placeholder="Your full name" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600"/>
                                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="detail_company" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Organization / Manufacturer / MAH</label>
                                <input type="text" id="detail_company" name="company" value="{{ old('company') }}" placeholder="Company or institution name" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600"/>
                            </div>

                            <div>
                                <label for="detail_email" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Business Email *</label>
                                <input type="email" id="detail_email" name="email" value="{{ old('email') }}" required placeholder="name@company.com" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600"/>
                                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="detail_phone" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Phone / WhatsApp</label>
                                <input type="text" id="detail_phone" name="phone" value="{{ old('phone') }}" placeholder="+255 ... or International" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600"/>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="detail_category" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Product / Facility Category</label>
                                <select id="detail_category" name="product_category" class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600">
                                    <option value="Pharmaceuticals / Prescription Medicines">Pharmaceuticals / Prescription &amp; OTC Medicines</option>
                                    <option value="Biologicals / Biosimilars / Vaccines">Biologicals / Biosimilars / Vaccines</option>
                                    <option value="Medical Devices & IVDs">Medical Devices &amp; In-Vitro Diagnostics (IVDs)</option>
                                    <option value="Controlled / Neonatal / Critical-Care Medicines">Controlled / Neonatal / Critical-Care Medicines</option>
                                    <option value="Warehouse / GSDP / GMP Facility">Warehouse, Wholesale Premises or Manufacturing Facility</option>
                                    <option value="Clinical Trial / Research Protocol">Clinical Trial / Research Protocol</option>
                                    <option value="Market Entry & Feasibility Advisory">Market Entry &amp; Feasibility Advisory</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="detail_message" class="block text-sm font-semibold text-slate-700 dark:text-gray-200 mb-1.5">Project Details &amp; Regulatory Questions *</label>
                                <textarea id="detail_message" name="message" rows="4" required placeholder="Tell us about your products, dossier readiness, target submission timeline or compliance needs in Tanzania..." class="w-full rounded-xl border-slate-300 dark:border-gray-700 dark:bg-slate-800 text-slate-900 dark:text-white text-sm py-3 px-4 focus:border-emerald-600 focus:ring-emerald-600">{{ old('message') }}</textarea>
                                @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                            <div class="text-xs text-slate-500 dark:text-gray-400">
                                Direct Desk: <a href="mailto:info@igeo.co.tz" class="font-semibold text-emerald-700 hover:underline">info@igeo.co.tz</a> &bull; <a href="tel:+255713035438" class="font-semibold text-emerald-700 hover:underline">+255 713 035 438</a>
                            </div>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-8 py-4 text-sm tracking-wide shadow-md transition-all shrink-0">
                                <span class="material-symbols-outlined text-[19px]">send</span>
                                <span>SUBMIT CONSULTATION REQUEST</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar: All 8 Services Drop-Line Switcher + Quick Contact -->
            <aside class="lg:col-span-4 space-y-6">
                <div class="sticky top-24 space-y-6">
                    <!-- Quick Switcher of All 8 Service Lines -->
                    <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-gray-800 overflow-hidden shadow-sm">
                        <div class="bg-[#0b1d33] px-5 py-4 text-white">
                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-400">Regulatory Service Lines</p>
                            <h3 class="font-heading text-base font-bold mt-0.5">Switch Service Line</h3>
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-gray-800">
                            @foreach($allServices as $item)
                                @php $isCurrent = $item['slug'] === $service['slug']; @endphp
                                <a href="{{ route('public.service.detail', ['slug' => $item['slug']]) }}"
                                   class="flex items-start gap-3 px-5 py-3.5 transition-colors {{ $isCurrent ? 'bg-emerald-50/90 dark:bg-emerald-950/40 border-l-4 border-emerald-600' : 'hover:bg-slate-50 dark:hover:bg-white/5' }}">
                                    <span class="text-xs font-extrabold mt-0.5 {{ $isCurrent ? 'text-emerald-700 dark:text-primary' : 'text-slate-400' }}">
                                        {{ $item['number'] }}
                                    </span>
                                    <span class="text-sm leading-snug {{ $isCurrent ? 'font-bold text-slate-900 dark:text-white' : 'font-medium text-slate-700 dark:text-gray-300' }}">
                                        {{ $item['name'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Direct Regulatory Desk Card -->
                    <div class="rounded-2xl bg-[#0b1d33] text-white p-6 shadow-md space-y-4">
                        <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-400">
                            <span class="material-symbols-outlined">support_agent</span>
                        </div>
                        <h3 class="font-heading text-lg font-bold">Need Immediate Regulatory Guidance?</h3>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            Our Dar es Salaam regulatory affairs &amp; 24/7 pharmacovigilance desk supports urgent TMDA queries, import permits and dossier assessments.
                        </p>
                        <div class="space-y-2 pt-1 text-sm">
                            <a href="tel:+255713035438" class="flex items-center gap-2.5 text-emerald-300 hover:text-white font-semibold">
                                <span class="material-symbols-outlined text-[18px]">call</span>
                                <span>+255 713 035 438</span>
                            </a>
                            <a href="mailto:info@igeo.co.tz" class="flex items-center gap-2.5 text-emerald-300 hover:text-white font-semibold">
                                <span class="material-symbols-outlined text-[18px]">mail</span>
                                <span>info@igeo.co.tz</span>
                            </a>
                        </div>
                        <a href="#consultation"
                           @click.prevent="document.getElementById('consultation').scrollIntoView({ behavior: 'smooth' })"
                           class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-[#0b1d33] font-extrabold py-3.5 text-xs uppercase tracking-wider transition-colors">
                            Request Consultation
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</div>
@endsection
