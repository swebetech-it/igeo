<div x-data="{
    showCreateModal: $wire.entangle('showCreateForm'),
    showEditModal: $wire.entangle('showEditForm'),
    showDeleteModal: false,
    deleteId: null
}" class="min-h-screen bg-gradient-to-br from-orange-50 to-orange-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4">
        <!-- Include unified styles -->
        @include('components.admin.ui-consistency-styles')
        
        <!-- Header Section -->
        <x-admin.page-header 
            title="Expense Management"
            subtitle="Track and manage company expenses and expenditures"
            icon="fas fa-receipt"
            color="red"
        >
            <x-slot name="actions">
                <button class="admin-btn admin-btn-primary" wire:click="openCreateForm" type="button">
                    <i class="fas fa-plus mr-2"></i>Add Expense
                </button>
                <button class="admin-btn admin-btn-secondary" type="button">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </x-slot>
        </x-admin.page-header>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="admin-grid admin-grid-cols-4 mb-8">
            <div class="admin-card">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                            <i class="fas fa-receipt text-red-600 dark:text-red-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Amount</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">${{ number_format($stats['total_amount'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-card">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                            <i class="fas fa-clock text-amber-600 dark:text-amber-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-card">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                            <i class="fas fa-check text-emerald-600 dark:text-emerald-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['approved'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-card">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                            <i class="fas fa-credit-card text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Paid Amount</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">${{ number_format($stats['pending_amount'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="admin-filters-card">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="admin-label">Search Expenses</label>
                    <div class="admin-input-group">
                        <div class="admin-input-icon">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.live="search" class="admin-input admin-input-with-icon" placeholder="Search expenses...">
                    </div>
                </div>
                <div>
                    <label class="admin-label">Category</label>
                    <select wire:model.live="categoryFilter" class="admin-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label">Status</label>
                    <select wire:model.live="statusFilter" class="admin-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label">Project</label>
                    <select wire:model.live="projectFilter" class="admin-select">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button wire:click="resetFilters" class="admin-btn admin-btn-secondary" type="button">
                    <i class="fas fa-undo mr-1"></i>Reset Filters
                </button>
            </div>
        </div>

        <!-- Expenses Table -->
        <div class="admin-card">
            <div class="admin-card-header flex items-center justify-between">
                <h5 class="admin-card-title">Expenses List</h5>
                <span class="text-sm text-gray-500">{{ $expenses->total() }} expenses</span>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Project</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Receipt</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>
                                    <div class="font-medium">{{ $expense->date->format('M d, Y') }}</div>
                                    <small class="text-gray-500">{{ $expense->date->format('l') }}</small>
                                </td>
                                <td>
                                    <div class="font-bold">{{ $expense->name }}</div>
                                    @if($expense->vendor)
                                        <small class="text-gray-500">{{ $expense->vendor }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-medium">{{ $expense->project->name }}</div>
                                    <small class="text-gray-500">{{ $expense->project->client->name }}</small>
                                </td>
                                <td>
                                    @php
                                        $rawCategory = $expense->category;
                                        $normalizedCategory = strtolower($rawCategory);
                                        if ($normalizedCategory === 'transport') { $normalizedCategory = 'transportation'; }
                                        if ($normalizedCategory === 'material') { $normalizedCategory = 'materials'; }
                                    @endphp
                                    <span class="admin-badge bg-gray-100 text-gray-800 border">{{ $categories[$normalizedCategory] ?? $rawCategory }}</span>
                                </td>
                                <td>
                                    <span class="font-bold text-red-600">${{ number_format($expense->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="admin-badge admin-badge-{{ $expense->status }}">
                                        {{ $statuses[$expense->status] }}
                                    </span>
                                </td>
                                <td>
                                    @if($expense->receipt_file)
                                        <a href="{{ Storage::url($expense->receipt_file) }}" target="_blank" class="admin-action-btn admin-action-btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    @else
                                        <span class="text-gray-500">No receipt</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex">
                                        @if($expense->status === 'pending')
                                            <button wire:click="approveExpense({{ $expense->id }})" class="admin-action-btn admin-action-btn-success" title="Approve" type="button">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button wire:click="openEditForm({{ $expense->id }})" class="admin-action-btn admin-action-btn-edit" type="button">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="deleteId = {{ $expense->id }}; showDeleteModal = true" class="admin-action-btn admin-action-btn-delete" type="button">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="admin-empty-state">
                                        <div class="admin-empty-state-icon">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <h5 class="admin-empty-state-title">No Expenses Found</h5>
                                        <p class="admin-empty-state-description">
                                            @if($search || $categoryFilter || $statusFilter || $projectFilter)
                                                No expenses match your current filters. Try adjusting your search criteria.
                                            @else
                                                Get started by adding your first expense to track project costs.
                                            @endif
                                        </p>
                                        @if(!$search && !$categoryFilter && !$statusFilter && !$projectFilter)
                                            <button wire:click="openCreateForm" class="admin-btn admin-btn-primary" type="button">
                                                <i class="fas fa-plus mr-2"></i>Create Your First Expense
                                            </button>
                                        @else
                                            <button wire:click="resetFilters" class="admin-btn admin-btn-secondary" type="button">
                                                <i class="fas fa-undo mr-2"></i>Clear All Filters
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($expenses->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-center">
                        {{ $expenses->links() }}
                    </div>
                </div>
            @endif
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

                <form wire:submit.prevent="{{ $isEdit ? 'updateExpense' : 'createExpense' }}" enctype="multipart/form-data">
                    <div class="px-6 py-4 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $isEdit ? 'Edit Expense' : 'Create New Expense' }}</h3>
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
                                <label class="admin-label">Project *</label>
                                <select class="admin-select @error('project_id') border-red-500 @enderror" wire:model="project_id">
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }} - {{ $project->client->name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">Date *</label>
                                <input type="date" class="admin-input @error('date') border-red-500 @enderror" wire:model="date">
                                @error('date') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="admin-label">Expense Name *</label>
                                <input type="text" class="admin-input @error('name') border-red-500 @enderror" wire:model="name" placeholder="e.g., Office supplies, Equipment rental">
                                @error('name') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">Amount *</label>
                                <input type="number" step="0.01" class="admin-input @error('amount') border-red-500 @enderror" wire:model="amount">
                                @error('amount') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="admin-label">Category *</label>
                                <select class="admin-select @error('category') border-red-500 @enderror" wire:model="category">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('category') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">Payment Method</label>
                                <select class="admin-select @error('payment_method') border-red-500 @enderror" wire:model="payment_method">
                                    <option value="">Select Method</option>
                                    @foreach($paymentMethods as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('payment_method') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">Status *</label>
                                <select class="admin-select @error('status') border-red-500 @enderror" wire:model="status">
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="admin-label">Vendor/Supplier</label>
                                <input type="text" class="admin-input @error('vendor') border-red-500 @enderror" wire:model="vendor">
                                @error('vendor') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">Invoice Number</label>
                                <input type="text" class="admin-input @error('invoice_number') border-red-500 @enderror" wire:model="invoice_number">
                                @error('invoice_number') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        @if($isEdit && $editingExpense && $editingExpense->receipt_file)
                            <div>
                                <label class="admin-label">Current Receipt</label>
                                <div class="flex items-center gap-3">
                                    <a href="{{ Storage::url($editingExpense->receipt_file) }}" target="_blank" class="admin-btn admin-btn-secondary">
                                        <i class="fas fa-eye mr-2"></i>View Current Receipt
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="admin-label">{{ $isEdit && $editingExpense && $editingExpense->receipt_file ? 'Replace Receipt/Invoice File' : 'Receipt/Invoice File' }}</label>
                            <input type="file" class="admin-input @error('receipt_file') border-red-500 @enderror" wire:model="receipt_file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <div class="text-sm text-gray-500 mt-1">Supported formats: JPG, PNG, PDF, DOC, DOCX (Max: 2MB)</div>
                            @error('receipt_file') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="receipt_file" class="text-sm text-blue-600 mt-2">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Uploading...
                            </div>
                        </div>

                        <div>
                            <label class="admin-label">Description</label>
                            <textarea class="admin-input @error('description') border-red-500 @enderror" wire:model="description" rows="2"></textarea>
                            @error('description') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="admin-label">Notes</label>
                            <textarea class="admin-input @error('notes') border-red-500 @enderror" wire:model="notes" rows="2"></textarea>
                            @error('notes') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
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
                            <span wire:loading.remove>{{ $isEdit ? 'Update Expense' : 'Create Expense' }}</span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i>{{ $isEdit ? 'Updating...' : 'Creating...' }}
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Expense</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this expense? This action cannot be undone. This will permanently delete the expense record.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="$wire.deleteExpense(); showDeleteModal = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Delete Expense
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