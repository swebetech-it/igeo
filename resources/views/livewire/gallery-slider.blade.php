<div
    x-data="{
        currentPage: $wire.entangle('currentPage').live,
        totalPages: {{ $totalPages }},
        autoSlide: $wire.entangle('autoSlide').live,
        interval: null,
        isPaused: false,
        lastActivity: Date.now(),

        // Optimized: Batch server requests and reduce frequency
        startAutoSlide() {
            if (!this.autoSlide || this.isPaused) return;
            this.stopAutoSlide(); // Clear any existing interval
            this.interval = setInterval(() => {
                if (!this.isPaused && document.visibilityState === 'visible') {
                    // Only advance if page is visible (performance optimization)
                    this.lastActivity = Date.now();
                    $wire.nextSlide();
                }
            }, 5000); // Increased to 5 seconds to reduce server load
        },

        stopAutoSlide() {
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
        },

        pauseAutoSlide() {
            this.isPaused = true;
            this.stopAutoSlide();
        },

        resumeAutoSlide() {
            this.isPaused = false;
            // Only resume if user was recently active
            if (this.autoSlide && (Date.now() - this.lastActivity < 300000)) { // 5 minutes
                this.startAutoSlide();
            }
        },

        // Performance: Handle page visibility changes
        handleVisibilityChange() {
            if (document.visibilityState === 'hidden') {
                this.pauseAutoSlide();
            } else if (document.visibilityState === 'visible' && this.autoSlide) {
                setTimeout(() => this.resumeAutoSlide(), 2000);
            }
        },

        init() {
            // Start auto-slide with performance considerations
            if (this.autoSlide && document.visibilityState === 'visible') {
                setTimeout(() => this.startAutoSlide(), 2000); // Longer delay for initialization
            }

            // Listen for page visibility changes (battery/performance optimization)
            document.addEventListener('visibilitychange', () => this.handleVisibilityChange());

            // Watch for changes in autoSlide property
            this.$watch('autoSlide', value => {
                if (value && !this.isPaused && document.visibilityState === 'visible') {
                    this.startAutoSlide();
                } else {
                    this.stopAutoSlide();
                }
            });

            // Cleanup on component destroy
            this.$cleanup = () => {
                document.removeEventListener('visibilitychange', this.handleVisibilityChange);
                this.stopAutoSlide();
            };
        }
    }"
    @mouseenter="pauseAutoSlide()"
    @mouseleave="resumeAutoSlide()"
    class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl p-8 shadow-2xl relative"
>
    <!-- Header -->
    <div class="text-center mb-8">
        <h3 class="text-3xl font-bold text-gray-900 mb-2">Our Project Gallery</h3>
        <p class="text-gray-600">Discover our latest construction and engineering projects</p>
    </div>

    <!-- Gallery Grid - Optimized for 6 images -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" wire:ignore.self>
        @foreach($currentPageGalleries as $index => $image)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="aspect-square overflow-hidden">
                    @if($image->file_path && Storage::disk('public')->exists($image->file_path))
                        <img src="{{ asset('storage/' . $image->file_path) }}"
                             alt="{{ $image->title }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                             loading="lazy"
                             decoding="async"
                             fetchpriority="auto"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             onload="this.style.opacity='1';"
                             style="opacity: 0; transition: opacity 0.3s ease-in-out;">

                        <!-- Fallback for broken images -->
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex-col items-center justify-center" style="display: none;">
                            <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                            <span class="text-gray-500 text-sm">Image not found</span>
                        </div>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex flex-col items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                            <span class="text-gray-500 text-sm">No image available</span>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-gray-900 mb-1 truncate">{{ $image->title }}</h4>
                    <p class="text-gray-600 text-sm truncate">{{ $image->description ?: 'No description available' }}</p>
                    @if($image->category)
                        <span class="inline-block mt-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $image->category }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Navigation Controls -->
    <div class="flex items-center justify-between mb-6">
        <!-- Previous Button -->
        <button wire:click="prevSlide"
                @click="pauseAutoSlide(); setTimeout(() => resumeAutoSlide(), 3000)"
                class="flex items-center justify-center w-12 h-12 bg-white hover:bg-blue-50 text-blue-600 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                :disabled="currentPage === 1">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- Dots Indicator -->
        <div class="flex space-x-2">
            @for($i = 1; $i <= $totalPages; $i++)
                <button wire:click="goToSlide({{ $i }})"
                        @click="pauseAutoSlide(); setTimeout(() => resumeAutoSlide(), 3000)"
                        class="w-3 h-3 rounded-full transition-all duration-300 hover:scale-110"
                        :class="currentPage === {{ $i }} ? 'bg-blue-600 w-8' : 'bg-gray-300 hover:bg-gray-400'">
                </button>
            @endfor
        </div>

        <!-- Next Button -->
        <button wire:click="nextSlide"
                @click="pauseAutoSlide(); setTimeout(() => resumeAutoSlide(), 3000)"
                class="flex items-center justify-center w-12 h-12 bg-white hover:bg-blue-50 text-blue-600 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                :disabled="currentPage === totalPages">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <!-- Auto-slide Toggle -->
    <div class="text-center">
        <button wire:click="toggleAutoSlide"
                class="inline-flex items-center px-6 py-3 rounded-full text-white font-medium transition-all duration-300 transform hover:scale-105 shadow-lg"
                :class="autoSlide ? 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700' : 'bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700'">
            <i class="fas mr-2 transition-transform duration-300" :class="autoSlide ? 'fa-pause' : 'fa-play'" x-bind:style="autoSlide && !isPaused ? 'animation: pulse 2s infinite' : ''"></i>
            <span x-text="autoSlide ? 'Auto Slideshow ON' : 'Auto Slideshow OFF'"></span>
        </button>

        <div class="mt-4 text-sm text-gray-600 flex items-center justify-center space-x-4">
            <div class="flex items-center">
                <span>Page {{ $currentPage }} of {{ $totalPages }}</span>
            </div>
            <span>•</span>
            <div class="flex items-center">
                <span>{{ $totalImages }} total images</span>
            </div>
            <template x-if="autoSlide && !isPaused">
                <div class="flex items-center text-green-600">
                    <i class="fas fa-circle text-xs animate-pulse mr-1"></i>
                    <span class="text-xs">Auto-playing</span>
                </div>
            </template>
        </div>
    </div>

    <!-- Progress indicator for auto-slide -->
    <template x-if="autoSlide && !isPaused">
        <div class="mt-4">
            <div class="w-full bg-gray-200 rounded-full h-1">
                <div class="bg-blue-600 h-1 rounded-full transition-all duration-100"
                     style="width: 0%; animation: slideProgress 4s linear infinite;"></div>
            </div>
        </div>
    </template>

    <style>
        @keyframes slideProgress {
            0% { width: 0%; }
            100% { width: 100%; }
        }
    </style>
</div>
