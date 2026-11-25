@extends('layouts.web')

@section('title', 'Pharmaceutical Regulatory Affairs, Market-Entry & Compliance Consultancy')

@section('content')
<!-- Executive Hero Section -->
<section class="relative bg-[#0b1d33] text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="absolute -top-32 -right-32 w-[520px] h-[520px] bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-[420px] h-[420px] bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-16 py-20 lg:py-28 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-400/10 border border-emerald-400/30 px-4 py-1.5">
                    <span class="material-symbols-outlined text-emerald-400 text-base">verified</span>
                    <span class="text-xs font-bold uppercase tracking-[0.16em] text-emerald-300">
                        Outsourced Regulatory &amp; Compliance Partner &bull; Tanzania
                    </span>
                </div>

                <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-[1.1]">
                    Specialist Pharmaceutical
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">
                        Regulatory Affairs &amp; Market Entry
                    </span>
                </h1>

                <p class="text-lg text-slate-200 leading-relaxed max-w-2xl">
                    {{ $positioning['lead'] }}
                </p>

                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="{{ route('public.services') }}" class="inline-flex items-center justify-center gap-2 h-14 px-8 bg-emerald-500 hover:bg-emerald-400 text-[#0b1d33] font-extrabold rounded-xl shadow-lg transition-all">
                        <span>Explore All 8 Service Lines</span>
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </a>
                    <a href="{{ route('public.services') }}#consultation" class="inline-flex items-center justify-center gap-2 h-14 px-8 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/25 text-white font-bold rounded-xl transition-all">
                        <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                        <span>Request Consultation</span>
                    </a>
                </div>
            </div>

            <!-- Right Quick Drop-Line Card of Core Service Lines -->
            <div class="lg:col-span-5">
                <div class="rounded-3xl bg-white/5 backdrop-blur-xl border border-white/15 p-6 sm:p-7 shadow-2xl">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-white/10">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-400">Quick Service Selector</p>
                            <h2 class="font-heading text-lg font-bold text-white">8 Core Regulatory Lines</h2>
                        </div>
                        <span class="material-symbols-outlined text-emerald-400">fact_check</span>
                    </div>
                    <div class="space-y-2">
                        @foreach($regulatoryServices as $regService)
                            <a href="{{ route('public.service.detail', ['slug' => $regService['slug']]) }}"
                               class="group flex items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 bg-white/5 hover:bg-emerald-500 hover:text-[#0b1d33] transition-all">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-xs font-extrabold text-emerald-400 group-hover:text-[#0b1d33]">{{ $regService['number'] }}</span>
                                    <span class="text-sm font-semibold text-slate-100 group-hover:text-[#0b1d33] truncate">{{ $regService['name'] }}</span>
                                </div>
                                <span class="material-symbols-outlined text-[18px] text-slate-400 group-hover:text-[#0b1d33] shrink-0">chevron_right</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Regulatory Key Metrics & Authorities Bar -->
<section class="bg-slate-900 py-10 px-6 lg:px-16 border-y border-white/10">
    <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="flex flex-col">
            <span class="font-heading text-emerald-400 text-3xl sm:text-4xl font-extrabold">8 Core</span>
            <span class="text-slate-300 text-xs sm:text-sm font-semibold uppercase tracking-wider mt-1">Regulatory Service Lines</span>
        </div>
        <div class="flex flex-col">
            <span class="font-heading text-white text-3xl sm:text-4xl font-extrabold">24/7</span>
            <span class="text-slate-300 text-xs sm:text-sm font-semibold uppercase tracking-wider mt-1">Pharmacovigilance Call Center</span>
        </div>
        <div class="flex flex-col">
            <span class="font-heading text-white text-3xl sm:text-4xl font-extrabold">TMDA &amp; PC</span>
            <span class="text-slate-300 text-xs sm:text-sm font-semibold uppercase tracking-wider mt-1">Submissions, GSDP &amp; GMP</span>
        </div>
        <div class="flex flex-col">
            <span class="font-heading text-emerald-400 text-3xl sm:text-4xl font-extrabold">End-to-End</span>
            <span class="text-slate-300 text-xs sm:text-sm font-semibold uppercase tracking-wider mt-1">Market Entry to Post-Marketing</span>
        </div>
    </div>
</section>

<!-- Eight Core Regulatory Service Lines Grid -->
<section class="py-20 lg:py-24 px-6 lg:px-16 bg-slate-50 dark:bg-background-dark">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-14">
            <div class="max-w-3xl">
                <span class="text-xs font-extrabold text-emerald-700 dark:text-primary uppercase tracking-[0.2em]">Core Regulatory Solutions</span>
                <h2 class="font-heading text-3xl sm:text-4xl lg:text-[42px] font-extrabold text-slate-900 dark:text-white mt-2 leading-tight">
                    Outsourced Regulatory &amp; Pharmaceutical Compliance Partner
                </h2>
                <p class="mt-3 text-slate-600 dark:text-gray-300 text-base leading-relaxed">
                    {{ $positioning['opportunity'] }}
                </p>
            </div>
            <a href="{{ route('public.services') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-gray-700 bg-white dark:bg-slate-900 px-5 py-3 text-sm font-bold text-slate-800 dark:text-white hover:border-emerald-600 hover:text-emerald-700 transition-colors shrink-0">
                <span>Open Interactive Drop-Line View</span>
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($regulatoryServices as $service)
                <div class="group flex flex-col justify-between bg-white dark:bg-slate-900 p-7 rounded-2xl border border-slate-200/90 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-emerald-500/60 transition-all duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-primary group-hover:bg-[#0b1d33] group-hover:text-emerald-400 transition-colors">
                                <span class="material-symbols-outlined text-2xl">{{ $service['icon'] }}</span>
                            </div>
                            <span class="font-heading text-xs font-extrabold uppercase tracking-wider text-slate-400">
                                Line {{ $service['number'] }}
                            </span>
                        </div>

                        <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white mb-2.5 leading-snug group-hover:text-emerald-700 dark:group-hover:text-primary transition-colors">
                            <a href="{{ route('public.service.detail', ['slug' => $service['slug']]) }}">
                                {{ $service['name'] }}
                            </a>
                        </h3>

                        <p class="text-sm text-slate-600 dark:text-gray-300 leading-relaxed mb-5">
                            {{ \Illuminate\Support\Str::limit($service['description'], 135) }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between">
                        <a href="{{ route('public.service.detail', ['slug' => $service['slug']]) }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-700 dark:text-primary hover:underline">
                            <span>View Details</span>
                            <span class="material-symbols-outlined text-[17px]">arrow_forward</span>
                        </a>
                        <a href="{{ route('public.service.detail', ['slug' => $service['slug']]) }}#consultation" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white">
                            Consult &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Case Study Section -->
<section class="bg-[#0b1d33] py-20 px-6 lg:px-16 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line pointer-events-none"></div>
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-5">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/15 border border-emerald-400/30 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-300">
                    <span class="material-symbols-outlined text-sm">science</span>
                    {{ $caseStudy['badge'] }}
                </span>
                <h2 class="font-heading text-3xl sm:text-4xl font-extrabold leading-tight">
                    {{ $caseStudy['title'] }}
                </h2>
                <p class="text-slate-200 text-base sm:text-lg leading-relaxed">
                    {{ $caseStudy['narrative'] }}
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ route('public.service.detail', ['slug' => 'regulatory-intelligence-and-market-access-advisory']) }}#consultation"
                       class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-[#0b1d33] font-extrabold px-6 py-3.5 text-sm transition-colors">
                        <span>Request Feasibility Consultation</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                    <a href="{{ route('public.adverse-event') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold px-6 py-3.5 text-sm transition-colors">
                        <span class="material-symbols-outlined text-[18px]">health_and_safety</span>
                        <span>24/7 Pharmacovigilance Portal</span>
                    </a>
                </div>
            </div>
            <div class="lg:col-span-5 space-y-4">
                @foreach($caseStudy['pillars'] as $pillar)
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
                        <h3 class="font-heading text-base font-bold text-emerald-400 mb-1.5">{{ $pillar['title'] }}</h3>
                        <p class="text-sm text-slate-300 leading-relaxed">{{ $pillar['detail'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Consultation CTA Banner -->
<section class="py-20 px-6 lg:px-16 bg-white dark:bg-background-dark">
    <div class="max-w-5xl mx-auto bg-slate-50 dark:bg-slate-900 p-10 lg:p-14 rounded-3xl border border-slate-200 dark:border-gray-800 text-center shadow-sm">
        <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mb-4">
            Planning Product Registration, Importation or Facility Licensing in Tanzania?
        </h2>
        <p class="text-slate-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto text-base leading-relaxed">
            Engage iGEO Pharmacy &amp; Consulting as your local regulatory affairs, pharmacovigilance and GSDP/GMP quality partner across TMDA, Pharmacy Council and TBS requirements.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('public.services') }}#consultation" class="inline-flex items-center gap-2 h-14 px-8 rounded-xl bg-[#0b1d33] hover:bg-emerald-700 text-white font-extrabold text-sm tracking-wide shadow-md transition-all">
                <span class="material-symbols-outlined text-[20px]">event_available</span>
                <span>REQUEST CONSULTATION</span>
            </a>
            <a href="{{ route('public.services') }}" class="inline-flex items-center gap-2 h-14 px-8 rounded-xl border border-slate-300 dark:border-gray-700 text-slate-800 dark:text-white font-bold text-sm hover:border-emerald-600 transition-all">
                <span>Browse All 8 Regulatory Services</span>
            </a>
        </div>
    </div>
</section>
@endsection
