<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Finance Dashboard</h1>
                    <p class="text-gray-600">Project: <span class="font-semibold">{{ $project->name }}</span> &mdash; Client: <span class="font-semibold">{{ $project->client->name ?? 'N/A' }}</span></p>
                </div>
            </div>
            <a href="{{ route('admin.finance.projects') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium"><i class="fas fa-arrow-left mr-2"></i>Back to Projects</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Budget</p>
                    <p class="text-3xl font-bold text-gray-800">${{ number_format($project->budget, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Expenses</p>
                    <p class="text-3xl font-bold text-gray-800">${{ number_format($project->total_expenses, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Income</p>
                    <p class="text-3xl font-bold text-gray-800">${{ number_format($project->total_income, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cash-register text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Profit / Loss</p>
                    <p class="text-3xl font-bold {{ $project->profit_loss >= 0 ? 'text-green-700' : 'text-red-700' }}">${{ number_format($project->profit_loss, 2) }}</p>
                    <span class="text-xs {{ $project->profit_loss >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $project->profit_loss >= 0 ? 'Profit' : 'Loss' }}</span>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-balance-scale text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Expenses -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-receipt text-yellow-500 mr-2"></i> Expenses
                </h3>
                <button class="bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2" wire:click="openExpenseModal">
                    <i class="fas fa-plus"></i>
                    <span>Add Expense</span>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-3 py-2 text-left">Date</th>
                            <th class="px-3 py-2 text-left">Name</th>
                            <th class="px-3 py-2 text-right">Amount</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->expenses as $expense)
                            <tr>
                                <td class="px-3 py-2">{{ $expense->date ? $expense->date->format('M d, Y') : '-' }}</td>
                                <td class="px-3 py-2">{{ $expense->name }}</td>
                                <td class="px-3 py-2 text-right">${{ number_format($expense->amount, 2) }}</td>
                                <td class="px-3 py-2">{{ ucfirst($expense->status) }}</td>
                                <td class="px-3 py-2 text-center">
                                    <button class="text-blue-600 hover:text-blue-800 mr-2" title="Edit" wire:click="editExpense({{ $expense->id }})"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-800" title="Delete" wire:click="confirmDeleteExpense({{ $expense->id }})"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-gray-400 py-4">No expenses recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Expense Modal -->
            @if($showExpenseModal)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
                    <div class="bg-white rounded-xl p-6 w-full max-w-md">
                        <h4 class="text-lg font-bold mb-4">{{ $editingExpense ? 'Edit Expense' : 'Add Expense' }}</h4>
                        <form wire:submit.prevent="saveExpense">
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Name</label>
                                <input type="text" class="form-input w-full" wire:model.defer="expenseForm.name" required />
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Amount</label>
                                <input type="number" class="form-input w-full" wire:model.defer="expenseForm.amount" required />
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Date</label>
                                <input type="date" class="form-input w-full" wire:model.defer="expenseForm.date" />
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Category</label>
                                <select class="form-select w-full" wire:model.defer="expenseForm.category" required>
                                    <option value="materials">Materials</option>
                                    <option value="labor">Labor</option>
                                    <option value="transport">Transport</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Status</label>
                                <select class="form-select w-full" wire:model.defer="expenseForm.status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                            <div class="flex justify-end space-x-2 mt-4">
                                <button type="button" class="px-4 py-2 bg-gray-200 rounded" wire:click="resetExpenseForm">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            <!-- Delete Expense Modal -->
            @if($editingExpense && !$showExpenseModal)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
                    <div class="bg-white rounded-xl p-6 w-full max-w-sm text-center">
                        <h4 class="text-lg font-bold mb-4">Delete Expense?</h4>
                        <p>Are you sure you want to delete this expense?</p>
                        <div class="flex justify-center space-x-2 mt-4">
                            <button type="button" class="px-4 py-2 bg-gray-200 rounded" wire:click="resetExpenseForm">Cancel</button>
                            <button type="button" class="px-4 py-2 bg-red-600 text-white rounded" wire:click="deleteExpense">Delete</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- Income -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-cash-register text-green-500 mr-2"></i> Income
                </h3>
                <button class="bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2" wire:click="openIncomeModal">
                    <i class="fas fa-plus"></i>
                    <span>Add Income</span>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-3 py-2 text-left">Date</th>
                            <th class="px-3 py-2 text-left">Name</th>
                            <th class="px-3 py-2 text-right">Amount</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->incomes as $income)
                            <tr>
                                <td class="px-3 py-2">{{ $income->date ? $income->date->format('M d, Y') : '-' }}</td>
                                <td class="px-3 py-2">{{ $income->name }}</td>
                                <td class="px-3 py-2 text-right">${{ number_format($income->amount, 2) }}</td>
                                <td class="px-3 py-2">{{ ucfirst($income->status) }}</td>
                                <td class="px-3 py-2 text-center">
                                    <button class="text-blue-600 hover:text-blue-800 mr-2" title="Edit" wire:click="editIncome({{ $income->id }})"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-600 hover:text-red-800" title="Delete" wire:click="confirmDeleteIncome({{ $income->id }})"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-gray-400 py-4">No income recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Income Modal -->
            @if($showIncomeModal)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
                    <div class="bg-white rounded-xl p-6 w-full max-w-md">
                        <h4 class="text-lg font-bold mb-4">{{ $editingIncome ? 'Edit Income' : 'Add Income' }}</h4>
                        <form wire:submit.prevent="saveIncome">
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Name</label>
                                <input type="text" class="form-input w-full" wire:model.defer="incomeForm.name" required />
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Amount</label>
                                <input type="number" class="form-input w-full" wire:model.defer="incomeForm.amount" required />
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Date</label>
                                <input type="date" class="form-input w-full" wire:model.defer="incomeForm.date" />
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Type</label>
                                <select class="form-select w-full" wire:model.defer="incomeForm.type" required>
                                    <option value="initial_deposit">Initial Deposit</option>
                                    <option value="milestone_payment">Milestone Payment</option>
                                    <option value="progress_payment">Progress Payment</option>
                                    <option value="final_payment">Final Payment</option>
                                    <option value="change_order">Change Order</option>
                                    <option value="bonus">Bonus Payment</option>
                                    <option value="retention_release">Retention Release</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium">Status</label>
                                <select class="form-select w-full" wire:model.defer="incomeForm.status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                            <div class="flex justify-end space-x-2 mt-4">
                                <button type="button" class="px-4 py-2 bg-gray-200 rounded" wire:click="resetIncomeForm">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            <!-- Delete Income Modal -->
            @if($editingIncome && !$showIncomeModal)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
                    <div class="bg-white rounded-xl p-6 w-full max-w-sm text-center">
                        <h4 class="text-lg font-bold mb-4">Delete Income?</h4>
                        <p>Are you sure you want to delete this income?</p>
                        <div class="flex justify-center space-x-2 mt-4">
                            <button type="button" class="px-4 py-2 bg-gray-200 rounded" wire:click="resetIncomeForm">Cancel</button>
                            <button type="button" class="px-4 py-2 bg-red-600 text-white rounded" wire:click="deleteIncome">Delete</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
