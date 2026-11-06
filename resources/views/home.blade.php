@extends('layouts.web')

@section('title', 'Innovation-Focused Landing Page')

@section('content')
<section class="relative w-full overflow-hidden">
    <div class="flex flex-col lg:flex-row min-h-[85vh]">
        <!-- Left: Modern Agro-Processing -->
        <div class="relative w-full lg:w-1/2 group">
            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-all z-10"></div>
            <div class="h-full w-full bg-cover bg-center" data-alt="Modern automated agricultural greenhouse processing facility" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAKSHa6xVI9I2z-8Me9nIm5FPqSXcxuUkJ617PiRdsT_9743QuSna_MM_Oo57_ye-wH6A0AiHZgKBRqEBTnQKqlms02EF8lEWoDTNtzVDYx7AJ47Hks-jZ19ovuPhp37wZ6iUt0U87TIb7TCyQ4j59X2ooJjTmfMH9PfNC6aLpayR5icmrCDpMj6q-VJ1UOV3X_1iX52lbKaGXDsnITKc6CKjTekDtbB56VUW_IPWk_lXhSq7kjTZRCyAEnUo2d1-zpWxSeBrc5DHJB');"></div>
            <div class="absolute bottom-10 left-10 z-20 hidden lg:block">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-widest">Precision Ag</span>
            </div>
        </div>
        <!-- Right: High-Tech Lab -->
        <div class="relative w-full lg:w-1/2 group">
            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-all z-10"></div>
            <div class="h-full w-full bg-cover bg-center" data-alt="Scientist working in a high-tech pharmaceutical laboratory" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCXOc6_znw44XVuawEuLhGT6rNCuezFaBrs52VTD3l5_BsUoPUR2QIk6XU2zsbhm7IB-lLXATKiMaErIdvHH0NanXoOj-9nBEx5E0YzYfSfqpVRyrANOFVkI3_rCRJi7Vonq9HW-IOZbEu_XU5CceAFpTd6AyQiwyENKEoYPAG3g1zafQhJ9N4v0yYNOObgMdVeVekgReeOTJwHWXAUkOuVx1YvlSTdIEmaRyoHlk9otEMZ8-2RuVZSQUvJawwb6WSmHDMGL1EOe1jk');"></div>
            <div class="absolute bottom-10 right-10 z-20 hidden lg:block text-right">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-widest">Sustainable Health</span>
            </div>
        </div>
        <!-- Floating Center Tagline -->
        <div class="absolute inset-0 flex flex-col items-center justify-center z-30 pointer-events-none p-6">
            <div class="max-w-4xl text-center pointer-events-auto">
                <div class="mb-6 inline-flex bg-background-dark/90 dark:bg-primary/20 backdrop-blur-xl border border-primary/30 px-4 py-2 rounded-full">
                    <span class="text-primary text-xs font-bold uppercase tracking-[0.2em]">The Future of Agribusiness &amp; Pharmacy</span>
                </div>
                <h1 class="text-4xl md:text-6xl lg:text-8xl font-black text-white drop-shadow-2xl leading-tight">
                    Redefining Access <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-teal-400">from Seed to Shelf</span>
                </h1>
                <p class="mt-8 max-w-2xl mx-auto text-lg text-white/90 font-medium drop-shadow-md">
                    Bridging the gap between nutritional security and pharmaceutical excellence with data-driven precision.
                </p>
                <div class="mt-12 flex flex-wrap justify-center gap-4">
                    <a href="#" class="flex items-center justify-center h-14 px-10 bg-primary text-background-dark font-black rounded-xl shadow-xl hover:shadow-primary/40 hover:-translate-y-1 transition-all">
                        Explore Our Services
                    </a>
                    <a href="#" class="flex items-center justify-center h-14 px-10 bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold rounded-xl hover:bg-white/20 transition-all">
                        View Global Impact
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Stats Bar / Data in Motion -->
<section class="bg-background-dark py-12 px-6 lg:px-20 border-y border-white/5">
    <div class="max-w-7xl mx-auto flex flex-wrap justify-between gap-8">
        <div class="flex flex-col">
            <span class="text-primary text-4xl font-black tabular-nums">160+</span>
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-widest mt-1">Countries Reached</span>
        </div>
        <div class="h-16 w-px bg-white/10 hidden md:block"></div>
        <div class="flex flex-col">
            <span class="text-white text-4xl font-black tabular-nums">500k+</span>
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-widest mt-1">Tons Managed</span>
        </div>
        <div class="h-16 w-px bg-white/10 hidden md:block"></div>
        <div class="flex flex-col">
            <span class="text-white text-4xl font-black tabular-nums">1.2M</span>
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-widest mt-1">Pharma Units</span>
        </div>
        <div class="h-16 w-px bg-white/10 hidden md:block"></div>
        <div class="flex flex-col">
            <span class="text-primary text-4xl font-black tabular-nums">22%</span>
            <span class="text-gray-400 text-sm font-semibold uppercase tracking-widest mt-1">Efficiency Increase</span>
        </div>
    </div>
