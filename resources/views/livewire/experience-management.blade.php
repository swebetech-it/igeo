<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4">
        <!-- Include unified styles -->
        @include('components.admin.ui-consistency-styles')
        
        <!-- Header Section -->
        <x-admin.page-header 
            title="Experience Management"
            subtitle="Showcase your professional experience and company achievements"
            icon="fas fa-briefcase"
            color="blue"
        >
            <x-slot name="actions">
                <button class="admin-btn admin-btn-primary" wire:click="openCreateForm">
                    <i class="fas fa-plus mr-2"></i>Add Experience
                </button>
            </x-slot>
        </x-admin.page-header>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Search Section -->
        <div class="admin-filters-card">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1">
                    <label class="admin-label">Search Experiences</label>
                    <div class="admin-input-group">
                        <div class="admin-input-icon">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.live="search" class="admin-input admin-input-with-icon" placeholder="Search experiences...">
                    </div>
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFilters" class="admin-btn admin-btn-secondary">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Experiences Grid -->
        <div class="admin-card">
            <div class="admin-card-header flex items-center justify-between">
                <h5 class="admin-card-title">Experience Timeline</h5>
                <span class="text-sm text-gray-500">{{ $experiences->total() }} experiences</span>
            </div>
            <div class="p-6">
                @if($experiences->count() > 0)
                    <div class="admin-grid admin-grid-cols-3">
                        @foreach($experiences as $experience)
                            <div class="admin-card">
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                            {{ $experience->year }}
                                        </span>
                                        <div class="flex gap-1">
                                            <button wire:click="edit({{ $experience->id }})" class="admin-action-btn admin-action-btn-edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="delete({{ $experience->id }})" class="admin-action-btn admin-action-btn-delete" wire:confirm="Are you sure you want to delete this experience?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <h6 class="font-bold text-gray-900 dark:text-white mb-2">{{ $experience->title }}</h6>
                                    <span class="inline-block bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full mb-3">
                                        {{ $experience->category }}
                                    </span>
                                    @if($experience->description)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ Str::limit($experience->description, 100) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="admin-empty-state">
                        <div class="admin-empty-state-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h5 class="admin-empty-state-title">No Experience Records Found</h5>
                        <p class="admin-empty-state-description">
                            @if($search)
                                No experiences match your search criteria. Try adjusting your search terms.
                            @else
                                Start documenting your company's journey! Add milestones, project completions, awards, and achievements to showcase your growth and success over the years.
                            @endif
                        </p>
                        @if(!$search)
                            <button wire:click="openCreateForm" class="admin-btn admin-btn-primary">
                                <i class="fas fa-plus mr-2"></i>Add Your First Experience
                            </button>
                        @else
                            <button wire:click="resetFilters" class="admin-btn admin-btn-secondary">
                                <i class="fas fa-undo mr-2"></i>Clear Search
                            </button>
                        @endif
                    </div>
                @endif
                
                @if($experiences->hasPages())
                    <div class="flex justify-center mt-6">
                        {{ $experiences->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <x-admin.modal
            :show="$showCreateForm"
            :title="$isEdit ? 'Edit Experience' : 'Add New Experience'"
            size="lg"
            icon="fas fa-briefcase"
            wire:model="showCreateForm"
        >
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="admin-label">Title *</label>
                        <input type="text" class="admin-input @error('title') border-red-500 @enderror" wire:model="title" placeholder="Enter experience title" required>
                        @error('title') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="admin-label">Year *</label>
                        <input type="number" class="admin-input @error('year') border-red-500 @enderror" wire:model="year" min="1990" max="2030" placeholder="2024" required>
                        @error('year') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="admin-label">Category *</label>
                    <select class="admin-select @error('category') border-red-500 @enderror" wire:model="category" required>
                        <option value="">Select Category</option>
                        <option value="Project">Project Completion</option>
                        <option value="Award">Award & Recognition</option>
                        <option value="Milestone">Company Milestone</option>
                        <option value="Certification">Certification</option>
                        <option value="Partnership">Partnership</option>
                    </select>
                    @error('category') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="mt-4">
                    <label class="admin-label">Description</label>
                    <textarea class="admin-input @error('description') border-red-500 @enderror" wire:model="description" rows="4" placeholder="Describe the experience, achievement, or milestone..."></textarea>
                    @error('description') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                    <button type="button" class="admin-btn admin-btn-secondary" wire:click="closeCreateForm">Cancel</button>
                    <button type="submit" class="admin-btn admin-btn-primary">
                        <i class="fas {{ $isEdit ? 'fa-save' : 'fa-plus' }} mr-2"></i>
                        {{ $isEdit ? 'Update Experience' : 'Save Experience' }}
                    </button>
                </div>
            </form>
        </x-admin.modal>
    </div>
</div>
