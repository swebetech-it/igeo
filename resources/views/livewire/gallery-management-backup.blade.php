<div x-data="{
    showDeleteModal: false,
    deleteId: null,
    showCreateModal: $wire.entangle('showCreateForm'),
    showEditModal: $wire.entangle('showEditForm')
}" class="space-y-6">

    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gallery Management</h1>
                <p class="text-gray-600 mt-1">Manage your project gallery and showcase your work</p>
            </div>
            <button wire:click="openCreateForm"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Add Image
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Gallery</label>
                <div class="relative">
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search by title or description..."
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
                <select wire:model.live="filterCategory"
                        class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="resetFilters"
                        class="px-4 py-3 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-undo mr-2"></i>
                    Reset
                </button>
            </div>
        </div>
    </div>
                                        </div>

    <!-- Gallery Grid -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Gallery Items ({{ $galleries->total() }})</h2>
        </div>

        @if($galleries->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">
                @foreach($galleries as $gallery)
                    <div class="group relative bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Image Container -->
                        <div class="relative aspect-w-16 aspect-h-12 overflow-hidden">
                            <img src="{{ $gallery->image_url }}"
                                 alt="{{ $gallery->title }}"
                                 class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"
                                 onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">

                            <!-- Overlay Actions -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center space-x-2 opacity-0 group-hover:opacity-100">
                                <button wire:click="edit({{ $gallery->id }})"
                                        class="p-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="deleteId = {{ $gallery->id }}; showDeleteModal = true"
                                        class="p-2 bg-white text-red-600 rounded-lg hover:bg-red-50 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Featured Badge -->
                            @if($gallery->featured)
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>
                                        Featured
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $gallery->title }}</h3>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $gallery->category }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $gallery->created_at->format('M d') }}
                                </span>
                            </div>

                            @if($gallery->description)
                                <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ Str::limit($gallery->description, 80) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-images text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No gallery items found</h3>
                <p class="text-gray-500 mb-6">
                    @if($search || $filterCategory)
                        No items match your current search criteria.
                    @else
                        Start building your gallery by adding your first image to showcase your projects.
                    @endif
                </p>
                @if(!$search && !$filterCategory)
                    <button wire:click="openCreateForm"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Add Your First Image
                    </button>
                @endif
            </div>
        @endif

        @if($galleries->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>

    <!-- Create Gallery Modal -->
    <div x-show="showCreateModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showCreateModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                 @click="showCreateModal = false"></div>

            <div x-show="showCreateModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                <form wire:submit="save">
                    <div class="px-6 py-4 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Add New Gallery Item</h3>
                            <button type="button"
                                    wire:click="closeCreateForm"
                                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                <input type="text"
                                       wire:model="title"
                                       placeholder="Enter gallery item title..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                <select wire:model="category"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Category</option>
                                    <option value="Residential">Residential</option>
                                    <option value="Commercial">Commercial</option>
                                    <option value="Industrial">Industrial</option>
                                    <option value="Infrastructure">Infrastructure</option>
                                </select>
                                @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description"
                                      rows="3"
                                      placeholder="Brief description of this image..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image *</label>
                            <input type="file"
                                   wire:model="image"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                            <!-- Image Preview -->
                            @if ($image)
                                <div class="mt-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                </div>
                            @endif

                            <!-- Upload Progress -->
                            <div wire:loading wire:target="image" class="mt-3">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                                    <span class="text-blue-700 text-sm">Uploading image...</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       wire:model="featured"
                                       id="featured_create"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="featured_create" class="ml-2 block text-sm text-gray-900">
                                    Mark as featured item (will be highlighted in gallery)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button"
                                wire:click="closeCreateForm"
                                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="save,image"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50">
                            <span wire:loading.remove wire:target="save,image">Add to Gallery</span>
                            <span wire:loading wire:target="save,image" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Gallery Modal -->
    <div x-show="showEditModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showEditModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                 @click="showEditModal = false"></div>

            <div x-show="showEditModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                <form wire:submit="save">
                    <div class="px-6 py-4 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Edit Gallery Item</h3>
                            <button type="button"
                                    wire:click="closeEditForm"
                                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                <input type="text"
                                       wire:model="title"
                                       placeholder="Enter gallery item title..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                <select wire:model="category"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Category</option>
                                    <option value="Residential">Residential</option>
                                    <option value="Commercial">Commercial</option>
                                    <option value="Industrial">Industrial</option>
                                    <option value="Infrastructure">Infrastructure</option>
                                </select>
                                @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description"
                                      rows="3"
                                      placeholder="Brief description of this image..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                            <input type="file"
                                   wire:model="image"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Leave blank to keep current image</p>
                            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                            <!-- Current Image -->
                            @if ($isEdit && $image_url && !$image)
                                <div class="mt-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Current Image:</p>
                                    <img src="{{ $image_url }}" alt="Current" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                </div>
                            @endif

                            <!-- New Image Preview -->
                            @if ($image)
                                <div class="mt-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                </div>
                            @endif

                            <!-- Upload Progress -->
                            <div wire:loading wire:target="image" class="mt-3">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                                    <span class="text-blue-700 text-sm">Uploading image...</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       wire:model="featured"
                                       id="featured_edit"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="featured_edit" class="ml-2 block text-sm text-gray-900">
                                    Mark as featured item (will be highlighted in gallery)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button"
                                wire:click="closeEditForm"
                                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="save,image"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50">
                            <span wire:loading.remove wire:target="save,image">Update Item</span>
                            <span wire:loading wire:target="save,image" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Updating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Gallery Item</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this gallery item? This action cannot be undone and will also delete the associated image file.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="$wire.delete(deleteId); showDeleteModal = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Delete
                    </button>
                    <button @click="showDeleteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Featured</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="featured" id="featured">
                                        <label class="form-check-label" for="featured">
                                            Mark as featured item
                                        </label>
                                    </div>
                                    @error('featured') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save,image">
                                <span wire:loading.remove wire:target="save,image">{{ $isEdit ? 'Update' : 'Save' }}</span>
                                <span wire:loading wire:target="save,image">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    {{ $isEdit ? 'Updating...' : 'Saving...' }}
                                </span>
                            </button>
                            @if($isEdit)
                                <button type="button" class="btn btn-secondary" wire:click="resetForm" wire:loading.attr="disabled" wire:target="save,image">Cancel</button>
                            @endif
                        </div>

                        <!-- Upload Progress -->
                        <div wire:loading wire:target="image" class="mb-3">
                            <div class="alert alert-info">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Uploading image...
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Gallery Items</h5>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model.live="search" placeholder="Search gallery items...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" wire:model.live="filterCategory">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" wire:click="resetFilters">Reset</button>
                        </div>
                    </div>

                    <!-- Gallery Grid -->
                    <div class="row">
                        @forelse($galleries as $gallery)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                        <img src="{{ $gallery->image_url }}"
                                             class="card-img-top"
                                             style="width: 100%; height: 100%; object-fit: cover;"
                                             alt="{{ $gallery->title }}"
                                             onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">{{ $gallery->title }}</h6>
                                        <span class="badge bg-primary mb-2">{{ $gallery->category }}</span>
                                        @if($gallery->featured)
                                            <span class="badge bg-warning mb-2">Featured</span>
                                        @endif
                                        <p class="card-text small text-muted flex-grow-1">
                                            {{ Str::limit($gallery->description, 100) }}
                                        </p>
                                        <div class="mt-auto">
                                            <button class="btn btn-sm btn-outline-primary"
                                                    wire:click="edit({{ $gallery->id }})">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                    wire:click="delete({{ $gallery->id }})"
                                                    wire:confirm="Are you sure you want to delete this gallery item?">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-4">
                                    <p class="text-muted">No gallery items found.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $galleries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