</section>
<!-- Services Section: Modern Grid -->
<section class="py-24 px-6 lg:px-20 data-grid-line dark:data-grid-line-dark">
    <div class="max-w-7xl mx-auto">
        <div class="mb-16">
            <h3 class="text-sm font-bold text-primary uppercase tracking-[0.3em] mb-4">Core Competencies</h3>
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white max-w-2xl leading-tight">
                Integrated Solutions for a Complex Supply Chain
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($services as $index => $service)
            <!-- Service Card {{ $index + 1 }} -->
            <div class="group bg-white dark:bg-background-dark/50 p-8 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300 hover-reveal">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-background-dark transition-all">
                    @if($service->picture)
                        <img src="{{ asset('storage/services/' . $service->picture) }}" alt="{{ $service->name }}" class="w-8 h-8 object-cover rounded-lg">
                    @else
                        <span class="material-symbols-outlined text-3xl">precision_manufacturing</span>
                    @endif
                </div>
                <h4 class="text-xl font-extrabold mb-3">{{ $service->name }}</h4>
                <p class="text-slate-600 dark:text-gray-400 leading-relaxed mb-4">
                    {{ Str::limit($service->description, 100) }}
                </p>
                <div class="reveal-content text-sm text-slate-500 dark:text-gray-500 space-y-2">
                    <a href="#" class="text-primary hover:underline">Learn more &rarr;</a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">No services available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
<!-- Global Map Section -->
<section class="bg-background-dark py-24 px-6 lg:px-20 overflow-hidden relative">
    <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none data-grid-line"></div>
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/3">
                <h3 class="text-primary font-bold uppercase tracking-widest text-sm mb-4">Global Network</h3>
                <h2 class="text-4xl font-black text-white mb-6">Connecting the World through Data</h2>
                <p class="text-gray-400 mb-8 leading-relaxed">
                    Our logistics infrastructure is powered by real-time analytics, ensuring that whether it's seeds or life-saving pharmaceuticals, they reach their destination efficiently and safely.
                </p>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3 text-white font-semibold">
                        <span class="material-symbols-outlined text-primary">public</span>
                        160+ Countries
                    </li>
                    <li class="flex items-center gap-3 text-white font-semibold">
                        <span class="material-symbols-outlined text-primary">hub</span>
                        24 Strategic Hubs
                    </li>
                    <li class="flex items-center gap-3 text-white font-semibold">
                        <span class="material-symbols-outlined text-primary">speed</span>
                        Real-time Transit Monitoring
                    </li>
                </ul>
            </div>
            <div class="lg:w-2/3 relative">
                <div class="w-full aspect-video bg-gray-900 rounded-3xl border border-white/10 p-4 shadow-2xl relative overflow-hidden">
                    <img class="w-full h-full object-cover rounded-2xl opacity-50 grayscale contrast-125" data-alt="Abstract glowing world map with data connection lines" data-location="Global" src="https://lh3.googleusercontent.com/aida-public/AB6AXuARZPC-SB0wMNKHCBFtVQuY9YQum9BN8HYjYeDNb9bH4CUwfQSuTsgYscAFhmrEHZoJnDSyF9lQXZRs8pxhoHRKVEuCwAGU-x6WRLDaHtqNRwyKcxFQOs2elkM4W6aYQZQDn-Kf4XCOa1v-NRZDMrC_nlzRDtjereB9WTUfMICmbEiEMkuQMdRbS-QTNEntLxgojN8Lkg700qMcSb2xPbIiz-96ZpNHKZDI1zHwFZmpP63DyLzlCGrSFFvVM9vwjQ6lJiQWM3d_mDdi"/>
                    <!-- Glowing markers -->
                    <div class="absolute top-1/4 left-1/4 w-4 h-4 bg-primary rounded-full animate-pulse shadow-[0_0_15px_rgba(19,236,19,0.8)]"></div>
                    <div class="absolute top-1/2 left-1/3 w-3 h-3 bg-primary rounded-full animate-pulse delay-700 shadow-[0_0_10px_rgba(19,236,19,0.8)]"></div>
                    <div class="absolute top-1/3 right-1/4 w-4 h-4 bg-primary rounded-full animate-pulse delay-1000 shadow-[0_0_15px_rgba(19,236,19,0.8)]"></div>
                    <div class="absolute bottom-1/4 right-1/3 w-3 h-3 bg-primary rounded-full animate-pulse delay-300 shadow-[0_0_10px_rgba(19,236,19,0.8)]"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 flex justify-between items-center text-xs text-primary font-bold tracking-tighter uppercase opacity-60">
                        <span>System Status: Online</span>
                        <span>Active Connections: 14,289</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Newsletter / Insights CTA -->
<section class="py-24 px-6 lg:px-20 bg-white dark:bg-background-dark">
    <div class="max-w-4xl mx-auto bg-primary/5 dark:bg-primary/10 p-10 lg:p-16 rounded-[2rem] border border-primary/20 text-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-teal-400/10 rounded-full blur-3xl"></div>
        <h2 class="text-3xl md:text-4xl font-black mb-6">Stay Ahead with iGEO Insights</h2>
        <p class="text-slate-600 dark:text-gray-400 mb-10 max-w-xl mx-auto">
            Get our monthly briefing on precision agriculture, pharmaceutical supply chain innovations, and sustainable growth strategies.
        </p>
        <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <input class="flex-1 px-6 h-14 rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary focus:border-primary" placeholder="Enter your business email" type="email"/>
            <button class="h-14 px-8 bg-background-dark text-white dark:bg-primary dark:text-background-dark font-bold rounded-xl hover:scale-105 transition-transform" type="submit">
                Subscribe
            </button>
        </form>
        <p class="mt-6 text-xs text-slate-400">Join 12,000+ industry leaders worldwide.</p>
    </div>
</section>
@endsection