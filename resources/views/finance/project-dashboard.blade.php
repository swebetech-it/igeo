<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Finance Dashboard: {{ $project->name }}</h1>
                    <p class="text-gray-600">Client: <span class="font-semibold">{{ $project->client->name ?? 'N/A' }}</span></p>
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
        <!-- Profit/Loss Card -->
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

    @livewire('project-finance-dashboard', ['project' => $project])
</div>
</x-app-layout>
