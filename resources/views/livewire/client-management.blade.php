<div x-data="{
    showDeleteModal: $wire.entangle('showDeleteConfirm'),
    deleteId: $wire.entangle('deleteClientId'),
    showCreateModal: $wire.entangle('showCreateForm'),
    showEditModal: $wire.entangle('showEditForm')
}" class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4">
        <!-- Include unified styles -->
        @include('components.admin.ui-consistency-styles')
        
        <!-- Header Section -->
        <x-admin.page-header 
            title="Client Management"
            subtitle="Manage your client database and showcase your partnerships"
            icon="fas fa-users"
            color="blue"
        >
            <x-slot name="actions">
                <button wire:click="openCreateForm" type="button"
                        class="admin-btn admin-btn-primary">
                    <i class="fas fa-plus mr-2"></i>Add New Client
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
                    <label class="admin-label">Search Clients</label>
                    <div class="admin-input-group">
                        <div class="admin-input-icon">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.live="search" class="admin-input admin-input-with-icon" placeholder="Search by name, email, or company...">
                    </div>
                </div>
                <div>
                    <label class="admin-label">Filter by Status</label>
                    <select wire:model.live="statusFilter" class="admin-select">
                        <option value="">All Status</option>
                        <option value="active">🟢 Active</option>
                        <option value="potential">🟡 Potential</option>
                        <option value="inactive">⚫ Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="admin-label">Filter by Type</label>
                    <select wire:model.live="typeFilter" class="admin-select">
                        <option value="">All Types</option>
                        <option value="residential">🏠 Residential</option>
                        <option value="commercial">🏢 Commercial</option>
                        <option value="industrial">🏭 Industrial</option>
                        <option value="infrastructure">🚧 Infrastructure</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Client Grid -->
        <div class="admin-card">
            <div class="admin-card-header flex items-center justify-between">
                <h5 class="admin-card-title">Client Directory</h5>
                <span class="text-sm text-gray-500">{{ $clients->total() }} clients</span>
            </div>
            <div class="p-6">
                @if($clients->count() > 0)
                    <div class="admin-grid admin-grid-cols-4">
                        @foreach($clients as $client)
                            <div class="admin-card">
                                <div class="aspect-square relative overflow-hidden rounded-lg flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-gray-700 dark:to-gray-800">
                                    <div class="flex flex-col items-center justify-center w-full h-full p-4">
                                        <!-- Client Initials as Logo -->
                                        <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold mb-3">
                                            {{ substr($client->name, 0, 2) }}
                                        </div>
                                        
                                        <h6 class="font-semibold text-gray-900 dark:text-gray-100 text-center">{{ $client->name }}</h6>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-2">{{ $client->company ?? 'Individual' }}</p>
                                        
                                        <div class="flex justify-between items-center text-xs text-gray-600 dark:text-gray-400 mb-2 w-full px-2">
                                            <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-2 py-1 rounded-full truncate max-w-[100px]">{{ $client->project_type ? ucfirst($client->project_type) : 'N/A' }}</span>
                                            <span>{{ $client->created_at->format('M d') }}</span>
                                        </div>
                                        
                                        <!-- Public Display Badge -->
                                        @if($client->is_public)
                                            <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                <i class="fas fa-eye mr-1"></i>Public
                                            </div>
                                        @endif
                                        
                                        <div class="flex justify-end gap-2 mt-4 w-full px-2">
                                            <button wire:click="edit({{ $client->id }})" class="admin-action-btn admin-action-btn-edit" type="button">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $client->id }})" class="admin-action-btn admin-action-btn-delete" onclick="return confirm('Are you sure you want to delete this client?')" type="button">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="admin-empty-state">
                        <div class="admin-empty-state-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="admin-empty-state-title">No Clients Yet</h5>
                        <p class="admin-empty-state-description">
                            @if($search || $statusFilter || $typeFilter)
                                No clients match your current filters. Try adjusting your search criteria.
                            @else
                                Build your client directory! Start by adding your first client to showcase your partnerships.
                            @endif
                        </p>
                        @if(!$search && !$statusFilter && !$typeFilter)
                            <button wire:click="openCreateForm" class="admin-btn admin-btn-primary" type="button">
                                <i class="fas fa-plus mr-2"></i>Create Your First Client
                            </button>
                        @else
                            <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('typeFilter', '')" class="admin-btn admin-btn-secondary" type="button">
                                <i class="fas fa-undo mr-2"></i>Clear All Filters
                            </button>
                        @endif
                    </div>
                @endif
                
                @if($clients->hasPages())
                    <div class="flex justify-center mt-6">
                        {{ $clients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create/Edit Form Modal -->
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
                            <h3 class="text-lg font-semibold text-gray-900">{{ $isEdit ? 'Edit Client' : 'Add New Client' }}</h3>
                            <button type="button"
                                    wire:click="closeCreateForm"
                                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $isEdit ? 'Update client information' : 'Create a new client profile' }}
                        </p>
                    </div>

                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="name" class="form-control {{ $errors->has('name') ? 'form-control-error' : '' }}" placeholder="Enter client name">
                                @error('name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email <span class="text-red-500">*</span></label>
                                <input type="email" wire:model="email" class="form-control {{ $errors->has('email') ? 'form-control-error' : '' }}" placeholder="Enter client email">
                                @error('email') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" wire:model="phone" class="form-control" placeholder="Enter phone number">
                                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Company</label>
                                <input type="text" wire:model="company" class="form-control" placeholder="Enter company name">
                                @error('company') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Project Type</label>
                                <select wire:model="project_type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="residential">🏠 Residential</option>
                                    <option value="commercial">🏢 Commercial</option>
                                    <option value="industrial">🏭 Industrial</option>
                                    <option value="infrastructure">🚧 Infrastructure</option>
                                </select>
                                @error('project_type') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Status <span class="text-red-500">*</span></label>
                                <select wire:model="status" class="form-control {{ $errors->has('status') ? 'form-control-error' : '' }}">
                                    <option value="active">🟢 Active</option>
                                    <option value="potential">🟡 Potential</option>
                                    <option value="inactive">⚫ Inactive</option>
                                </select>
                                @error('status') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notes</label>
                            <textarea wire:model="notes" rows="4" class="form-control" placeholder="Any additional notes about this client..."></textarea>
                            @error('notes') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_public" id="is_public" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <label for="is_public" class="ml-2 block text-sm text-gray-700">Display on Public Website</label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Check this box to make this client visible on the public website</p>
                            @error('is_public') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" wire:click="closeCreateForm" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn-primary bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800">
                            <span wire:loading.remove>
                                <i class="fas fa-{{ $isEdit ? 'edit' : 'plus' }} mr-2"></i>
                                {{ $isEdit ? 'Update Client' : 'Create Client' }}
                            </span>
                            <span wire:loading class="flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                {{ $isEdit ? 'Updating...' : 'Creating...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Client Modal -->
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
                            <h3 class="text-lg font-semibold text-gray-900">Edit Client</h3>
                            <button type="button"
                                    wire:click="closeEditForm"
                                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Update client information
                        </p>
                    </div>

                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="name" class="form-control {{ $errors->has('name') ? 'form-control-error' : '' }}" placeholder="Enter client name">
                                @error('name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email <span class="text-red-500">*</span></label>
                                <input type="email" wire:model="email" class="form-control {{ $errors->has('email') ? 'form-control-error' : '' }}" placeholder="Enter client email">
                                @error('email') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" wire:model="phone" class="form-control" placeholder="Enter phone number">
                                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Company</label>
                                <input type="text" wire:model="company" class="form-control" placeholder="Enter company name">
                                @error('company') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Project Type</label>
                                <select wire:model="project_type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="residential">🏠 Residential</option>
                                    <option value="commercial">🏢 Commercial</option>
                                    <option value="industrial">🏭 Industrial</option>
                                    <option value="infrastructure">🚧 Infrastructure</option>
                                </select>
                                @error('project_type') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Status <span class="text-red-500">*</span></label>
                                <select wire:model="status" class="form-control {{ $errors->has('status') ? 'form-control-error' : '' }}">
                                    <option value="active">🟢 Active</option>
                                    <option value="potential">🟡 Potential</option>
                                    <option value="inactive">⚫ Inactive</option>
                                </select>
                                @error('status') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notes</label>
                            <textarea wire:model="notes" rows="4" class="form-control" placeholder="Any additional notes about this client..."></textarea>
                            @error('notes') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="is_public" id="is_public_edit" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                <label for="is_public_edit" class="ml-2 block text-sm text-gray-700">Display on Public Website</label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Check this box to make this client visible on the public website</p>
                            @error('is_public') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" wire:click="closeEditForm" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn-primary bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800">
                            <span wire:loading.remove>
                                <i class="fas fa-edit mr-2"></i>
                                Update Client
                            </span>
                            <span wire:loading class="flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Client</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this client? This action cannot be undone and will also remove all associated projects and data.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="delete" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Delete Client
                    </button>
                    <button wire:click="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>