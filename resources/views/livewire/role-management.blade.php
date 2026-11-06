<div>
    <!-- Page Header -->
    <x-page-header
        title="Role Management"
        subtitle="Manage user roles and permissions"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'fas fa-tachometer-alt'],
            ['label' => 'Role Management', 'icon' => 'fas fa-user-tag']
        ]"
    >
        <x-slot name="actions">
            <x-ui-button
                wire:click="openCreateForm"
                variant="primary"
                size="md"
            >
                <i class="fas fa-plus mr-2"></i>
                Add New Role
            </x-ui-button>
        </x-slot>
    </x-page-header>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <x-stats-card
            title="Total Roles"
            :value="$stats['total'] ?? 0"
            icon="fas fa-user-tag"
            color="blue"
        />

        <x-stats-card
            title="Roles with Permissions"
            :value="$stats['rolesWithPermissions'] ?? 0"
            icon="fas fa-key"
            color="green"
        />
    </div>

    <!-- Search -->
    <div class="mb-8 card-enhanced">
        <div class="card-enhanced-header">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Search Roles</h3>
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
                    <label class="form-label">Search Roles</label>
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
    <x-ui.modal
        :show="$showCreateForm"
        :title="$isEdit ? 'Edit Role' : 'Add New Role'"
        size="lg"
        icon="fas fa-user-tag"
    >
        <x-slot name="subtitle">
            {{ $isEdit ? 'Update role information' : 'Create a new role' }}
        </x-slot>

        <form wire:submit="save" class="space-y-6">
            <div class="form-group">
                <label class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" wire:model="name" class="form-control {{ $errors->has('name') ? 'form-control-error' : '' }}" placeholder="Enter role name">
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea wire:model="description" rows="3" class="form-control {{ $errors->has('description') ? 'form-control-error' : '' }}" placeholder="Enter role description"></textarea>
                @error('description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
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
                    {{ $isEdit ? 'Update Role' : 'Create Role' }}
                </x-ui-button>
            </div>
        </form>
    </x-ui.modal>

    <!-- Permissions Modal -->
    <x-ui.modal
        :show="$showPermissionsModal"
        title="Manage Permissions for {{ $name }}"
        size="xl"
        icon="fas fa-key"
    >
        <x-slot name="subtitle">
            Select the permissions this role should have
        </x-slot>

        <div class="max-h-96 overflow-y-auto pr-2">
            @if($permissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($permissions as $permission)
                        <div class="flex items-start p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center h-5">
                                <input id="permission-{{ $permission->id }}" wire:model.live="selectedPermissions" type="checkbox" value="{{ $permission->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="permission-{{ $permission->id }}" class="font-medium text-gray-700 dark:text-gray-300">{{ $permission->name }}</label>
                                @if($permission->description)
                                    <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $permission->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-key text-gray-300 text-3xl mb-3"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No permissions found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Permissions need to be created first.</p>
                </div>
            @endif
        </div>

        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
            <x-ui-button
                wire:click="$set('showPermissionsModal', false)"
                variant="secondary"
            >
                Cancel
            </x-ui-button>
            <x-ui-button
                wire:click="savePermissions"
                variant="primary"
            >
                Save Permissions
            </x-ui-button>
        </div>
    </x-ui.modal>

    <!-- Delete Confirmation Modal -->
    <x-ui.modal
        :show="$showDeleteConfirm"
        title="Delete Role"
        size="md"
        icon="fas fa-exclamation-triangle"
        :persistent="true"
    >
        <div class="text-center">
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Are you sure you want to delete this role? This action cannot be undone.
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
                Delete Role
            </x-ui-button>
        </div>
    </x-ui.modal>

    <!-- Roles Table -->
    <div class="card-enhanced">
        <div class="card-enhanced-header">
            <h3 class="text-lg font-semibold text-gray-900">Roles List</h3>
            <p class="text-sm text-gray-600 mt-1">Showing {{ $roles->count() }} of {{ $roles->total() }} roles</p>
        </div>
        <div class="card-enhanced-body">
            @if($roles->count() > 0)
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
                                    Permissions
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Employees
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($roles as $role)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $role->description ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $role->permissions->count() }} permissions
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $role->employees->count() }} employees
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button wire:click="managePermissions({{ $role->id }})" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <button wire:click="edit({{ $role->id }})" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $role->id }})" class="text-red-600 hover:text-red-900">
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
                    {{ $roles->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-user-tag text-gray-300 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No roles found</h3>
                    <p class="text-gray-500">Get started by adding a new role.</p>
                    <div class="mt-6">
                        <x-ui-button
                            wire:click="openCreateForm"
                            variant="primary"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Add Role
                        </x-ui-button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>