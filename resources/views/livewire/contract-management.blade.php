<div x-data="{
    showCreateModal: $wire.entangle('showCreateForm'),
    showEditModal: $wire.entangle('showEditForm'),
    showSignatureModal: $wire.entangle('showSignatureModal')
}" class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4">
        <!-- Include unified styles -->
        @include('components.admin.ui-consistency-styles')
        
        <!-- Header Section -->
        <x-admin.page-header 
            title="Contract Management"
            subtitle="Paperless contract lifecycle with secure e‑signatures and status tracking"
            icon="fas fa-file-signature"
            color="blue"
        >
            <x-slot name="actions">
                <button class="admin-btn admin-btn-primary" wire:click="create" type="button">
                    <i class="fas fa-plus mr-2"></i>New Contract
                </button>
                <button class="admin-btn admin-btn-secondary" wire:click="$refresh" type="button">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </x-slot>
        </x-admin.page-header>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Contracts Table -->
        <div class="admin-card">
            <div class="admin-card-header flex items-center justify-between">
                <h5 class="admin-card-title">Active Contracts</h5>
                <span class="text-sm text-gray-500">{{ $contracts->total() }} contracts</span>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Contract</th>
                            <th>Project/Client</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Signatures</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $contract)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow mr-3">
                                            <i class="fas fa-file-contract"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $contract->title }}</div>
                                            <div class="text-sm text-gray-500">Ref: {{ $contract->contract_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($contract->project)
                                        <div class="font-medium">{{ $contract->project->name }}</div>
                                    @endif
                                    @if($contract->client)
                                        <div class="text-sm text-gray-500">{{ $contract->client->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="admin-badge bg-blue-100 text-blue-800">
                                        {{ ucfirst($contract->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($contract->value)
                                        <div class="font-medium">${{ number_format($contract->value, 2) }}</div>
                                    @else
                                        <div class="text-gray-500">-</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="admin-badge bg-purple-100 text-purple-800">
                                        {{ $contract->signatures_received }}/{{ $contract->signatures_required }}
                                    </span>
                                </td>
                                <td>
                                    @php $status = $contract->status; @endphp
                                    <span class="admin-badge {{ $status === 'completed' ? 'admin-badge-completed' : '' }} {{ $status === 'pending' ? 'admin-badge-pending' : '' }} {{ $status === 'cancelled' ? 'admin-badge-rejected' : '' }} {{ $status === 'draft' ? 'admin-badge-draft' : '' }}">
                                        {{ ucfirst(str_replace('_',' ',$status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm text-gray-500">{{ $contract->updated_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="flex">
                                        <button wire:click="edit({{ $contract->id }})" class="admin-action-btn admin-action-btn-edit" type="button">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="openSignatureModal({{ $contract->id }})" class="admin-action-btn admin-action-btn-success ml-1" type="button">
                                            <i class="fas fa-signature"></i>
                                        </button>
                                        <button wire:click="delete({{ $contract->id }})" class="admin-action-btn admin-action-btn-delete ml-1" wire:confirm="Are you sure you want to delete this contract?" type="button">
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
                                            <i class="fas fa-file-contract"></i>
                                        </div>
                                        <h5 class="admin-empty-state-title">No Contracts Found</h5>
                                        <p class="admin-empty-state-description">
                                            Get started by creating your first contract for project management.
                                        </p>
                                        <button wire:click="create" class="admin-btn admin-btn-primary" type="button">
                                            <i class="fas fa-plus mr-2"></i>Add First Contract
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($contracts->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-center">
                        {{ $contracts->links() }}
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

                <form wire:submit.prevent="{{ $showEditForm ? 'update' : 'store' }}" enctype="multipart/form-data">
                    <div class="px-6 py-4 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $showEditForm ? 'Edit Contract' : 'Create New Contract' }}</h3>
                            <button type="button"
                                    wire:click="{{ $showEditForm ? 'closeEditForm' : 'closeCreateForm' }}"
                                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="admin-label">Contract Title *</label>
                                <input type="text" class="admin-input @error('title') border-red-500 @enderror" wire:model="title" placeholder="e.g., Construction Service Agreement" required>
                                @error('title') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="admin-label">Description</label>
                            <textarea class="admin-input @error('description') border-red-500 @enderror" wire:model="description" rows="3" placeholder="Brief contract description..."></textarea>
                            @error('description') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="admin-label">Project</label>
                                <select class="admin-select @error('project_id') border-red-500 @enderror" wire:model="project_id">
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">Client</label>
                                <select class="admin-select @error('client_id') border-red-500 @enderror" wire:model="client_id">
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="admin-label">Type *</label>
                                <select class="admin-select @error('type') border-red-500 @enderror" wire:model="type" required>
                                    <option value="service">Service</option>
                                    <option value="purchase">Purchase</option>
                                    <option value="lease">Lease</option>
                                    <option value="employment">Employment</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('type') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">Value ($)</label>
                                <input type="number" class="admin-input @error('value') border-red-500 @enderror" wire:model="value" step="0.01" min="0" placeholder="0.00">
                                @error('value') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">Signatures Required *</label>
                                <input type="number" class="admin-input @error('signatures_required') border-red-500 @enderror" wire:model="signatures_required" min="1" required>
                                @error('signatures_required') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="admin-label">Start Date</label>
                                <input type="date" class="admin-input @error('start_date') border-red-500 @enderror" wire:model="start_date">
                                @error('start_date') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="admin-label">End Date</label>
                                <input type="date" class="admin-input @error('end_date') border-red-500 @enderror" wire:model="end_date">
                                @error('end_date') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="admin-label">Terms and Conditions</label>
                            <textarea class="admin-input @error('terms_and_conditions') border-red-500 @enderror" wire:model="terms_and_conditions" rows="4" placeholder="Enter contract terms and conditions..."></textarea>
                            @error('terms_and_conditions') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div>
                            <label class="admin-label">{{ $showEditForm ? 'Replace Document' : 'Upload Document' }} (PDF, DOC, DOCX)</label>
                            <input type="file" class="admin-input @error('document') border-red-500 @enderror" wire:model="document" accept=".pdf,.doc,.docx">
                            @error('document') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                            <div class="text-sm text-gray-500 mt-1">Max 10MB. Supported: PDF, DOC, DOCX</div>
                            <div wire:loading wire:target="document" class="text-sm text-blue-600 mt-2">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Uploading...
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button"
                                wire:click="{{ $showEditForm ? 'closeEditForm' : 'closeCreateForm' }}"
                                class="admin-btn admin-btn-secondary">
                            Cancel
                        </button>
                        <button type="submit"
                                class="admin-btn admin-btn-primary">
                            <i class="fas fa-save mr-2"></i>{{ $showEditForm ? 'Update Contract' : 'Create Contract' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Signature Modal -->
    <div x-show="showSignatureModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showSignatureModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                 @click="showSignatureModal = false"></div>

            <div x-show="showSignatureModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Sign Contract</h3>
                        <button type="button"
                                wire:click="closeSignatureModal"
                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-4">
                    <p class="text-gray-600 dark:text-gray-400">Draw your signature below. It will be securely attached to this contract record.</p>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl overflow-hidden bg-white dark:bg-gray-900 shadow-inner">
                        <canvas id="signaturePad" width="900" height="220" class="w-full cursor-crosshair"></canvas>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="button" class="admin-btn admin-btn-secondary" wire:click="closeSignatureModal">Cancel</button>
                        <button type="button" class="admin-btn admin-btn-primary" wire:click="saveSignature">Save Signature</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>