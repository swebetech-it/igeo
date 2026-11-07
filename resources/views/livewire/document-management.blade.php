<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4">
        <!-- Include unified styles -->
        @include('components.admin.ui-consistency-styles')
        
        <!-- Header Section -->
        <x-admin.page-header 
            title="Document Management"
            subtitle="Manage contracts, certificates, reports and other project documents"
            icon="fas fa-file-alt"
            color="blue"
        >
            <x-slot name="actions">
                <button class="admin-btn admin-btn-primary" wire:click="openCreateForm">
                    <i class="fas fa-plus mr-2"></i>Add New Document
                </button>
            </x-slot>
        </x-admin.page-header>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filters Section -->
        <div class="admin-filters-card">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="admin-label">Search Documents</label>
                    <div class="admin-input-group">
                        <div class="admin-input-icon">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.live="search" class="admin-input admin-input-with-icon" placeholder="Search by title or description...">
                    </div>
                </div>
                <div>
                    <label class="admin-label">Category Filter</label>
                    <select wire:model.live="filterCategory" class="admin-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFilters" class="admin-btn admin-btn-secondary">
                        <i class="fas fa-undo mr-2"></i>Reset Filters
                    </button>
                </div>
            </div>
            @if($search || $filterCategory)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center gap-2 text-sm">
                    <span class="text-gray-600 font-medium">Active filters:</span>
                    @if($search)
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800">
                            Search: "{{ $search }}"
                            <button wire:click="$set('search', '')" class="ml-2 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </span>
                    @endif
                    @if($filterCategory)
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 text-purple-800">
                            Category: {{ $filterCategory }}
                            <button wire:click="$set('filterCategory', '')" class="ml-2 text-purple-600 hover:text-purple-800">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </span>
                    @endif
                    <button wire:click="resetFilters" class="text-gray-500 hover:text-gray-700 underline">Clear all</button>
                </div>
            @endif
        </div>

        <!-- Documents Grid -->
        <div class="admin-card">
            <div class="admin-card-header flex items-center justify-between">
                <h5 class="admin-card-title">Document Library</h5>
                <span class="text-sm text-gray-500">{{ $documents->total() }} documents</span>
            </div>
            <div class="p-6">
                @if($documents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Visibility</th>
                                    <th>Uploaded</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow mr-3">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $doc->title }}</div>
                                                    @if($doc->description)
                                                        <div class="text-sm text-gray-500">{{ Str::limit($doc->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="admin-badge bg-blue-100 text-blue-800">{{ $doc->category }}</span>
                                        </td>
                                        <td>
                                            <span class="admin-badge bg-gray-100 text-gray-800">{{ strtoupper($doc->file_type) }}</span>
                                        </td>
                                        <td>
                                            @if($doc->is_public)
                                                <span class="admin-badge admin-badge-approved">Public</span>
                                            @else
                                                <span class="admin-badge admin-badge-pending">Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-sm text-gray-500">{{ $doc->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td>
                                            <div class="flex">
                                                <a href="{{ $doc->file_path }}" target="_blank" class="admin-action-btn admin-action-btn-view" title="View Document">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ $doc->file_path }}" download="{{ $doc->title }}" class="admin-action-btn admin-action-btn-secondary ml-1" title="Download Document">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button wire:click="edit({{ $doc->id }})" class="admin-action-btn admin-action-btn-edit ml-1">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button wire:click="delete({{ $doc->id }})" class="admin-action-btn admin-action-btn-delete ml-1" onclick="return confirm('Delete this document?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="admin-empty-state">
                        <div class="admin-empty-state-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h5 class="admin-empty-state-title">No Documents Found</h5>
                        <p class="admin-empty-state-description">
                            @if($search || $filterCategory)
                                No documents match your current filters. Try adjusting your search criteria.
                            @else
                                Get started by uploading your first document to build your document library.
                            @endif
                        </p>
                        @if(!$search && !$filterCategory)
                            <button wire:click="openCreateForm" class="admin-btn admin-btn-primary">
                                <i class="fas fa-plus mr-2"></i>Add Your First Document
                            </button>
                        @else
                            <button wire:click="resetFilters" class="admin-btn admin-btn-secondary">
                                <i class="fas fa-undo mr-2"></i>Clear All Filters
                            </button>
                        @endif
                    </div>
                @endif
                
                @if($documents->hasPages())
                    <div class="flex justify-center mt-6">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <x-admin.modal
            :show="$showCreateForm"
            :title="$isEdit ? 'Edit Document' : 'Add New Document'"
            size="lg"
            icon="fas fa-file-upload"
            wire:model="showCreateForm"
        >
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="admin-label">Title *</label>
                        <input type="text" class="admin-input @error('title') border-red-500 @enderror" wire:model="title" placeholder="e.g. Project Contract" required>
                        @error('title') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="admin-label">Category *</label>
                        <select class="admin-select @error('category') border-red-500 @enderror" wire:model="category" required>
                            <option value="">Select Category</option>
                            <option value="Contracts">Contracts</option>
                            <option value="Certificates">Certificates</option>
                            <option value="Reports">Reports</option>
                            <option value="Plans">Plans & Blueprints</option>
                            <option value="Permits">Permits & Licenses</option>
                            <option value="Proposals">Proposals</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('category') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="admin-label">Description</label>
                    <textarea class="admin-input @error('description') border-red-500 @enderror" wire:model="description" rows="3" placeholder="Brief description of the document..."></textarea>
                    @error('description') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="admin-label">Document File {{ $isEdit ? '(optional)' : '*' }}</label>
                        <input type="file" class="admin-input @error('document') border-red-500 @enderror" wire:model="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png">
                        @error('document') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        <div class="text-sm text-gray-500 mt-1">Max 10MB. Supported: PDF, DOC(X), XLS(X), PPT(X), TXT, JPG, PNG</div>
                        <div wire:loading wire:target="document" class="text-sm text-blue-600 mt-2">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Uploading...
                        </div>
                    </div>
                    
                    @if($isEdit && $file_path && !$document)
                        <div>
                            <label class="admin-label">Current Document</label>
                            <div class="flex items-center gap-3">
                                <a href="{{ $file_path }}" target="_blank" class="admin-btn admin-btn-secondary">
                                    <i class="fas fa-eye mr-2"></i>View Document ({{ strtoupper($file_type) }})
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" wire:model="is_public">
                        <span class="ml-2 text-gray-700">
                            <span class="font-medium">Public Document</span>
                            <span class="block text-sm text-gray-500">Allow public access to this document</span>
                        </span>
                    </label>
                </div>
                
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                    <button type="button" class="admin-btn admin-btn-secondary" wire:click="closeCreateForm">Cancel</button>
                    <button type="submit" class="admin-btn admin-btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $isEdit ? 'Update Document' : 'Save Document' }}</span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin mr-2"></i>{{ $isEdit ? 'Updating...' : 'Saving...' }}
                        </span>
                    </button>
                </div>
            </form>
        </x-admin.modal>
    </div>
</div>