{{-- Project Management Component with Modern UI --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    {{-- Header Section --}}
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-project-diagram text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Project Management</h1>
                    <p class="text-gray-600">Manage and track all construction projects</p>
                </div>
            </div>
            <button wire:click="openCreateForm"
                    class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>New Project</span>
            </button>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-project-diagram text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Active Projects</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-play-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Completed</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['completed'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Budget</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_budget'] ?? 0, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Search and Filter Section --}}
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-4">
            <div class="md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Search projects..."
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <select wire:model.live="statusFilter"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="clientFilter"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="typeFilter"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    @foreach($typeOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Active Filters --}}
        @if($search || $statusFilter || $clientFilter || $typeFilter)
            <div class="flex flex-wrap gap-2">
                <span class="text-sm text-gray-600">Active filters:</span>
                @if($search)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Search: "{{ $search }}"
                        <button wire:click="$set('search', '')" class="ml-1 text-blue-600 hover:text-blue-800">×</button>
                    </span>
                @endif
                @if($statusFilter)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Status: {{ $statusOptions[$statusFilter ?? ''] ?? $statusFilter ?? 'Unknown' }}
                        <button wire:click="$set('statusFilter', '')" class="ml-1 text-green-600 hover:text-green-800">×</button>
                    </span>
                @endif
                @if($clientFilter)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Client: {{ $clients->find($clientFilter)->name ?? 'Unknown' }}
                        <button wire:click="$set('clientFilter', '')" class="ml-1 text-purple-600 hover:text-purple-800">×</button>
                    </span>
                @endif
                @if($typeFilter)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Type: {{ $typeOptions[$typeFilter ?? ''] ?? $typeFilter ?? 'Unknown' }}
                        <button wire:click="$set('typeFilter', '')" class="ml-1 text-yellow-600 hover:text-yellow-800">×</button>
                    </span>
                @endif
            </div>
        @endif
    </div>

    {{-- Projects Table --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800">Projects List</h2>
            <div class="flex items-center space-x-4">
                <select wire:model.live="perPage" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('name')">
                            <div class="flex items-center space-x-1">
                                <span>Name</span>
                                @if($sortBy === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('client_id')">
                            <div class="flex items-center space-x-1">
                                <span>Client</span>
                                @if($sortBy === 'client_id')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('status')">
                            <div class="flex items-center space-x-1">
                                <span>Status</span>
                                @if($sortBy === 'status')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('type')">
                            <div class="flex items-center space-x-1">
                                <span>Type</span>
                                @if($sortBy === 'type')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('budget')">
                            <div class="flex items-center space-x-1">
                                <span>Budget</span>
                                @if($sortBy === 'budget')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('start_date')">
                            <div class="flex items-center space-x-1">
                                <span>Start Date</span>
                                @if($sortBy === 'start_date')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400"></i>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                    @if($project->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($project->description, 50) }}</div>
                                    @endif
                                    @if($project->location)
                                        <div class="text-xs text-gray-400 flex items-center mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ Str::limit($project->location, 30) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $project->client->name }}</div>
                                @if($project->project_manager)
                                    <div class="text-sm text-gray-500">PM: {{ $project->project_manager }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'planning' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'active' => 'bg-green-100 text-green-800 border-green-200',
                                        'on_hold' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        'completed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'cancelled' => 'bg-red-100 text-red-800 border-red-200'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                    {{ $statusOptions[$project->status] ?? ucfirst($project->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'residential' => 'bg-purple-100 text-purple-800',
                                        'commercial' => 'bg-blue-100 text-blue-800',
                                        'industrial' => 'bg-gray-100 text-gray-800',
                                        'infrastructure' => 'bg-green-100 text-green-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$project->type] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $typeOptions[$project->type] ?? ucfirst($project->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($project->budget)
                                        ${{ number_format($project->budget, 0) }}
                                    @else
                                        <span class="text-gray-400">Not set</span>
                                    @endif
                                </div>
                                @if($project->actual_cost > 0)
                                    <div class="text-xs text-gray-500">
                                        Actual: ${{ number_format($project->actual_cost, 0) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-1">
                                        <div class="bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                                                 style="width: {{ $project->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ round($project->progress_percentage) }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $project->start_date ? $project->start_date->format('M d, Y') : '-' }}
                                </div>
                                @if($project->end_date)
                                    <div class="text-xs text-gray-500">
                                        End: {{ $project->end_date->format('M d, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('projects.finance', $project->id) }}"
                                       class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-700 text-white px-3 py-2 rounded-lg font-medium transition-all duration-150 flex items-center space-x-1 shadow-sm">
                                        <i class="fas fa-coins"></i>
                                        <span>Finance</span>
                                    </a>
                                    <button wire:click="openEditForm({{ $project->id }})"
                                            class="text-blue-600 hover:text-blue-900 transition-colors duration-150 p-2 rounded-lg hover:bg-blue-50">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $project->id }})"
                                            class="text-red-600 hover:text-red-900 transition-colors duration-150 p-2 rounded-lg hover:bg-red-50">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-project-diagram text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No projects found</h3>
                                    <p class="text-gray-500">Get started by creating your first project.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($projects->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

    {{-- Create Project Modal --}}
    @if($showCreateForm)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-2xl rounded-2xl bg-white">
                <div class="flex items-center justify-between p-6 border-b border-gray-200 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Create New Project</h3>
                    </div>
                    <button wire:click="closeCreateForm"
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-150 p-2 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="p-6 max-h-96 overflow-y-auto">
                    <form wire:submit.prevent="createProject" class="space-y-6">
                        {{-- Project Name and Status --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                                <input type="text" wire:model="name" id="name"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select wire:model="status" id="status"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                    @foreach($statusOptions as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description" id="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                      placeholder="Enter project description..."></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Client and Project Manager --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                                <select wire:model="client_id" id="client_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_id') border-red-500 @enderror">
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="project_manager" class="block text-sm font-medium text-gray-700 mb-2">Project Manager</label>
                                <input type="text" wire:model="project_manager" id="project_manager"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('project_manager') border-red-500 @enderror"
                                       placeholder="Enter project manager name">
                                @error('project_manager') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Type and Budget --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                <select wire:model="type" id="type"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror">
                                    @foreach($typeOptions as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget</label>
                                <input type="number" wire:model="budget" id="budget" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('budget') border-red-500 @enderror"
                                       placeholder="0.00">
                                @error('budget') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="actual_cost" class="block text-sm font-medium text-gray-700 mb-2">Actual Cost</label>
                                <input type="number" wire:model="actual_cost" id="actual_cost" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('actual_cost') border-red-500 @enderror"
                                       placeholder="0.00">
                                @error('actual_cost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Dates and Progress --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                <input type="date" wire:model="start_date" id="start_date"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                                @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                <input type="date" wire:model="end_date" id="end_date"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                                @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="completion_date" class="block text-sm font-medium text-gray-700 mb-2">Completion Date</label>
                                <input type="date" wire:model="completion_date" id="completion_date"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('completion_date') border-red-500 @enderror">
                                @error('completion_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="progress_percentage" class="block text-sm font-medium text-gray-700 mb-2">Progress (%)</label>
                                <input type="number" wire:model="progress_percentage" id="progress_percentage" min="0" max="100"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('progress_percentage') border-red-500 @enderror"
                                       placeholder="0">
                                @error('progress_percentage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Location --}}
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <textarea wire:model="location" id="location" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
                                      placeholder="Enter project location..."></textarea>
                            @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea wire:model="notes" id="notes" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                      placeholder="Additional notes or comments..."></textarea>
                            @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </form>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl space-x-4">
                    <button wire:click="closeCreateForm"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-colors duration-150">
                        Cancel
                    </button>
                    <button wire:click="createProject"
                            wire:loading.attr="disabled"
                            class="px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-150 disabled:opacity-50 flex items-center space-x-2">
                        <span wire:loading.remove>Create Project</span>
                        <span wire:loading>Creating...</span>
                        <i wire:loading class="fas fa-spinner fa-spin"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Project Modal --}}
    @if($showEditForm)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-2xl rounded-2xl bg-white">
                <div class="flex items-center justify-between p-6 border-b border-gray-200 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Edit Project</h3>
                    </div>
                    <button wire:click="closeEditForm"
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-150 p-2 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="p-6 max-h-96 overflow-y-auto">
                    <form wire:submit.prevent="updateProject" class="space-y-6">
                        {{-- Project Name and Status --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                                <input type="text" wire:model="name" id="edit_name"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select wire:model="status" id="edit_status"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                    @foreach($statusOptions as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description" id="edit_description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                      placeholder="Enter project description..."></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Client and Project Manager --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                                <select wire:model="client_id" id="edit_client_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('client_id') border-red-500 @enderror">
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="edit_project_manager" class="block text-sm font-medium text-gray-700 mb-2">Project Manager</label>
                                <input type="text" wire:model="project_manager" id="edit_project_manager"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('project_manager') border-red-500 @enderror"
                                       placeholder="Enter project manager name">
                                @error('project_manager') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Type and Budget --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="edit_type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                <select wire:model="type" id="edit_type"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('type') border-red-500 @enderror">
                                    @foreach($typeOptions as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="edit_budget" class="block text-sm font-medium text-gray-700 mb-2">Budget</label>
                                <input type="number" wire:model="budget" id="edit_budget" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('budget') border-red-500 @enderror"
                                       placeholder="0.00">
                                @error('budget') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="edit_actual_cost" class="block text-sm font-medium text-gray-700 mb-2">Actual Cost</label>
                                <input type="number" wire:model="actual_cost" id="edit_actual_cost" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('actual_cost') border-red-500 @enderror"
                                       placeholder="0.00">
                                @error('actual_cost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Dates and Progress --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="edit_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                <input type="date" wire:model="start_date" id="edit_start_date"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                                @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="edit_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                <input type="date" wire:model="end_date" id="edit_end_date"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('end_date') border-red-500 @enderror">
                                @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="edit_completion_date" class="block text-sm font-medium text-gray-700 mb-2">Completion Date</label>
                                <input type="date" wire:model="completion_date" id="edit_completion_date"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('completion_date') border-red-500 @enderror">
                                @error('completion_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="edit_progress_percentage" class="block text-sm font-medium text-gray-700 mb-2">Progress (%)</label>
                                <input type="number" wire:model="progress_percentage" id="edit_progress_percentage" min="0" max="100"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('progress_percentage') border-red-500 @enderror"
                                       placeholder="0">
                                @error('progress_percentage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Location --}}
                        <div>
                            <label for="edit_location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <textarea wire:model="location" id="edit_location" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('location') border-red-500 @enderror"
                                      placeholder="Enter project location..."></textarea>
                            @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea wire:model="notes" id="edit_notes" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                      placeholder="Additional notes or comments..."></textarea>
                            @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </form>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl space-x-4">
                    <button wire:click="closeEditForm"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-colors duration-150">
                        Cancel
                    </button>
                    <button wire:click="updateProject"
                            wire:loading.attr="disabled"
                            class="px-6 py-2 bg-gradient-to-r from-green-500 to-blue-600 text-white rounded-lg hover:from-green-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors duration-150 disabled:opacity-50 flex items-center space-x-2">
                        <span wire:loading.remove>Update Project</span>
                        <span wire:loading>Updating...</span>
                        <i wire:loading class="fas fa-spinner fa-spin"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteConfirm)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/3 shadow-2xl rounded-2xl bg-white">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Project</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Are you sure you want to delete this project? This action cannot be undone and will remove all associated data.
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <button wire:click="cancelDelete"
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-colors duration-150">
                            Cancel
                        </button>
                        <button wire:click="deleteProject"
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors duration-150">
                            Delete Project
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
