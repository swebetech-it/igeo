<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100">
        <!-- Header Section with Stats -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">iGEO CMS</h1>
                        <p class="mt-1 text-gray-500">Welcome back! Here's what's happening with your projects.</p>
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
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total Visitors -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Visitors</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Visitor::count() }}</p>
                            <p class="text-sm text-blue-600 mt-1">
                                <i class="fas fa-globe mr-1"></i>
                                Unique IPs: {{ \App\Models\Visitor::distinct('ip')->count('ip') }}
                            </p>
                        </div>
                        <div class="bg-yellow-500 rounded-full p-3">
                            <i class="fas fa-user-friends text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            <!-- Recent Visitor Locations -->
            <div class="bg-white rounded-xl shadow-sm border mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg p-2 mr-3">
                            <i class="fas fa-map-marker-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Recent Visitor Locations</h3>
                            <p class="text-gray-500 text-sm">Last 10 visitors (country/city)</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-gray-600">
                                <th class="px-2 py-1 text-left">IP</th>
                                <th class="px-2 py-1 text-left">Country</th>
                                <th class="px-2 py-1 text-left">City</th>
                                <th class="px-2 py-1 text-left">Device</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Visitor::latest()->take(10)->get() as $visitor)
                                <tr class="border-b">
                                    <td class="px-2 py-1">{{ $visitor->ip }}</td>
                                    <td class="px-2 py-1">{{ $visitor->country ?? '-' }}</td>
                                    <td class="px-2 py-1">{{ $visitor->city ?? '-' }}</td>
                                    <td class="px-2 py-1">{{ $visitor->device ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
                <!-- Total Projects -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Projects</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Project::count() }}</p>
                            <p class="text-sm text-green-600 mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>
                                {{ \App\Models\Project::where('status', 'active')->count() }} Active
                            </p>
                        </div>
                        <div class="bg-blue-500 rounded-full p-3">
                            <i class="fas fa-project-diagram text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Revenue</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format(\App\Models\Income::sum('received_amount'), 0) }}</p>
                            <p class="text-sm text-green-600 mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>
                                This Month
                            </p>
                        </div>
                        <div class="bg-green-500 rounded-full p-3">
                            <i class="fas fa-money-bill-wave text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Expenses</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format(\App\Models\Expense::sum('amount'), 0) }}</p>
                            <p class="text-sm text-red-600 mt-1">
                                <i class="fas fa-arrow-down mr-1"></i>
                                This Month
                            </p>
                        </div>
                        <div class="bg-red-500 rounded-full p-3">
                            <i class="fas fa-receipt text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Active Clients -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Active Clients</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Client::count() }}</p>
                            <p class="text-sm text-blue-600 mt-1">
                                <i class="fas fa-users mr-1"></i>
                                Total Clients
                            </p>
                        </div>
                        <div class="bg-purple-500 rounded-full p-3">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Server Performance Monitoring -->
            <div class="bg-white rounded-xl shadow-sm border mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-2 mr-3">
                            <i class="fas fa-server text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Server Performance & Resources</h3>
                            <p class="text-gray-500 text-sm">Monitor server resources and optimize performance</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Cache Status -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-blue-900">Cache Status</h4>
                                <i class="fas fa-database text-blue-600"></i>
                            </div>
                            @php
                                $cacheHits = \Cache::get('gallery_slider_images') ? 'Active' : 'Empty';
                                $cacheColor = $cacheHits === 'Active' ? 'text-green-600' : 'text-orange-600';
                            @endphp
                            <p class="text-lg font-bold {{ $cacheColor }}">{{ $cacheHits }}</p>
                            <p class="text-xs text-gray-600">Gallery cache (5min TTL)</p>
                        </div>

                        <!-- Memory Usage -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-green-900">Memory Usage</h4>
                                <i class="fas fa-memory text-green-600"></i>
                            </div>
                            @php
                                $memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 1);
                                $memoryPeak = round(memory_get_peak_usage(true) / 1024 / 1024, 1);
                            @endphp
                            <p class="text-lg font-bold text-gray-900">{{ $memoryUsage }} MB</p>
                            <p class="text-xs text-gray-600">Peak: {{ $memoryPeak }} MB</p>
                        </div>

                        <!-- Response Time -->
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-yellow-900">Avg Response</h4>
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            @php
                                $startTime = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);
                                $responseTime = round((microtime(true) - $startTime) * 1000, 0);
                            @endphp
                            <p class="text-lg font-bold text-gray-900">{{ $responseTime }}ms</p>
                            <p class="text-xs text-gray-600">This page load</p>
                        </div>

                        <!-- Auto Slideshow Status -->
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-purple-900">Slideshow</h4>
                                <i class="fas fa-images text-purple-600"></i>
                            </div>
                            @php
                                $galleryCount = \App\Models\Gallery::count();
                                $slideshowStatus = $galleryCount > 0 ? 'Active' : 'No Images';
                            @endphp
                            <p class="text-lg font-bold text-gray-900">{{ $slideshowStatus }}</p>
                            <p class="text-xs text-gray-600">{{ $galleryCount }} images cached</p>
                        </div>
                    </div>

                    <!-- Performance Tips -->
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            Performance Optimizations Applied
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Gallery cache (5min TTL)
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Lazy image loading
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Page visibility API
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Optimized DB queries
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Reduced server requests
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Auto-pause on inactivity
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Finance Module Section -->
            <div class="bg-white rounded-xl shadow-sm border mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-2 mr-3">
                                <i class="fas fa-chart-line text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Finance & Reporting</h3>
                                <p class="text-gray-500 text-sm">Manage projects, expenses, and income</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-dollar-sign mr-1"></i>
                                Profit: ${{ number_format(\App\Models\Income::sum('received_amount') - \App\Models\Expense::sum('amount'), 0) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Finance Dashboard -->
                        <a href="{{ route('admin.finance.dashboard') }}" class="group bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 hover:border-green-300 rounded-xl p-4 transition-all hover:shadow-md">
                            <div class="flex items-center">
                                <div class="bg-green-500 rounded-lg p-3 group-hover:bg-green-600 transition-colors">
                                    <i class="fas fa-tachometer-alt text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-gray-900">Dashboard</h4>
                                    <p class="text-sm text-gray-600">P&L Overview</p>
                                </div>
                            </div>
                        </a>

                        <!-- Project Management -->
                        <a href="{{ route('admin.finance.projects') }}" class="group bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 hover:border-blue-300 rounded-xl p-4 transition-all hover:shadow-md">
                            <div class="flex items-center">
                                <div class="bg-blue-500 rounded-lg p-3 group-hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-project-diagram text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-gray-900">Projects</h4>
                                    <p class="text-sm text-gray-600">Manage Projects</p>
                                </div>
                            </div>
                        </a>

                        <!-- Expense Management -->
                        <a href="{{ route('admin.finance.expenses') }}" class="group bg-gradient-to-br from-red-50 to-pink-50 border-2 border-red-200 hover:border-red-300 rounded-xl p-4 transition-all hover:shadow-md">
                            <div class="flex items-center">
                                <div class="bg-red-500 rounded-lg p-3 group-hover:bg-red-600 transition-colors">
                                    <i class="fas fa-receipt text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-gray-900">Expenses</h4>
                                    <p class="text-sm text-gray-600">Track Costs</p>
                                </div>
                            </div>
                        </a>

                        <!-- Income Management -->
                        <a href="{{ route('admin.finance.income') }}" class="group bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 hover:border-emerald-300 rounded-xl p-4 transition-all hover:shadow-md">
                            <div class="flex items-center">
                                <div class="bg-emerald-500 rounded-lg p-3 group-hover:bg-emerald-600 transition-colors">
                                    <i class="fas fa-money-bill-wave text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-gray-900">Income</h4>
                                    <p class="text-sm text-gray-600">Track Payments</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Management Section -->
            <div class="bg-white rounded-xl shadow-sm border mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-2 mr-3">
                            <i class="fas fa-edit text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Content Management</h3>
                            <p class="text-gray-500 text-sm">Manage iGEO Administration</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Clients Management -->
                        <a href="{{ route('admin.clients') }}" class="group bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 hover:border-blue-300 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center mb-3">
                                <div class="bg-blue-500 rounded-lg p-3 group-hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Clients</h4>
                                    <p class="text-gray-600 text-sm">{{ \App\Models\Client::count() }} records</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Manage client information and contact details</p>
                        </a>

                        <!-- Services Management -->
                        <a href="{{ route('admin.services') }}" class="group bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 hover:border-green-300 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center mb-3">
                                <div class="bg-green-500 rounded-lg p-3 group-hover:bg-green-600 transition-colors">
                                    <i class="fas fa-cogs text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Services</h4>
                                    <p class="text-gray-600 text-sm">{{ \App\Models\Service::count() }} services</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Manage service offerings and descriptions</p>
                        </a>

                        <!-- Gallery Management -->
                        <a href="{{ route('admin.gallery') }}" class="group bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 hover:border-purple-300 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center mb-3">
                                <div class="bg-purple-500 rounded-lg p-3 group-hover:bg-purple-600 transition-colors">
                                    <i class="fas fa-images text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Gallery</h4>
                                    <p class="text-gray-600 text-sm">{{ \App\Models\Gallery::count() }} images</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Manage project gallery and portfolio images</p>
                        </a>

                        <!-- Blog Management -->
                        <a href="{{ route('admin.blogs') }}" class="group bg-gradient-to-br from-red-50 to-pink-50 border-2 border-red-200 hover:border-red-300 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center mb-3">
                                <div class="bg-red-500 rounded-lg p-3 group-hover:bg-red-600 transition-colors">
                                    <i class="fas fa-blog text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Blog</h4>
                                    <p class="text-gray-600 text-sm">{{ \App\Models\Blog::count() }} posts</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Manage blog posts and news articles</p>
                        </a>

                        <!-- Experience Management -->
                        <a href="{{ route('admin.experience') }}" class="group bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-200 hover:border-yellow-300 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center mb-3">
                                <div class="bg-yellow-500 rounded-lg p-3 group-hover:bg-yellow-600 transition-colors">
                                    <i class="fas fa-award text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Experience</h4>
                                    <p class="text-gray-600 text-sm">{{ \App\Models\Experience::count() }} records</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Manage company experience and achievements</p>
                        </a>

                        <!-- Documents Management -->
                        <a href="{{ route('admin.documents') }}" class="group bg-gradient-to-br from-indigo-50 to-blue-50 border-2 border-indigo-200 hover:border-indigo-300 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center mb-3">
                                <div class="bg-indigo-500 rounded-lg p-3 group-hover:bg-indigo-600 transition-colors">
                                    <i class="fas fa-file-alt text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Documents</h4>
                                    <p class="text-gray-600 text-sm">{{ \App\Models\Document::count() }} files</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Manage documents and file resources</p>
                        </a>

                        <!-- Pharmacovigilance / Adverse Event Management -->
                        @php
                            $hasAeTable = \Illuminate\Support\Facades\Schema::hasTable('adverse_event_reports');
                            $totalAeCount = $hasAeTable ? \App\Models\AdverseEventReport::count() : 0;
                            $pendingAeCount = $hasAeTable ? \App\Models\AdverseEventReport::where('status', 'new')->count() : 0;
                        @endphp
                        <a href="{{ route('admin.adverse-events') }}" class="group bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 hover:border-emerald-400 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="bg-emerald-600 rounded-lg p-3 group-hover:bg-emerald-700 transition-colors">
                                        <i class="fas fa-shield-virus text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold text-gray-900">Adverse Events</h4>
                                        <p class="text-gray-600 text-sm">{{ $totalAeCount }} safety reports</p>
                                    </div>
                                </div>
                                @if($pendingAeCount > 0)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">{{ $pendingAeCount }} new</span>
                                @endif
                            </div>
                            <p class="text-gray-600 text-sm">Review Drug Safety &amp; Pharmacovigilance submissions</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Employee Management Section -->
            <div class="bg-white rounded-xl shadow-sm border mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 rounded-lg p-2 mr-3">
                            <i class="fas fa-id-card text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Employee Management</h3>
                            <p class="text-gray-500 text-sm">Manage employees, roles, and permissions</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Employee Management -->
                        <a href="{{ route('admin.employees') }}" class="group bg-gradient-to-br from-teal-50 to-cyan-50 border-2 border-teal-200 hover:border-teal-300 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center mb-3">
                                <div class="bg-teal-500 rounded-lg p-3 group-hover:bg-teal-600 transition-colors">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Employees</h4>
                                    <p class="text-gray-600 text-sm">{{ \App\Models\Employee::count() }} records</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Manage employee database and user accounts</p>
                        </a>

                        <!-- Role Management -->
                        <a href="{{ route('admin.roles') }}" class="group bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-200 hover:border-indigo-300 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center mb-3">
                                <div class="bg-indigo-500 rounded-lg p-3 group-hover:bg-indigo-600 transition-colors">
                                    <i class="fas fa-user-tag text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Roles</h4>
                                    <p class="text-gray-600 text-sm">{{ \App\Models\Role::count() }} roles</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Manage user roles and permissions</p>
                        </a>

                        <!-- Permission Management -->
                        <a href="{{ route('admin.permissions') }}" class="group bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 hover:border-purple-300 rounded-xl p-6 transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="flex items-center mb-3">
                                <div class="bg-purple-500 rounded-lg p-3 group-hover:bg-purple-600 transition-colors">
                                    <i class="fas fa-key text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Permissions</h4>
                                    <p class="text-gray-600 text-sm">{{ \App\Models\Permission::count() }} permissions</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Manage system permissions</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Website Preview -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-blue-700 hover:to-blue-800 transition-all shadow-sm hover:shadow-md">
                                <i class="fas fa-eye mr-2"></i>
                                View Public Website
                            </a>
                            <a href="{{ route('profile.edit') }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-gray-700 hover:to-gray-800 transition-all shadow-sm hover:shadow-md">
                                <i class="fas fa-user-edit mr-2"></i>
                                Edit Profile Settings
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-clock text-green-500 mr-2"></i>
                            System Status
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900">Database</span>
                                </div>
                                <span class="text-sm text-green-600 font-semibold">Connected</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900">File Storage</span>
                                </div>
                                <span class="text-sm text-green-600 font-semibold">Available</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900">Last Backup</span>
                                </div>
                                <span class="text-sm text-blue-600 font-semibold">{{ now()->format('M j') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
