<div>
    <!-- Page Header -->
    <x-page-header
        title="Employee Management"
        subtitle="Manage your employee database and user accounts"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'fas fa-tachometer-alt'],
            ['label' => 'Employee Management', 'icon' => 'fas fa-users']
        ]"
    >
        <x-slot name="actions">
            <x-ui-button
                wire:click="openCreateForm"
                variant="primary"
                size="md"
            >
                <i class="fas fa-plus mr-2"></i>
                Add New Employee
            </x-ui-button>
        </x-slot>
    </x-page-header>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-stats-card
            title="Total Employees"
            :value="$stats['total'] ?? 0"
            icon="fas fa-users"
            color="blue"
        />

        <x-stats-card
            title="Active Employees"
            :value="$stats['active'] ?? 0"
            icon="fas fa-user-check"
            color="green"
        />

        <x-stats-card
            title="Inactive Employees"
            :value="$stats['inactive'] ?? 0"
            icon="fas fa-user-slash"
            color="gray"
        />
    </div>

    <!-- Search and Filters -->
    <div class="mb-8 card-enhanced">
        <div class="card-enhanced-header">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Search & Filter</h3>
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
                    <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('departmentFilter', '')" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <i class="fas fa-undo mr-1"></i>
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>
        <div class="card-enhanced-body">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="col-span-1 md:col-span-2">
                    <label class="form-label">Search Employees</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email, or position..." class="form-control">
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
                <div class="form-group">
                    <label class="form-label">Status Filter</label>
                    <select wire:model.live="statusFilter" class="form-control">
                        <option value="">All Status</option>
                        <option value="active">🟢 Active</option>
                        <option value="inactive">🔴 Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <select wire:model.live="departmentFilter" class="form-control">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if($search || $statusFilter || $departmentFilter)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-600">Active filters:</span>
                        @if($search)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Search: "{{ $search }}"
                                <button wire:click="$set('search', '')" class="ml-2 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </span>
                        @endif
                        @if($statusFilter)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Status: {{ ucfirst($statusFilter) }}
                                <button wire:click="$set('statusFilter', '')" class="ml-2 text-green-600 hover:text-green-800">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </span>
                        @endif
                        @if($departmentFilter)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Department: {{ $departmentFilter }}
                                <button wire:click="$set('departmentFilter', '')" class="ml-2 text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </span>
                        @endif
                        <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('departmentFilter', '')" class="text-sm text-gray-500 hover:text-gray-700 underline">
                            Clear all
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Create/Edit Form Modal -->
    @if($showCreateForm)
    <div class="modal-overlay" wire:key="employee-modal">
        <div class="modal-container">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $isEdit ? 'Edit Employee' : 'Add New Employee' }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $isEdit ? 'Update employee information' : 'Create a new employee profile' }}</p>
                </div>
                <button wire:click="closeCreateForm" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-2 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label">First Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="first_name" class="form-control {{ $errors->has('first_name') ? 'form-control-error' : '' }}" placeholder="Enter first name">
                        @error('first_name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="last_name" class="form-control {{ $errors->has('last_name') ? 'form-control-error' : '' }}" placeholder="Enter last name">
                        @error('last_name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" class="form-control {{ $errors->has('email') ? 'form-control-error' : '' }}" placeholder="Enter email address">
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" wire:model="phone" class="form-control" placeholder="Enter phone number">
                        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Position</label>
                        <input type="text" wire:model="position" class="form-control" placeholder="Enter position">
                        @error('position') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" wire:model="department" class="form-control" placeholder="Enter department">
                        @error('department') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Role <span class="text-red-500">*</span></label>
                        <select wire:model="role_id" class="form-control {{ $errors->has('role_id') ? 'form-control-error' : '' }}">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hire Date</label>
                        <input type="date" wire:model="hire_date" class="form-control" placeholder="Select hire date">
                        @error('hire_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status <span class="text-red-500">*</span></label>
                        <select wire:model="status" class="form-control {{ $errors->has('status') ? 'form-control-error' : '' }}">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if(!$isEdit)
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center">
                        <input type="checkbox" wire:model.live="createUserAccount" id="createUserAccount" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="createUserAccount" class="ml-2 block text-sm text-gray-900">
                            Create user account for this employee
                        </label>
                    </div>

                    @if($createUserAccount)
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 pl-6 border-l-2 border-blue-200">
                        <div class="form-group">
                            <label class="form-label">Username <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="userName" class="form-control {{ $errors->has('userName') ? 'form-control-error' : '' }}" placeholder="Enter username">
                            @error('userName') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password <span class="text-red-500">*</span></label>
                            <input type="password" wire:model="userPassword" class="form-control {{ $errors->has('userPassword') ? 'form-control-error' : '' }}" placeholder="Enter password">
                            @error('userPassword') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group md:col-span-2">
                            <label class="form-label">Confirm Password <span class="text-red-500">*</span></label>
                            <input type="password" wire:model="userPasswordConfirmation" class="form-control {{ $errors->has('userPassword') ? 'form-control-error' : '' }}" placeholder="Confirm password">
                            @error('userPassword') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    @endif
                </div>
                @endif

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
                        {{ $isEdit ? 'Update Employee' : 'Create Employee' }}
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
                <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Employee</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Are you sure you want to delete this employee? This action cannot be undone.
                    @if($deleteEmployeeId && ($employee = \App\Models\Employee::find($deleteEmployeeId)) && $employee->user)
                        <br><br><strong>Note:</strong> This will also delete the associated user account.
                    @endif
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
                    Delete Employee
                </x-ui-button>
            </div>
        </div>
    </div>
    @endif

    <!-- Employees Table -->
    <div class="card-enhanced">
        <div class="card-enhanced-header">
            <h3 class="text-lg font-semibold text-gray-900">Employee List</h3>
            <p class="text-sm text-gray-600 mt-1">Showing {{ $employees->count() }} of {{ $employees->total() }} employees</p>
        </div>
        <div class="card-enhanced-body">
            @if($employees->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('first_name')" class="flex items-center space-x-1">
                                        <span>Employee</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('position')" class="flex items-center space-x-1">
                                        <span>Position</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('department')" class="flex items-center space-x-1">
                                        <span>Department</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('role_id')" class="flex items-center space-x-1">
                                        <span>Role</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('status')" class="flex items-center space-x-1">
                                        <span>Status</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('hire_date')" class="flex items-center space-x-1">
                                        <span>Hire Date</span>
                                        <i class="fas fa-sort text-xs"></i>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($employees as $employee)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-blue-800 font-medium">
                                                        {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $employee->full_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $employee->email }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $employee->phone }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $employee->position ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $employee->department ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $employee->role->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($employee->status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button wire:click="edit({{ $employee->id }})" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $employee->id }})" class="text-red-600 hover:text-red-900">
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
                    {{ $employees->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No employees found</h3>
                    <p class="text-gray-500">Get started by adding a new employee.</p>
                    <div class="mt-6">
                        <x-ui-button
                            wire:click="openCreateForm"
                            variant="primary"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Add Employee
                        </x-ui-button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>