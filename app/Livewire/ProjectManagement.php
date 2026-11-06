<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\Client;
use Illuminate\Support\Facades\Log;

class ProjectManagement extends Component
{
    use WithPagination;

    public $showCreateForm = false;
    public $showEditForm = false;
    public $showDeleteConfirm = false;
    public $editingProject = null;
    public $deleteProjectId = null;
    public $isLoading = false;

    // Form fields
    public $name;
    public $description;
    public $client_id;
    public $status = 'planning';
    public $type = 'residential';
    public $budget;
    public $actual_cost = 0;
    public $start_date;
    public $end_date;
    public $completion_date;
    public $project_manager;
    public $location;
    public $progress_percentage = 0;
    public $notes;

    // Search and filters
    public $search = '';
    public $statusFilter = '';
    public $clientFilter = '';
    public $typeFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'client_id' => 'required|exists:clients,id',
        'status' => 'required|in:planning,active,on_hold,completed,cancelled',
        'type' => 'required|in:residential,commercial,industrial,infrastructure',
        'budget' => 'nullable|numeric|min:0',
        'actual_cost' => 'nullable|numeric|min:0',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'completion_date' => 'nullable|date',
        'project_manager' => 'nullable|string|max:255',
        'location' => 'nullable|string',
        'progress_percentage' => 'nullable|integer|min:0|max:100',
        'notes' => 'nullable|string'
    ];

    public function openCreateForm()
    {
        Log::info('ProjectManagement: openCreateForm button clicked');
        try {
            $this->resetForm();
            $this->showCreateForm = true;
            Log::info('ProjectManagement: Create form opened successfully', [
                'showCreateForm' => $this->showCreateForm
            ]);
        } catch (\Exception $e) {
            Log::error('ProjectManagement: Error opening create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function closeCreateForm()
    {
        Log::info('ProjectManagement: closeCreateForm button clicked');
        try {
            $this->showCreateForm = false;
            $this->resetForm();
            Log::info('ProjectManagement: Create form closed successfully');
        } catch (\Exception $e) {
            Log::error('ProjectManagement: Error closing create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function openEditForm($projectId)
    {
        $this->editingProject = Project::findOrFail($projectId);
        $this->name = $this->editingProject->name;
        $this->description = $this->editingProject->description;
        $this->client_id = $this->editingProject->client_id;
        $this->status = $this->editingProject->status;
        $this->type = $this->editingProject->type;
        $this->budget = $this->editingProject->budget;
        $this->actual_cost = $this->editingProject->actual_cost;
        $this->start_date = $this->editingProject->start_date ? $this->editingProject->start_date->format('Y-m-d') : '';
        $this->end_date = $this->editingProject->end_date ? $this->editingProject->end_date->format('Y-m-d') : '';
        $this->completion_date = $this->editingProject->completion_date ? $this->editingProject->completion_date->format('Y-m-d') : '';
        $this->project_manager = $this->editingProject->project_manager;
        $this->location = $this->editingProject->location;
        $this->progress_percentage = $this->editingProject->progress_percentage;
        $this->notes = $this->editingProject->notes;
        $this->showEditForm = true;
    }

    public function closeEditForm()
    {
        $this->showEditForm = false;
        $this->editingProject = null;
        $this->resetForm();
    }

    public function createProject()
    {
        $this->isLoading = true;

        try {
            $this->validate();

            Project::create([
                'name' => $this->name,
                'description' => $this->description,
                'client_id' => $this->client_id,
                'status' => $this->status,
                'type' => $this->type,
                'budget' => $this->budget,
                'actual_cost' => $this->actual_cost,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'completion_date' => $this->completion_date,
                'project_manager' => $this->project_manager,
                'location' => $this->location,
                'progress_percentage' => $this->progress_percentage,
                'notes' => $this->notes
            ]);

            $this->dispatch('show-toast', ['message' => 'Project created successfully!', 'type' => 'success']);
            $this->closeCreateForm();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['message' => 'Error creating project. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }

    public function updateProject()
    {
        $this->isLoading = true;

        try {
            $this->validate();

            $this->editingProject->update([
                'name' => $this->name,
                'description' => $this->description,
                'client_id' => $this->client_id,
                'status' => $this->status,
                'type' => $this->type,
                'budget' => $this->budget,
                'actual_cost' => $this->actual_cost,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'completion_date' => $this->completion_date,
                'project_manager' => $this->project_manager,
                'location' => $this->location,
                'progress_percentage' => $this->progress_percentage,
                'notes' => $this->notes
            ]);

            $this->dispatch('show-toast', ['message' => 'Project updated successfully!', 'type' => 'success']);
            $this->closeEditForm();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['message' => 'Error updating project. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }



    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->client_id = '';
        $this->status = 'planning';
        $this->type = 'residential';
        $this->budget = '';
        $this->actual_cost = 0;
        $this->start_date = '';
        $this->end_date = '';
        $this->completion_date = '';
        $this->project_manager = '';
        $this->location = '';
        $this->progress_percentage = 0;
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingClientFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteProjectId = $id;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->deleteProjectId = null;
        $this->showDeleteConfirm = false;
    }

    public function deleteProject()
    {
        if (!$this->deleteProjectId) return;

        try {
            Project::findOrFail($this->deleteProjectId)->delete();
            $this->dispatch('show-toast', ['message' => 'Project deleted successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['message' => 'Error deleting project. It may have associated data.', 'type' => 'error']);
        } finally {
            $this->cancelDelete();
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

    public function render()
    {
        $projects = Project::with('client')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('project_manager', 'like', '%' . $this->search . '%')
                    ->orWhere('location', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->clientFilter, function ($query) {
                $query->where('client_id', $this->clientFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $clients = Client::orderBy('name')->get();

        $statusOptions = [
            'planning' => 'Planning',
            'active' => 'Active',
            'on_hold' => 'On Hold',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        $typeOptions = [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
            'infrastructure' => 'Infrastructure',
            'renovation' => 'Renovation',
            'new_construction' => 'New Construction'
        ];

        // Get statistics for display
        $stats = [
            'total' => Project::count(),
            'active' => Project::where('status', 'active')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'planning' => Project::where('status', 'planning')->count(),
            'total_budget' => Project::sum('budget') ?: 0,
            'total_cost' => Project::sum('actual_cost') ?: 0,
        ];

        return view('livewire.project-management', compact('projects', 'clients', 'statusOptions', 'typeOptions', 'stats'));
    }
}
