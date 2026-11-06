@extends('layouts.web')

@section('title', 'Our Clients')

@section('content')
<!-- Hero Section -->
<section class="relative bg-background-dark py-24 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">Strategic Partnerships</h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Collaborating with global farm cooperatives, healthcare networks, and distributors.
        </p>
    </div>
</section>

<!-- Clients Content -->
<section class="py-24 px-6 lg:px-20 bg-background-light dark:bg-background-dark min-h-screen">
    <div class="max-w-7xl mx-auto">
        @if($publicClients->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                @foreach($publicClients as $client)
                    <div class="group bg-white dark:bg-background-dark/50 p-8 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300 flex flex-col items-center justify-center text-center h-full">
                        
                        <!-- Logo / Placeholder -->
                        <div class="mb-4 flex items-center justify-center h-20 w-20 rounded-full bg-gray-50 dark:bg-gray-800 group-hover:scale-110 transition-transform duration-300">
                             @if($client->logo_path)
                                <img src="{{ asset('storage/' . $client->logo_path) }}" 
                                     alt="{{ $client->name }}" 
                                     class="max-w-[70%] max-h-[70%] object-contain">
                            @else
                                <div class="text-2xl font-bold text-gray-400 group-hover:text-primary transition-colors">
                                    {{ substr($client->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $client->name }}</h3>
                        @if($client->company)
                            <p class="text-xs font-bold text-slate-500 dark:text-gray-500 uppercase tracking-wider">
                                {{ $client->company }}
                            </p>
                        @endif
                        
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Results -->
             <div class="text-center py-24 bg-white dark:bg-background-dark/50 rounded-2xl border border-gray-200 dark:border-gray-800">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">handshake</span>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Clients Listed</h3>
                <p class="text-slate-600 dark:text-gray-400">
                    Client list is currently being updated.
                </p>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 px-6 lg:px-20 bg-primary/5 border-y border-primary/10">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-6">Become Our Next Partner</h2>
        <p class="text-slate-600 dark:text-gray-400 mb-10 max-w-xl mx-auto leading-relaxed">
            Join the network of companies transforming their operations with our technology.
        </p>
        <a href="{{ route('home') }}#contact" class="inline-flex items-center gap-2 h-14 px-8 bg-background-dark text-white dark:bg-primary dark:text-background-dark font-bold rounded-xl hover:scale-105 transition-transform shadow-lg">
            Partner With Us <span class="material-symbols-outlined">arrow_forward</span>
        </a>
    </div>
</section>
@endsection