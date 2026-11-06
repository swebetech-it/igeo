<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Log;

class ClientManagement extends Component
{
    use WithPagination;

    public $name, $email, $phone, $company, $project_type, $status = 'active', $notes, $is_public = false;
    public $clientId;
    public $isEdit = false;
    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    // UI state
    public $showCreateForm = false;
    public $showEditForm = false;
    public $showDeleteConfirm = false;
    public $deleteClientId = null;
    public $isLoading = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email' . ($this->isEdit ? ',' . $this->clientId : ''),
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'project_type' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,potential',
            'notes' => 'nullable|string',
            'is_public' => 'boolean',
        ];
    }

    public function openCreateForm()
    {
        Log::info('ClientManagement: openCreateForm button clicked');
        try {
            $this->resetForm();
            $this->showCreateForm = true;
            Log::info('ClientManagement: Create form opened successfully', [
                'showCreateForm' => $this->showCreateForm
            ]);
        } catch (\Exception $e) {
            Log::error('ClientManagement: Error opening create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function closeCreateForm()
    {
        Log::info('ClientManagement: closeCreateForm button clicked');
        try {
            $this->showCreateForm = false;
            $this->resetForm();
            Log::info('ClientManagement: Create form closed successfully');
        } catch (\Exception $e) {
            Log::error('ClientManagement: Error closing create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function openEditForm()
    {
        $this->showEditForm = true;
    }

    public function closeEditForm()
    {
        $this->showEditForm = false;
        $this->resetForm();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('company', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('project_type', $this->typeFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        // Get statistics for display
        $stats = [
            'total' => Client::count(),
            'active' => Client::where('status', 'active')->count(),
            'potential' => Client::where('status', 'potential')->count(),
            'inactive' => Client::where('status', 'inactive')->count(),
        ];

        return view('livewire.client-management', compact('clients', 'stats'));
    }

    public function save()
    {
        Log::info('ClientManagement: Save button clicked', [
            'isEdit' => $this->isEdit,
            'clientId' => $this->clientId,
            'name' => $this->name,
            'email' => $this->email
        ]);

        $this->isLoading = true;

        try {
            Log::info('ClientManagement: Starting validation');
            $this->validate();

            $clientData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'company' => $this->company,
                'project_type' => $this->project_type,
                'status' => $this->status,
                'notes' => $this->notes,
                'is_public' => $this->is_public,
            ];

            if ($this->isEdit) {
                Log::info('ClientManagement: Updating existing client', ['clientId' => $this->clientId, 'data' => $clientData]);
                Client::find($this->clientId)->update($clientData);
                $message = 'Client updated successfully!';
                Log::info('ClientManagement: Client updated successfully');
            } else {
                Log::info('ClientManagement: Creating new client', ['data' => $clientData]);
                $newClient = Client::create($clientData);
                $message = 'Client created successfully!';
                Log::info('ClientManagement: Client created successfully', ['newId' => $newClient->id]);
            }

            $this->closeCreateForm();
            $this->closeEditForm();
            $this->dispatch('show-toast', ['message' => $message, 'type' => 'success']);
            Log::info('ClientManagement: Save operation completed successfully');

        } catch (\Exception $e) {
            Log::error('ClientManagement: Error during save operation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $clientData ?? null
            ]);
            $this->dispatch('show-toast', ['message' => 'Error saving client. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteClientId = $id;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->deleteClientId = null;
        $this->showDeleteConfirm = false;
    }

    public function edit($id)
    {
        Log::info('ClientManagement: Edit button clicked', ['clientId' => $id]);
        try {
            $client = Client::findOrFail($id);
            $this->clientId = $id;
            $this->name = $client->name;
            $this->email = $client->email;
            $this->phone = $client->phone;
            $this->company = $client->company;
            $this->project_type = $client->project_type;
            $this->status = $client->status;
            $this->notes = $client->notes;
            $this->is_public = $client->is_public;
            $this->isEdit = true;
            $this->showEditForm = true;

            Log::info('ClientManagement: Edit form populated successfully', [
                'clientId' => $id,
                'name' => $this->name,
                'showEditForm' => $this->showEditForm
            ]);
        } catch (\Exception $e) {
            Log::error('ClientManagement: Error during edit operation', [
                'error' => $e->getMessage(),
                'clientId' => $id
            ]);
        }
    }

    public function delete()
    {
        Log::info('ClientManagement: Delete button clicked', ['clientId' => $this->deleteClientId]);
        if (!$this->deleteClientId) {
            Log::warning('ClientManagement: Delete called without deleteClientId');
            return;
        }

        try {
            $client = Client::findOrFail($this->deleteClientId);
            Log::info('ClientManagement: Starting delete operation', [
                'clientId' => $this->deleteClientId,
                'clientName' => $client->name
            ]);

            $client->delete();
            Log::info('ClientManagement: Client deleted successfully', ['clientId' => $this->deleteClientId]);
            $this->dispatch('show-toast', ['message' => 'Client deleted successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            Log::error('ClientManagement: Error during delete operation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'clientId' => $this->deleteClientId
            ]);
            $this->dispatch('show-toast', ['message' => 'Error deleting client. It may be associated with active projects.', 'type' => 'error']);
        } finally {
            $this->deleteClientId = null;
            $this->showDeleteConfirm = false;
        }
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'phone', 'company', 'project_type', 'notes', 'is_public', 'clientId', 'isEdit']);
        $this->status = 'active';
        $this->is_public = false;
        $this->resetErrorBag();
    }
}