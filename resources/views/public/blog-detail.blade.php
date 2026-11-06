@extends('layouts.web')

@section('title', $blog->title)

@section('content')
<!-- Hero Section -->
<section class="relative bg-background-dark py-24 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        @if($blog->category)
            <span class="inline-block px-3 py-1 mb-6 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold uppercase tracking-widest">
                {{ $blog->category }}
            </span>
        @endif
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-8 leading-tight">
            {{ $blog->title }}
        </h1>
        <div class="flex items-center justify-center gap-6 text-sm text-gray-400 font-medium uppercase tracking-wider">
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">calendar_today</span>
                {{ $blog->created_at->format('M d, Y') }}
            </span>
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">person</span>
                {{ $blog->author ?? 'iGEO Team' }}
            </span>
        </div>
    </div>
</section>

<!-- Content -->
<section class="py-24 px-6 lg:px-20 bg-background-light dark:bg-background-dark min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Main Article -->
        <article class="bg-white dark:bg-background-dark/50 p-8 md:p-12 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm mb-16">
            @if($blog->image_path)
                <div class="mb-10 rounded-xl overflow-hidden">
                    <img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" class="w-full h-auto object-cover">
                </div>
            @endif
            
            <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:font-black prose-a:text-primary hover:prose-a:text-teal-400">
                {!! $blog->content !!}
            </div>
        </article>
        
        <!-- Related Posts -->
        @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
        <div class="border-t border-gray-200 dark:border-gray-800 pt-16">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-8">Related Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                 @foreach($relatedBlogs as $related)
                    <a href="{{ route('public.blog', $related->id) }}" class="group block bg-white dark:bg-background-dark/50 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 hover:shadow-lg transition-all">
                        <span class="text-xs text-slate-500 dark:text-gray-500 font-bold uppercase tracking-wider mb-2 block">
                            {{ $related->created_at->format('M d, Y') }}
                        </span>
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white group-hover:text-primary transition-colors line-clamp-2">
                            {{ $related->title }}
                        </h3>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="mt-12 text-center">
            <a href="{{ route('public.blogs') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-200 dark:border-gray-700 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-slate-900 dark:text-white">
                <span class="material-symbols-outlined">arrow_back</span> Back to Articles
            </a>
        </div>
    </div>
</section>
@endsection
