<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Reports Dashboard</h1>
                        <p class="text-gray-600">Construction Company Management System</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Report Navigation -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.reports.project') }}" 
                   class="px-6 py-3 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg font-medium transition duration-200 flex items-center">
                    <i class="fas fa-project-diagram mr-2"></i>Project Reports
                </a>
                <a href="{{ route('admin.reports.financial') }}" 
                   class="px-6 py-3 bg-green-100 hover:bg-green-200 text-green-800 rounded-lg font-medium transition duration-200 flex items-center">
                    <i class="fas fa-money-bill-wave mr-2"></i>Financial Reports
                </a>
                <a href="{{ route('admin.reports.resource') }}" 
                   class="px-6 py-3 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 rounded-lg font-medium transition duration-200 flex items-center">
                    <i class="fas fa-tools mr-2"></i>Resource Reports
                </a>
                <a href="{{ route('admin.reports.executive') }}" 
                   class="px-6 py-3 bg-purple-100 hover:bg-purple-200 text-purple-800 rounded-lg font-medium transition duration-200 flex items-center">
                    <i class="fas fa-chart-line mr-2"></i>Executive Reports
                </a>
            </div>
        </div>

        <!-- Report Content -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            @if(request()->routeIs('admin.reports.project'))
                @livewire('project-reports')
            @elseif(request()->routeIs('admin.reports.financial'))
                @livewire('financial-reports')
            @elseif(request()->routeIs('admin.reports.resource'))
                @livewire('resource-reports')
            @elseif(request()->routeIs('admin.reports.executive'))
                @livewire('executive-reports')
            @else
                <div class="text-center py-12">
                    <i class="fas fa-chart-pie text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Select a Report Type</h3>
                    <p class="text-gray-600">Choose from the navigation above to view different reports</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>