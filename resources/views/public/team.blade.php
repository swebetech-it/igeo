@extends('layouts.web')

@section('title', 'Our Team')

@section('content')
<!-- Hero Section -->
<section class="relative bg-background-dark py-24 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">Scientific & Logistics Experts</h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Agronomists, Pharmacists, and Supply Chain Architects dedicated to global health.
        </p>
    </div>
</section>

<!-- Team Content -->
<section class="py-24 px-6 lg:px-20 bg-background-light dark:bg-background-dark min-h-screen">
    <div class="max-w-7xl mx-auto">
        @if($teamMembers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($teamMembers as $member)
                    <div class="group bg-white dark:bg-background-dark/50 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <!-- Image -->
                        <div class="relative aspect-[3/4] overflow-hidden bg-gray-100 dark:bg-gray-800">
                            @if($member->photo_path)
                                <img src="{{ asset('storage/' . $member->photo_path) }}" 
                                     alt="{{ $member->name }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                    <span class="material-symbols-outlined text-6xl text-gray-400">person</span>
                                </div>
                            @endif
                            
                            <!-- Social Overlay -->
                            <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-gradient-to-t from-black/80 to-transparent">
                                <div class="flex justify-center gap-4">
                                    @if($member->linkedin)
                                        <a href="{{ $member->linkedin }}" target="_blank" class="text-white hover:text-primary transition-colors">
                                            <i class="fab fa-linkedin text-xl"></i>
                                        </a>
                                    @endif
                                    @if($member->twitter)
                                        <a href="{{ $member->twitter }}" target="_blank" class="text-white hover:text-primary transition-colors">
                                            <i class="fab fa-twitter text-xl"></i>
                                        </a>
                                    @endif
                                    @if($member->email)
                                        <a href="mailto:{{ $member->email }}" class="text-white hover:text-primary transition-colors">
                                            <i class="fas fa-envelope text-xl"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $member->name }}</h3>
                            <p class="text-sm font-bold text-primary uppercase tracking-wider mb-3">{{ $member->position }}</p>
                            
                            @if($member->bio)
                                <p class="text-sm text-slate-600 dark:text-gray-400 line-clamp-3">
                                    {{ $member->bio }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-24 bg-white dark:bg-background-dark/50 rounded-2xl border border-gray-200 dark:border-gray-800">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">groups</span>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Team Not Found</h3>
                <p class="text-slate-600 dark:text-gray-400">
                    We are currently updating our team roster. Please check back soon.
                </p>
            </div>
        @endif
    </div>
</section>

<!-- Join Us CTA -->
<section class="py-24 px-6 lg:px-20 bg-background-dark relative overflow-hidden text-center">
    <div class="absolute inset-0 bg-primary/5"></div>
    <div class="max-w-4xl mx-auto relative z-10">
        <h2 class="text-3xl md:text-4xl font-black text-white mb-6">Join Our Mission</h2>
        <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            We are always looking for talented individuals to help us redefine the future of agribusiness and technology.
        </p>
        <a href="{{ route('home') }}#contact" class="inline-flex items-center gap-2 h-14 px-8 bg-primary text-background-dark font-bold rounded-xl hover:scale-105 transition-transform shadow-lg shadow-primary/20">
            View Open Positions <span class="material-symbols-outlined">arrow_forward</span>
        </a>
    </div>
</section>
@endsection
