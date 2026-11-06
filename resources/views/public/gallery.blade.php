@extends('layouts.web')

@section('content')
<!-- Hero Section -->
<section class="relative bg-background-dark py-24 px-6 lg:px-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 data-grid-line"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">Project Gallery</h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Explore our portfolio of successful projects and innovative solutions across various industries
        </p>
    </div>
</section>

<!-- Gallery Content -->
<section class="py-24 px-6 lg:px-20 bg-background-light dark:bg-background-dark min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Search Section -->
        <div class="mb-12 bg-white dark:bg-background-dark/50 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search gallery..."
                       class="flex-1 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-transparent text-slate-900 dark:text-white focus:ring-primary focus:border-primary">
                
                <select name="category" 
                        class="px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-transparent text-slate-900 dark:text-white focus:ring-primary focus:border-primary min-w-[200px]">
                    <option value="" class="dark:bg-background-dark">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }} class="dark:bg-background-dark">
                            {{ ucfirst($category) }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="px-8 py-3 bg-primary text-background-dark font-bold rounded-xl hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">search</span> Search
                </button>
                
                @if(request('search') || request('category'))
                    <a href="{{ route('public.gallery') }}" class="px-8 py-3 border border-gray-200 dark:border-gray-700 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center justify-center text-slate-900 dark:text-white">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if($galleries->count() > 0)
            <!-- Gallery Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($galleries as $index => $gallery)
                    <div class="group bg-white dark:bg-background-dark/50 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden cursor-pointer hover:shadow-xl transition-all duration-300" onclick="openModal('{{ $gallery->id }}')">
                        <!-- Image Container -->
                        <div class="relative aspect-square overflow-hidden bg-gray-100 dark:bg-gray-800">
                            <img src="{{ asset('storage/' . $gallery->file_path) }}" 
                                 alt="{{ $gallery->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            
                            <!-- Fallback -->
                            <div class="absolute inset-0 flex items-center justify-center hidden">
                                <span class="material-symbols-outlined text-6xl text-gray-400">image</span>
                            </div>
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-4xl transform scale-50 group-hover:scale-100 transition-transform duration-300">open_in_full</span>
                            </div>
                            
                            <!-- Category Badge -->
                            @if($gallery->category)
                                <span class="absolute top-4 left-4 px-3 py-1 bg-primary text-background-dark text-xs font-bold uppercase tracking-wider rounded-lg shadow-lg">
                                    {{ ucfirst($gallery->category) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-primary transition-colors">{{ $gallery->title }}</h3>
                            @if($gallery->description)
                                <p class="text-sm text-slate-600 dark:text-gray-400 line-clamp-2 mb-4">
                                    {{ $gallery->description }}
                                </p>
                            @endif
                            <div class="flex items-center text-xs text-slate-500 dark:text-gray-500 font-medium uppercase tracking-wider">
                                <span class="material-symbols-outlined text-sm mr-1">calendar_today</span>
                                {{ $gallery->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($galleries->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $galleries->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="text-center py-24 bg-white dark:bg-background-dark/50 rounded-2xl border border-gray-200 dark:border-gray-800">
                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-4">perm_media</span>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Gallery Images Found</h3>
                <p class="text-slate-600 dark:text-gray-400 mb-6">
                    @if(request('search') || request('category'))
                        No images match your current filters.
                    @else
                        No gallery images have been uploaded yet.
                    @endif
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('public.gallery') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-200 dark:border-gray-700 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-slate-900 dark:text-white">
                        <span class="material-symbols-outlined">arrow_back</span> View All Images
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<!-- Modal Script -->
<script>
    const galleries = @json($galleries->items());

    function openModal(galleryId) {
        const gallery = galleries.find(g => g.id == galleryId);
        if (!gallery) return;

        // Create modal
        const modal = document.createElement('div');
        modal.id = 'imageModal';
        modal.className = 'fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/95 backdrop-blur-sm animate-fade-in';
        modal.onclick = closeModal;

        const content = document.createElement('div');
        content.className = 'relative max-w-7xl max-h-[90vh] w-full flex flex-col items-center';
        content.onclick = (e) => e.stopPropagation();

        const closeBtn = document.createElement('button');
        closeBtn.className = 'absolute -top-12 right-0 text-white hover:text-primary transition-colors';
        closeBtn.innerHTML = '<span class="material-symbols-outlined text-4xl">close</span>';
        closeBtn.onclick = closeModal;

        const imgWrapper = document.createElement('div');
        imgWrapper.className = 'relative w-full h-auto max-h-[80vh] flex items-center justify-center';
        
        const img = document.createElement('img');
        img.src = `{{ asset('storage/') }}/${gallery.file_path}`;
        img.alt = gallery.title;
        img.className = 'max-w-full max-h-[80vh] object-contain rounded-xl shadow-2xl';

        const info = document.createElement('div');
        info.className = 'mt-6 text-center max-w-3xl';
        info.innerHTML = `
            <h3 class="text-2xl font-bold text-white mb-2">${gallery.title}</h3>
            <p class="text-gray-400">${gallery.description || ''}</p>
        `;

        imgWrapper.appendChild(img);
        content.appendChild(closeBtn);
        content.appendChild(imgWrapper);
        content.appendChild(info);
        modal.appendChild(content);
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.remove();
            document.body.style.overflow = 'auto';
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection
