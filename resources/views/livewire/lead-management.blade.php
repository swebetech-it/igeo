<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">AI Captured Leads</h1>
            <p class="mt-2 text-sm text-gray-700">Leads automatically captured by the AI agent during customer chats.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="mt-8 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 max-w-xs">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search leads..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <div>
            <select wire:model.live="statusFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">All Statuses</option>
                <option value="new">New</option>
                <option value="contacted">Contacted</option>
                <option value="converted">Converted</option>
                <option value="junk">Junk</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg bg-white">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Potential Client</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Contact Info</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Company</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($leads as $lead)
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-medium">{{ $lead->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="text-gray-900">{{ $lead->email }}</div>
                                    <div class="text-xs">{{ $lead->phone }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $lead->company ?: 'N/A' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <select wire:change="updateStatus({{ $lead->id }}, $event.target.value)" class="text-xs rounded border-gray-300 focus:ring-indigo-500">
                                        <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>New</option>
                                        <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="converted" {{ $lead->status == 'converted' ? 'selected' : '' }}>Converted</option>
                                        <option value="junk" {{ $lead->status == 'junk' ? 'selected' : '' }}>Junk</option>
                                    </select>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $lead->created_at->format('M d, Y') }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <button wire:click="viewLead({{ $lead->id }})" class="text-indigo-600 hover:text-indigo-900 mr-4">View</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                        {{ $leads->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lead Detail Modal -->
    @if($showDetailModal && $selectedLead)
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Lead Detail</h3>
                        <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-500">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase">Customer Name</span>
                            <p class="text-lg font-semibold">{{ $selectedLead->name }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase">Email</span>
                                <p class="text-gray-900">{{ $selectedLead->email }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-500 uppercase">Phone</span>
                                <p class="text-gray-900">{{ $selectedLead->phone }}</p>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase">Company</span>
                            <p class="text-gray-900">{{ $selectedLead->company ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase">Captured Message / Intent</span>
                            <div class="mt-1 p-4 bg-gray-50 rounded italic text-gray-700">
                                "{{ $selectedLead->message }}"
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex justify-between">
                    <button wire:click="deleteLead({{ $selectedLead->id }})" class="px-4 py-2 bg-red-100 text-red-700 rounded font-bold hover:bg-red-200 transition-colors">Delete Lead</button>
                    <button wire:click="$set('showDetailModal', false)" class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-bold hover:bg-gray-300 transition-colors">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
