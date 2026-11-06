<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4">
        <!-- Include unified styles -->
        @include('components.admin.ui-consistency-styles')
        
        <!-- Header Section -->
        <x-admin.page-header 
            title="Finance Dashboard"
            subtitle="Comprehensive financial overview and analytics"
            icon="fas fa-chart-line"
            color="green"
        >
            <x-slot name="actions">
                <button class="admin-btn admin-btn-secondary">
                    <i class="fas fa-download mr-2"></i>Export PDF
                </button>
                <button class="admin-btn admin-btn-secondary">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
            </x-slot>
        </x-admin.page-header>

        <!-- Filters Section -->
        <div class="admin-filters-card">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="admin-label">Project Filter</label>
                    <select class="admin-select" wire:model.live="selectedProject">
                        <option value="">All Projects</option>
                        @foreach($projectsList as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label">Time Period</label>
                    <select class="admin-select" wire:model.live="selectedPeriod">
                        <option value="custom">Custom Range</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
                <div>
                    <label class="admin-label">From Date</label>
                    <input type="date" class="admin-input" wire:model.live="dateFrom">
                </div>
                <div>
                    <label class="admin-label">To Date</label>
                    <input type="date" class="admin-input" wire:model.live="dateTo">
                </div>
            </div>
        </div>

        <!-- Financial Overview Cards -->
        <div class="admin-grid admin-grid-cols-4 mb-8">
            <div class="admin-card bg-gradient-to-br from-emerald-500 to-emerald-600 text-white">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h6 class="text-sm font-medium opacity-90 uppercase tracking-wider mb-2">Total Income</h6>
                            <div class="text-3xl font-bold mb-2">${{ number_format($totalIncome, 2) }}</div>
                            <div class="text-sm">
                                <i class="fas fa-trending-up mr-1"></i>
                                Income Stream
                            </div>
                        </div>
                        <i class="fas fa-arrow-up text-4xl opacity-30"></i>
                    </div>
                </div>
            </div>
            
            <div class="admin-card bg-gradient-to-br from-red-500 to-red-600 text-white">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h6 class="text-sm font-medium opacity-90 uppercase tracking-wider mb-2">Total Expenses</h6>
                            <div class="text-3xl font-bold mb-2">${{ number_format($totalExpenses, 2) }}</div>
                            <div class="text-sm">
                                <i class="fas fa-receipt mr-1"></i>
                                All Expenditures
                            </div>
                        </div>
                        <i class="fas fa-arrow-down text-4xl opacity-30"></i>
                    </div>
                </div>
            </div>
            
            <div class="admin-card bg-gradient-to-br {{ $profit >= 0 ? 'from-blue-500 to-blue-600' : 'from-red-500 to-red-600' }} text-white">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h6 class="text-sm font-medium opacity-90 uppercase tracking-wider mb-2">{{ $profit >= 0 ? 'Net Profit' : 'Net Loss' }}</h6>
                            <div class="text-3xl font-bold mb-2">${{ number_format(abs($profit), 2) }}</div>
                            <div class="text-sm">
                                <i class="fas {{ $profit >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                {{ $profit >= 0 ? 'Positive' : 'Negative' }} Margin
                            </div>
                        </div>
                        <i class="fas {{ $profit >= 0 ? 'fa-chart-line' : 'fa-chart-line-down' }} text-4xl opacity-30"></i>
                    </div>
                </div>
            </div>
            
            <div class="admin-card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h6 class="text-sm font-medium opacity-90 uppercase tracking-wider mb-2">Profit Margin</h6>
                            <div class="text-3xl font-bold mb-2">{{ number_format($profitMargin, 1) }}%</div>
                            <div class="text-sm">
                                Performance Ratio
                            </div>
                        </div>
                        <i class="fas fa-percentage text-4xl opacity-30"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Metrics -->
        <div class="admin-grid admin-grid-cols-2 mb-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="admin-card-title">
                        <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                        Pending Items
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div>
                            <h4 class="text-3xl font-bold text-amber-600 dark:text-amber-400">${{ number_format($pendingIncome, 2) }}</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Pending Income</p>
                        </div>
                        <div>
                            <h4 class="text-3xl font-bold text-red-600 dark:text-red-400">${{ number_format($pendingExpenses, 2) }}</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Pending Expenses</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="admin-card-title">
                        <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                        Expense Breakdown
                    </h5>
                </div>
                <div class="p-6">
                    @if($expensesByCategory->count() > 0)
                        <div class="space-y-3">
                            @foreach($expensesByCategory->take(4) as $category => $amount)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-600 last:border-0">
                                    <span class="text-gray-700 dark:text-gray-300">{{ ucwords(str_replace('_', ' ', $category)) }}</span>
                                    <strong class="text-gray-900 dark:text-gray-100">${{ number_format($amount, 2) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center">No expenses in selected period</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Project Performance -->
        @if($projects->count() > 0)
        <div class="admin-card mb-8">
            <div class="admin-card-header">
                <h5 class="admin-card-title">
                    <i class="fas fa-project-diagram text-emerald-600 mr-2"></i>
                    Project Performance
                </h5>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="text-left">Project</th>
                            <th class="text-left">Budget</th>
                            <th class="text-left">Income</th>
                            <th class="text-left">Expenses</th>
                            <th class="text-left">Profit/Loss</th>
                            <th class="text-left">Margin %</th>
                            <th class="text-left">Progress</th>
                            <th class="text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td class="font-medium text-gray-900 dark:text-gray-100">{{ $project['name'] }}</td>
                                <td>
                                    @if($project['budget'])
                                        ${{ number_format($project['budget'], 2) }}
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Not set</span>
                                    @endif
                                </td>
                                <td class="text-emerald-600 dark:text-emerald-400 font-medium">
                                    ${{ number_format($project['total_income'], 2) }}
                                </td>
                                <td class="text-red-600 dark:text-red-400 font-medium">
                                    ${{ number_format($project['total_expenses'], 2) }}
                                </td>
                                <td class="{{ $project['profit_loss'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} font-medium">
                                    ${{ number_format($project['profit_loss'], 2) }}
                                </td>
                                <td class="{{ $project['profit_margin'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} font-medium">
                                    {{ number_format($project['profit_margin'], 1) }}%
                                </td>
                                <td>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="h-2 bg-blue-600 rounded-full transition-all duration-300"
                                             style="width: {{ $project['completion_percentage'] }}%">
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        {{ round($project['completion_percentage']) }}%
                                    </div>
                                </td>
                                <td>
                                    <span class="admin-badge {{ $project['status'] === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100' : ($project['status'] === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                        {{ ucwords($project['status']) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Recent Transactions -->
        <div class="admin-grid admin-grid-cols-2">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="admin-card-title">
                        <i class="fas fa-money-bill-wave text-emerald-600 mr-2"></i>
                        Recent Income
                    </h5>
                </div>
                <div class="p-6">
                    @if($recentIncome->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentIncome as $income)
                                <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-600 last:border-0">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $income->name }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $income->project->name }} - {{ $income->date->format('M d, Y') }}</div>
                                    </div>
                                    <div class="text-emerald-600 dark:text-emerald-400 font-semibold text-right">
                                        ${{ number_format($income->received_amount, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="admin-empty-state">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center text-gray-500 mb-4 mx-auto">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <p class="text-gray-500 text-center">No recent income</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="admin-card-title">
                        <i class="fas fa-receipt text-red-600 mr-2"></i>
                        Recent Expenses
                    </h5>
                </div>
                <div class="p-6">
                    @if($recentExpenses->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentExpenses as $expense)
                                <div class="flex justify-between items-start py-3 border-b border-gray-100 dark:border-gray-600 last:border-0">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $expense->name }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $expense->project->name }} - {{ $expense->date->format('M d, Y') }}</div>
                                    </div>
                                    <div class="text-red-600 dark:text-red-400 font-semibold text-right">
                                        ${{ number_format($expense->amount, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="admin-empty-state">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center text-gray-500 mb-4 mx-auto">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <p class="text-gray-500 text-center">No recent expenses</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>