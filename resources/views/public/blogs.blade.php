@extends('layouts.web')

@section('content')
<!-- Hero Section -->
<section class="relative bg-background-dark py-24 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">Insights & Articles</h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Stay updated with the latest trends, insights, and best practices in technology and software development
        </p>
    </div>
</section>

<!-- Blog Content -->
<section class="py-24 px-6 lg:px-20 bg-background-light dark:bg-background-dark min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Search Section -->
        <div class="mb-12 bg-white dark:bg-background-dark/50 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search articles..."
                       class="flex-1 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-transparent text-slate-900 dark:text-white focus:ring-primary focus:border-primary">
                
                <select name="category"
                        class="px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-transparent text-slate-900 dark:text-white focus:ring-primary focus:border-primary min-w-[200px]">
                    <option value="" class="dark:bg-background-dark">All Categories</option>
                    <option value="technology" {{ request('category') === 'technology' ? 'selected' : '' }} class="dark:bg-background-dark">Technology</option>
                    <option value="development" {{ request('category') === 'development' ? 'selected' : '' }} class="dark:bg-background-dark">Development</option>
                    <option value="business" {{ request('category') === 'business' ? 'selected' : '' }} class="dark:bg-background-dark">Business</option>
                    <option value="tutorial" {{ request('category') === 'tutorial' ? 'selected' : '' }} class="dark:bg-background-dark">Tutorial</option>
                </select>
                
                <button type="submit" class="px-8 py-3 bg-primary text-background-dark font-bold rounded-xl hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">search</span> Search
                </button>
                
                @if(request('search') || request('category'))
                    <a href="{{ route('public.blogs') }}" class="px-8 py-3 border border-gray-200 dark:border-gray-700 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center justify-center text-slate-900 dark:text-white">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if($blogs->count() > 0)
            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs as $index => $blog)
                    <div class="group bg-white dark:bg-background-dark/50 p-8 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-primary/50 transition-all duration-300 flex flex-col h-full">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-background-dark transition-all">
                            <span class="material-symbols-outlined text-3xl">article</span>
                        </div>
                        
                        @if($blog->category)
                            <div class="mb-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-xs font-bold uppercase tracking-wider">
                                    {{ ucfirst($blog->category) }}
                                </span>
                            </div>
                        @endif
                        
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-4 group-hover:text-primary transition-colors line-clamp-2">
                            {{ $blog->title }}
                        </h3>
                        
                        @if($blog->excerpt)
                            <p class="text-slate-600 dark:text-gray-400 mb-6 flex-1 line-clamp-3">
                                {{ Str::limit($blog->excerpt, 150) }}
                            </p>
                        @endif
                        
                        <div class="mt-auto flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-800">
                            <div class="flex items-center text-xs text-slate-500 dark:text-gray-500 font-medium uppercase tracking-wider">
                                <span class="material-symbols-outlined text-sm mr-1">calendar_today</span>
                                {{ $blog->created_at->format('M d, Y') }}
                            </div>
                            <a href="{{ route('public.blog', $blog->id) }}" class="flex items-center gap-1 text-sm font-bold text-slate-900 dark:text-white hover:text-primary transition-colors">
                                Read More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($blogs->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $blogs->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="text-center py-24 bg-white dark:bg-background-dark/50 rounded-2xl border border-gray-200 dark:border-gray-800">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">article</span>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Blog Posts Found</h3>
                <p class="text-slate-600 dark:text-gray-400 mb-6">
                    @if(request('search') || request('category'))
                        No articles match your current filters.
                    @else
                        No blog posts have been published yet.
                    @endif
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('public.blogs') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-200 dark:border-gray-700 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-slate-900 dark:text-white">
                        <span class="material-symbols-outlined">arrow_back</span> View All Posts
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<!-- Recent Posts Sidebar (Optional) -->
@if(isset($recentBlogs) && $recentBlogs->count() > 0)
<section class="py-24 px-6 lg:px-20 bg-white/50 dark:bg-black/20 border-t border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto">
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Recent Articles</h2>
            <p class="text-slate-600 dark:text-gray-400">
                Latest insights from our blog
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($recentBlogs->take(3) as $blog)
                <div class="group bg-white dark:bg-background-dark p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-lg transition-all">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-3 group-hover:text-primary transition-colors line-clamp-2">
                        {{ $blog->title }}
                    </h3>
                    <div class="flex items-center text-xs text-slate-500 dark:text-gray-500 font-medium uppercase tracking-wider mb-6">
                        <span class="material-symbols-outlined text-sm mr-1">calendar_today</span>
                        {{ $blog->created_at->format('M d, Y') }}
                    </div>
                    <a href="{{ route('public.blog', $blog->id) }}" class="inline-flex items-center gap-1 text-sm font-bold text-primary">
                        Read More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
