@extends('layouts.web')

@section('title', 'About Us')

@section('content')
<!-- Hero Section -->
<section class="relative bg-background-dark py-24 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">
            Pioneering <span class="text-primary">Agro</span> & <span class="text-teal-custom">Pharma</span> Innovation
        </h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Integrated solutions for sustainable agriculture and global healthcare access.
        </p>
    </div>
</section>

<!-- Tab Navigation -->
<section class="sticky top-[73px] z-40 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
        <div class="flex overflow-x-auto gap-8" id="profileTabs">
            @foreach($aboutUs as $index => $profile)
            <button onclick="switchTab({{ $index }})" 
                    class="tab-btn py-4 text-sm font-bold uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap {{ $index === 0 ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
                    data-index="{{ $index }}">
                {{ $profile->title }}
            </button>
            @endforeach
        </div>
    </div>
</section>

<!-- Content Sections -->
<div class="min-h-screen">
    @foreach($aboutUs as $index => $profile)
    <div id="profile-content-{{ $index }}" class="{{ $index === 0 ? '' : 'hidden' }} animate-fade-in">
        
        <!-- Stats Section -->
        <section class="border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-background-dark/50 py-12 px-6 lg:px-20">
            <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
                @if($profile->founded_year)
                <div class="text-center">
                    <div class="text-4xl font-black text-primary mb-2">{{ date('Y') - $profile->founded_year }}+</div>
                    <div class="text-sm font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400">Years Active</div>
                </div>
                @endif
                @if($profile->projects_completed)
                <div class="text-center">
                    <div class="text-4xl font-black text-primary mb-2">{{ $profile->projects_completed }}+</div>
                    <div class="text-sm font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400">Projects</div>
                </div>
                @endif
                @if($profile->clients_served)
                <div class="text-center">
                    <div class="text-4xl font-black text-primary mb-2">{{ $profile->clients_served }}+</div>
                    <div class="text-sm font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400">Clients</div>
                </div>
                @endif
                @if($profile->employees_count)
                <div class="text-center">
                    <div class="text-4xl font-black text-primary mb-2">{{ $profile->employees_count }}+</div>
                    <div class="text-sm font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400">Experts</div>
                </div>
                @endif
            </div>
        </section>

        <!-- Company Overview -->
        <section class="py-24 px-6 lg:px-20 bg-background-light dark:bg-background-dark">
            <div class="max-w-5xl mx-auto">
                <div class="bg-white dark:bg-background-dark/50 p-8 md:p-16 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-8">{{ $profile->title }}</h2>
                        <div class="prose prose-lg dark:prose-invert max-w-none text-slate-600 dark:text-gray-400 leading-relaxed">
                            {!! nl2br(e($profile->content)) !!}
                        </div>
                    </div>
                    <!-- Decorative Element -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
                </div>
            </div>
        </section>

        <!-- Mission, Vision, Values -->
        <section class="py-24 px-6 lg:px-20 data-grid-line dark:data-grid-line-dark border-t border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">
                    @if($profile->mission)
                    <div class="group bg-white dark:bg-background-dark/50 p-10 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                        <div class="mb-8 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-background-dark transition-all">
                            <span class="material-symbols-outlined text-4xl">bullseye</span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4">Our Mission</h3>
                        <p class="text-lg text-slate-600 dark:text-gray-400 leading-relaxed">
                            {{ $profile->mission }}
                        </p>
                    </div>
                    @endif

                    @if($profile->vision)
                    <div class="group bg-white dark:bg-background-dark/50 p-10 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300">
                        <div class="mb-8 flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-custom/10 text-teal-custom group-hover:bg-teal-custom group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-4xl">visibility</span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4">Our Vision</h3>
                        <p class="text-lg text-slate-600 dark:text-gray-400 leading-relaxed">
                            {{ $profile->vision }}
                        </p>
                    </div>
                    @endif
                </div>

                @if($profile->values)
                <div class="bg-slate-900 rounded-3xl p-10 md:p-16 text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-primary/10 data-grid-line opacity-20"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black text-white mb-6">Core Values</h3>
                        <div class="flex flex-wrap justify-center gap-4 md:gap-8">
                            @foreach(explode('•', $profile->values) as $value)
                            <span class="px-6 py-3 bg-white/10 backdrop-blur-sm border border-white/10 rounded-full text-white font-bold text-sm md:text-base uppercase tracking-wider hover:bg-primary hover:text-background-dark transition-colors cursor-default">
                                {{ trim($value) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
    @endforeach
</div>

<!-- CTA Section -->
<section class="py-24 px-6 lg:px-20 bg-background-dark relative overflow-hidden text-center border-t border-white/5">
    <div class="absolute inset-0 bg-primary/5"></div>
    <div class="max-w-4xl mx-auto relative z-10">
        <h2 class="text-3xl md:text-5xl font-black text-white mb-8">Ready to Partner With Us?</h2>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('public.team') }}" class="flex items-center gap-2 h-14 px-8 bg-primary text-background-dark font-bold rounded-xl hover:scale-105 transition-transform">
                Meet The Team <span class="material-symbols-outlined">group</span>
            </a>
            <a href="{{ route('home') }}#contact" class="flex items-center gap-2 h-14 px-8 bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold rounded-xl hover:bg-white/20 transition-all">
                Contact Us <span class="material-symbols-outlined">mail</span>
            </a>
        </div>
    </div>
</section>

<script>
    function switchTab(index) {
        // Hide all contents
        document.querySelectorAll('[id^="profile-content-"]').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Show selected content
        document.getElementById('profile-content-' + index).classList.remove('hidden');
        
        // Update styling
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400');
        });
        
        const activeBtn = document.querySelector(`.tab-btn[data-index="${index}"]`);
        activeBtn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400');
        activeBtn.classList.add('border-primary', 'text-primary');
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>
@endsection
