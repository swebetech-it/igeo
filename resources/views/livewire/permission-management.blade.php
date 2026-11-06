<div>
    <!-- Page Header -->
    <x-page-header
        title="Permission Management"
        subtitle="Manage system permissions"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'fas fa-tachometer-alt'],
            ['label' => 'Permission Management', 'icon' => 'fas fa-key']
        ]"
    >
        <x-slot name="actions">
            <x-ui-button
                wire:click="openCreateForm"
                variant="primary"
                size="md"
            >
                <i class="fas fa-plus mr-2"></i>
                Add New Permission
            </x-ui-button>
        </x-slot>
    </x-page-header>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <x-stats-card
            title="Total Permissions"
            :value="$stats['total'] ?? 0"
            icon="fas fa-key"
            color="blue"
        />

        <x-stats-card
            title="Permissions with Roles"
            :value="$stats['permissionsWithRoles'] ?? 0"
            icon="fas fa-user-tag"
            color="green"
        />
    </div>

    <!-- Search -->
    <div class="mb-8 card-enhanced">
        <div class="card-enhanced-header">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Search Permissions</h3>
                <div class="flex items-center space-x-4 mt-4 lg:mt-0">
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>Per page:</span>
                        <select wire:model.live="perPage" class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <button wire:click="$set('search', '')" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <i class="fas fa-undo mr-1"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>
        <div class="card-enhanced-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-1">
                    <label class="form-label">Search Permissions</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or description..." class="form-control">
                        <div class="absolute left-4 top-3.5">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        @if($search)
                            <button wire:click="$set('search', '')" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Form Modal -->
    @if($showCreateForm)
    <div class="modal-overlay" wire:key="permission-modal">
        <div class="modal-container">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $isEdit ? 'Edit Permission' : 'Add New Permission' }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $isEdit ? 'Update permission information' : 'Create a new permission' }}</p>
                </div>
                <button wire:click="closeCreateForm" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form wire:submit="save" class="space-y-6">
                <div class="form-group">
                    <label class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" class="form-control {{ $errors->has('name') ? 'form-control-error' : '' }}" placeholder="Enter permission name">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea wire:model="description" rows="3" class="form-control {{ $errors->has('description') ? 'form-control-error' : '' }}" placeholder="Enter permission description"></textarea>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <x-ui-button
                        wire:click="closeCreateForm"
                        variant="secondary"
                        type="button"
                    >
                        Cancel
                    </x-ui-button>
                    <x-ui-button
                        variant="primary"
                        type="submit"
                        :loading="$isLoading"
                    >
                        {{ $isEdit ? 'Update Permission' : 'Create Permission' }}
                    </x-ui-button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm)
    <div class="modal-overlay">
        <div class="modal-container max-w-md">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Permission</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Are you sure you want to delete this permission? This action cannot be undone.
                </p>
            </div>
            <div class="mt-6 flex justify-center space-x-3">
                <x-ui-button
                    wire:click="cancelDelete"
                    variant="secondary"
                >
                    Cancel
                </x-ui-button>
                <x-ui-button
                    wire:click="delete"
                    variant="danger"
                >
                    Delete Permission
                </x-ui-button>
            </div>
        </div>
    </div>
    @endif

    <!-- Permissions Table -->
    <div class="card-enhanced">
        <div class="card-enhanced-header">
            <h3 class="text-lg font-semibold text-gray-900">Permissions List</h3>
            <p class="text-sm text-gray-600 mt-1">Showing {{ $permissions->count() }} of {{ $permissions->total() }} permissions</p>
        </div>
        <div class="card-enhanced-body">
            @if($permissions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('name')" class="flex items-center space-x-1">
                                        <span>Name</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Roles
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($permissions as $permission)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $permission->description ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $permission->roles->count() }} roles
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button wire:click="edit({{ $permission->id }})" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $permission->id }})" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $permissions->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-key text-gray-300 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No permissions found</h3>
                    <p class="text-gray-500">Get started by adding a new permission.</p>
                    <div class="mt-6">
                        <x-ui-button
                            wire:click="openCreateForm"
                            variant="primary"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Add Permission
                        </x-ui-button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>