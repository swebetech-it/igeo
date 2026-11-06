<div x-data="{
    showCreateModal: $wire.entangle('showCreateForm'),
    showEditModal: $wire.entangle('showEditForm')
}" class="min-h-screen bg-gradient-to-br from-purple-50 to-purple-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4">
        <!-- Include unified styles -->
        @include('components.admin.ui-consistency-styles')
        
        <!-- Header Section -->
        <x-admin.page-header 
            title="Gallery Management"
            subtitle="Manage your project gallery and showcase your work"
            icon="fas fa-images"
            color="purple"
        >
            <x-slot name="actions">
                <button wire:click="openCreateForm" type="button"
                        class="admin-btn admin-btn-primary">
                    <i class="fas fa-plus mr-2"></i>Add New Image
                </button>
            </x-slot>
        </x-admin.page-header>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 text-purple-700 dark:text-purple-300 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filters Section -->
        <div class="admin-filters-card">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="admin-label">Search Gallery</label>
                    <div class="admin-input-group">
                        <div class="admin-input-icon">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.live="search" class="admin-input admin-input-with-icon" placeholder="Search by title or description...">
                    </div>
                </div>
                <div>
                    <label class="admin-label">Filter by Category</label>
                    <select wire:model.live="filterCategory" class="admin-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFilters" class="admin-btn admin-btn-secondary" title="Reset Filters" type="button">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="admin-card">
            <div class="admin-card-header flex items-center justify-between">
                <h5 class="admin-card-title">Gallery Items</h5>
                <span class="text-sm text-gray-500">{{ $galleries->total() }} items</span>
            </div>
            <div class="p-6">
                @if($galleries->count() > 0)
                    <div class="admin-grid admin-grid-cols-4">
                        @foreach($galleries as $gallery)
                            <div class="admin-card">
                                <div class="aspect-square relative overflow-hidden rounded-lg">
                                    <img src="{{ asset('storage/' . $gallery->file_path) }}"
                                         class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                         alt="{{ $gallery->title }}"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    
                                    <div class="hidden absolute inset-0 flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-image text-4xl mb-2"></i>
                                        <span>Image Not Found</span>
                                    </div>
                                    
                                    <!-- Featured Badge -->
                                    @if($gallery->featured)
                                        <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            <i class="fas fa-star mr-1"></i>Featured
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="p-4">
                                    <h6 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $gallery->title }}</h6>
                                    <div class="flex justify-between items-center text-xs text-gray-600 dark:text-gray-400 mb-2">
                                        <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 px-2 py-1 rounded-full">{{ $gallery->category }}</span>
                                        <span>{{ $gallery->created_at->format('M d') }}</span>
                                    </div>
                                    @if($gallery->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ Str::limit($gallery->description, 80) }}</p>
                                    @endif
                                    <div class="flex justify-end gap-2 mt-4">
                                        <button wire:click="edit({{ $gallery->id }})" class="admin-action-btn admin-action-btn-edit" type="button">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="delete({{ $gallery->id }})" class="admin-action-btn admin-action-btn-delete" onclick="return confirm('Are you sure you want to delete this gallery item?')" type="button">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="admin-empty-state">
                        <div class="admin-empty-state-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <h5 class="admin-empty-state-title">No Gallery Items Yet</h5>
                        <p class="admin-empty-state-description">
                            @if($search || $filterCategory)
                                No gallery items match your current filters. Try adjusting your search criteria.
                            @else
                                Transform your workspace into a visual showcase! Start building your professional gallery by adding stunning images of your completed projects and work.
                            @endif
                        </p>
                        @if(!$search && !$filterCategory)
                            <button wire:click="openCreateForm" class="admin-btn admin-btn-primary" type="button">
                                <i class="fas fa-plus mr-2"></i>Create Your First Gallery Item
                            </button>
                        @else
                            <button wire:click="resetFilters" class="admin-btn admin-btn-secondary" type="button">
                                <i class="fas fa-undo mr-2"></i>Clear All Filters
                            </button>
                        @endif
                    </div>
                @endif
                
                @if($galleries->hasPages())
                    <div class="flex justify-center mt-6">
                        {{ $galleries->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showCreateModal || showEditModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showCreateModal || showEditModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                 @click="showCreateModal = false; showEditModal = false"></div>

            <div x-show="showCreateModal || showEditModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    <div class="px-6 py-4 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $isEdit ? 'Edit Gallery Item' : 'Add New Gallery Item' }}</h3>
                            <button type="button"
                                    wire:click="{{ $isEdit ? 'closeEditForm' : 'closeCreateForm' }}"
                                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="admin-label">Title *</label>
                                <input type="text" class="admin-input @error('title') border-red-500 @enderror" wire:model="title" required>
                                @error('title') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">Category *</label>
                                <input type="text" class="admin-input @error('category') border-red-500 @enderror" wire:model="category" placeholder="e.g., Project A" required>
                                @error('category') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="admin-label">Description</label>
                            <textarea class="admin-input @error('description') border-red-500 @enderror" wire:model="description" rows="3" placeholder="Describe the image..."></textarea>
                            @error('description') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="admin-label">{{ $isEdit ? 'Change Image' : 'Image' }} {{ $isEdit ? '' : '*' }}</label>
                                <input type="file" class="admin-input @error('image') border-red-500 @enderror" wire:model="image" accept="image/*" {{ $isEdit ? '' : 'required' }}>
                                <div class="text-sm text-gray-500 mt-1">Max size: 2MB (jpeg, png, bmp, gif, svg, webp)</div>
                                @error('image') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                                <div wire:loading wire:target="image" class="text-sm text-blue-600 mt-2">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Uploading...
                                </div>
                            </div>
                            
                            @if($isEdit && $file_path)
                                <div>
                                    <label class="admin-label">Current Image</label>
                                    <div class="border rounded-lg overflow-hidden">
                                        <img src="{{ Storage::url($file_path) }}" alt="Current Image" class="w-full h-40 object-cover">
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" wire:model="featured">
                                <span class="ml-2 text-gray-700">Featured Image</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button"
                                wire:click="{{ $isEdit ? 'closeEditForm' : 'closeCreateForm' }}"
                                class="admin-btn admin-btn-secondary">
                            Cancel
                        </button>
                        <button type="submit"
                                class="admin-btn admin-btn-primary"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ $isEdit ? 'Update' : 'Save' }}</span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i>{{ $isEdit ? 'Updating...' : 'Saving...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>