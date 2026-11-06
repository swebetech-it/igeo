<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100">
        <!-- Header Section -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Employee Management System</h1>
                        <p class="mt-1 text-gray-500">Manage employees, roles, and permissions</p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-circle text-green-400 mr-1"></i>
                            System Online
                        </span>
                        <span class="text-sm text-gray-500">Last updated: {{ now()->format('M j, Y g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Employee Management Section -->
            <div class="bg-white rounded-xl shadow-sm border mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-2 mr-3">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Employee Management</h3>
                            <p class="text-gray-500 text-sm">Manage employee database and user accounts</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @livewire('employee-management')
                </div>
            </div>

            <!-- Role Management Section -->
            <div class="bg-white rounded-xl shadow-sm border mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-2 mr-3">
                            <i class="fas fa-user-tag text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Role Management</h3>
                            <p class="text-gray-500 text-sm">Manage user roles and permissions</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @livewire('role-management')
                </div>
            </div>

            <!-- Permission Management Section -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg p-2 mr-3">
                            <i class="fas fa-key text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Permission Management</h3>
                            <p class="text-gray-500 text-sm">Manage system permissions</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @livewire('permission-management')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>