<div x-data="{
    activeTab: 'overview',
    chartInstances: {},
    init() {
        this.$nextTick(() => {
            this.initializeCharts();
        });
        
        // Reinitialize charts when Livewire updates
        this.$watch('$wire.dateRange', () => {
            this.initializeCharts();
        });
        
        this.$watch('$wire.selectedProject', () => {
            this.initializeCharts();
        });
    },
    initializeCharts() {
        // Destroy existing chart instances
        Object.values(this.chartInstances).forEach(chart => {
            if (chart) chart.destroy();
        });
        
        // Initialize all charts
        this.initRevenueForecastChart();
        this.initProjectStatusChart();
        this.initCashFlowChart();
        this.initProfitabilityHeatMap();
        this.initExpenseBreakdownChart();
        this.initResourceUtilizationChart();
    }
}" class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Unified Header -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                        <span class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center mr-4 shadow-lg">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </span>
                        Advanced Analytics Dashboard
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2 text-base">Data-driven insights with visual analytics for smarter decisions</p>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="$refresh" class="inline-flex items-center px-5 py-3 rounded-xl bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:from-gray-200 dark:hover:from-gray-600 text-gray-700 dark:text-gray-300 font-medium transition-all duration-200 shadow hover:shadow-md">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                    <select wire:model.live="dateRange" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 shadow-sm">
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="60">Last 60 Days</option>
                        <option value="90">Last 90 Days</option>
                        <option value="180">Last 6 Months</option>
                        <option value="365">Last Year</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Project Filter</label>
                    <select wire:model.live="selectedProject" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 shadow-sm">
                        <option value="all">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="inline-flex items-center w-full justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5" wire:click="$refresh">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex overflow-x-auto py-3 px-4 -mb-px">
                    <button @click="activeTab = 'overview'" :class="{'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-500': activeTab === 'overview'}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 whitespace-nowrap">
                        <i class="fas fa-th-large mr-2"></i>Overview
                    </button>
                    <button @click="activeTab = 'revenue'" :class="{'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-500': activeTab === 'revenue'}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 whitespace-nowrap ml-2">
                        <i class="fas fa-chart-line mr-2"></i>Revenue
                    </button>
                    <button @click="activeTab = 'profitability'" :class="{'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-500': activeTab === 'profitability'}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 whitespace-nowrap ml-2">
                        <i class="fas fa-chart-pie mr-2"></i>Profitability
                    </button>
                    <button @click="activeTab = 'resources'" :class="{'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-500': activeTab === 'resources'}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 whitespace-nowrap ml-2">
                        <i class="fas fa-users mr-2"></i>Resources
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" class="space-y-6">
                    <!-- Key Metrics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white transform transition-transform duration-200 hover:scale-[1.02]">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium mb-1">Total Revenue</p>
                                    <h3 class="text-2xl font-bold">${{ number_format($revenueData['total'], 2) }}</h3>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-blue-400/20 flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-blue-400/20">
                                <p class="text-blue-100 text-xs flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>12.5% from last period</span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white transform transition-transform duration-200 hover:scale-[1.02]">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-green-100 text-sm font-medium mb-1">Total Profit</p>
                                    <h3 class="text-2xl font-bold">${{ number_format($profitabilityData['totalProfit'], 2) }}</h3>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-green-400/20 flex items-center justify-center">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-green-400/20">
                                <p class="text-green-100 text-xs flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>8.3% from last period</span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white transform transition-transform duration-200 hover:scale-[1.02]">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-amber-100 text-sm font-medium mb-1">Avg Profit Margin</p>
                                    <h3 class="text-2xl font-bold">{{ $profitabilityData['avgMargin'] }}%</h3>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-amber-400/20 flex items-center justify-center">
                                    <i class="fas fa-percentage text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-amber-400/20">
                                <p class="text-amber-100 text-xs flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    <span>2.1% from last period</span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-2xl shadow-lg p-6 text-white transform transition-transform duration-200 hover:scale-[1.02]">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium mb-1">Employee Utilization</p>
                                    <h3 class="text-2xl font-bold">{{ $resourceUtilization['employeeUtilization'] }}%</h3>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-purple-400/20 flex items-center justify-center">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-purple-400/20">
                                <p class="text-purple-100 text-xs flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>5.7% from last period</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Revenue Trend Chart -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-chart-area text-emerald-500 mr-2"></i>Revenue Trend & Forecast
                                </h3>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        {{ abs($revenueForecast['growthRate'] ?? 0) }}% Growth
                                    </span>
                                </div>
                            </div>
                            <div class="h-80">
                                <canvas id="revenueForecastChart"></canvas>
                            </div>
                        </div>

                        <!-- Project Status Chart -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                                <i class="fas fa-project-diagram text-blue-500 mr-2"></i>Project Status Distribution
                            </h3>
                            <div class="h-80">
                                <canvas id="projectStatusChart"></canvas>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-6">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Active: {{ $projectMetrics['active'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Completed: {{ $projectMetrics['completed'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-amber-500 mr-2"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">On Hold: {{ $projectMetrics['onHold'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Delayed: {{ $projectMetrics['delayed'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Tab -->
                <div x-show="activeTab === 'revenue'" class="space-y-6">
                    <!-- Cash Flow Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Cash Flow Analysis
                        </h3>
                        <div class="h-96">
                            <canvas id="cashFlowChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Profitability Tab -->
                <div x-show="activeTab === 'profitability'" class="space-y-6">
                    <!-- Profitability Heat Map -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            <i class="fas fa-fire text-orange-500 mr-2"></i>Project Profitability Heat Map
                        </h3>
                        <div class="h-96">
                            <canvas id="profitabilityHeatMap"></canvas>
                        </div>
                    </div>

                    <!-- Expense Breakdown -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            <i class="fas fa-chart-pie text-purple-500 mr-2"></i>Expense Breakdown
                        </h3>
                        <div class="h-96">
                            <canvas id="expenseBreakdownChart"></canvas>
                        </div>
                    </div>

                    <!-- Detailed Profitability Table -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            <i class="fas fa-table text-blue-500 mr-2"></i>Detailed Project Profitability
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Revenue</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Expenses</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Profit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Margin %</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Budget</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($profitabilityData['projects'] as $project)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project['project'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $project['status'] === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                                    {{ $project['status'] === 'active' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                                    {{ $project['status'] === 'on_hold' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' : '' }}
                                                    {{ $project['status'] === 'delayed' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $project['status'])) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-medium">
                                                ${{ number_format($project['income'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 font-medium">
                                                ${{ number_format($project['expenses'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                                {{ $project['profit'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                ${{ number_format($project['profit'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $project['margin'] >= 20 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                                    {{ $project['margin'] >= 10 && $project['margin'] < 20 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' : '' }}
                                                    {{ $project['margin'] < 10 ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}">
                                                    {{ $project['margin'] }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                ${{ number_format($project['budget'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Resources Tab -->
                <div x-show="activeTab === 'resources'" class="space-y-6">
                    <!-- Resource Utilization Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            <i class="fas fa-users-cog text-indigo-500 mr-2"></i>Employee Resource Allocation
                        </h3>
                        <div class="h-96">
                            <canvas id="resourceUtilizationChart"></canvas>
                        </div>
                    </div>

                    <!-- Budget Utilization -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            <i class="fas fa-wallet text-purple-500 mr-2"></i>Budget Utilization
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Budget</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Spent</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Utilization</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($resourceUtilization['budgetUtilization'] as $budget)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $budget['project'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${{ number_format($budget['budget'], 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${{ number_format($budget['spent'], 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                                        <div class="h-2.5 rounded-full 
                                                            {{ $budget['utilization'] > 90 ? 'bg-red-500' : ($budget['utilization'] > 70 ? 'bg-amber-500' : 'bg-green-500') }}" 
                                                            style="width: {{ min($budget['utilization'], 100) }}%"></div>
                                                    </div>
                                                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $budget['utilization'] }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('livewire:init', function () {
    // Initialize Alpine.js data
    window.analyticsDashboard = {
        chartInstances: {},
        
        initRevenueForecastChart() {
            const ctx = document.getElementById('revenueForecastChart');
            if (!ctx) return;
            
            this.chartInstances.revenueForecast = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @js($revenueForecast['labels']),
                    datasets: [
                        {
                            label: 'Actual Revenue',
                            data: @js($revenueForecast['actual']),
                            borderColor: 'rgb(54, 162, 235)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Forecasted Revenue',
                            data: @js($revenueForecast['forecast']),
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.1)',
                            borderDash: [5, 5],
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        title: {
                            display: true,
                            text: 'Revenue Trend with 30-Day Forecast',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        },
        
        initProjectStatusChart() {
            const ctx = document.getElementById('projectStatusChart');
            if (!ctx) return;
            
            this.chartInstances.projectStatus = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @js($projectMetrics['statusDistribution']['labels']),
                    datasets: [{
                        data: @js($projectMetrics['statusDistribution']['data']),
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(255, 99, 132, 0.8)'
                        ],
                        borderColor: [
                            'rgb(54, 162, 235)',
                            'rgb(75, 192, 192)',
                            'rgb(255, 206, 86)',
                            'rgb(255, 99, 132)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        },
        
        initCashFlowChart() {
            const ctx = document.getElementById('cashFlowChart');
            if (!ctx) return;
            
            this.chartInstances.cashFlow = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @js($cashFlowData['labels']),
                    datasets: [
                        {
                            label: 'Income',
                            data: @js($cashFlowData['income']),
                            backgroundColor: 'rgba(75, 192, 192, 0.7)',
                            borderColor: 'rgb(75, 192, 192)',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Expenses',
                            data: @js($cashFlowData['expense']),
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Net Cash Flow',
                            data: @js($cashFlowData['net']),
                            type: 'line',
                            borderColor: 'rgb(255, 206, 86)',
                            backgroundColor: 'rgba(255, 206, 86, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        title: {
                            display: true,
                            text: 'Income vs Expenses Cash Flow',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        },
        
        initProfitabilityHeatMap() {
            const ctx = document.getElementById('profitabilityHeatMap');
            if (!ctx) return;
            
            const projects = @js($profitabilityData['projects']);
            const data = projects.map(p => p.margin);
            const labels = projects.map(p => p.project);
            
            this.chartInstances.profitabilityHeatMap = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Profit Margin %',
                        data: data,
                        backgroundColor: data.map(p => {
                            if (p >= 20) return 'rgba(75, 192, 192, 0.8)';
                            if (p >= 10) return 'rgba(255, 206, 86, 0.8)';
                            return 'rgba(255, 99, 132, 0.8)';
                        }),
                        borderColor: data.map(p => {
                            if (p >= 20) return 'rgb(75, 192, 192)';
                            if (p >= 10) return 'rgb(255, 206, 86)';
                            return 'rgb(255, 99, 132)';
                        }),
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Project Profit Margins (Green>20%, Yellow>10%, Red<10%)',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Margin: ' + context.parsed.x + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        },
        
        initExpenseBreakdownChart() {
            const ctx = document.getElementById('expenseBreakdownChart');
            if (!ctx) return;
            
            this.chartInstances.expenseBreakdown = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @js($expenseBreakdown['labels']),
                    datasets: [{
                        data: @js($expenseBreakdown['data']),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 206, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(153, 102, 255)',
                            'rgb(255, 159, 64)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += '$' + context.parsed.toLocaleString();
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        },
        
        initResourceUtilizationChart() {
            const ctx = document.getElementById('resourceUtilizationChart');
            if (!ctx) return;
            
            const resourceData = @js($resourceUtilization['projectAllocation']);
            const data = resourceData.map(r => r.employees);
            const labels = resourceData.map(r => r.project);
            
            this.chartInstances.resourceUtilization = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Employees Assigned',
                        data: data,
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                        borderColor: 'rgb(153, 102, 255)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Employee Distribution Across Projects',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    };
});

// Initialize charts when the page loads
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.analyticsDashboard !== 'undefined') {
        window.analyticsDashboard.initRevenueForecastChart();
        window.analyticsDashboard.initProjectStatusChart();
        window.analyticsDashboard.initCashFlowChart();
        window.analyticsDashboard.initProfitabilityHeatMap();
        window.analyticsDashboard.initExpenseBreakdownChart();
        window.analyticsDashboard.initResourceUtilizationChart();
    }
});
</script>
@endpush