<?php

namespace App\Livewire;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class PermissionManagement extends Component
{
    use WithPagination;

    public $name, $description;
    public $permissionId;
    public $isEdit = false;
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // UI state
    public $showCreateForm = false;
    public $showEditForm = false;
    public $showDeleteConfirm = false;
    public $deletePermissionId = null;
    public $isLoading = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:permissions,name' . ($this->isEdit ? ',' . $this->permissionId : ''),
            'description' => 'nullable|string|max:500',
        ];
    }

    public function openCreateForm()
    {
        Log::info('PermissionManagement: openCreateForm button clicked');
        try {
            $this->resetForm();
            $this->showCreateForm = true;
            Log::info('PermissionManagement: Create form opened successfully');
        } catch (\Exception $e) {
            Log::error('PermissionManagement: Error opening create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function closeCreateForm()
    {
        Log::info('PermissionManagement: closeCreateForm button clicked');
        try {
            $this->showCreateForm = false;
            $this->resetForm();
            Log::info('PermissionManagement: Create form closed successfully');
        } catch (\Exception $e) {
            Log::error('PermissionManagement: Error closing create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $permissions = Permission::with('roles')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        // Get statistics for display
        $stats = [
            'total' => Permission::count(),
            'permissionsWithRoles' => Permission::has('roles')->count(),
        ];

        return view('livewire.permission-management', compact('permissions', 'stats'));
    }

    public function save()
    {
        Log::info('PermissionManagement: Save button clicked', [
            'isEdit' => $this->isEdit,
            'permissionId' => $this->permissionId,
        ]);

        $this->isLoading = true;

        try {
            Log::info('PermissionManagement: Starting validation');
            $this->validate();

            $permissionData = [
                'name' => $this->name,
                'description' => $this->description,
            ];

            if ($this->isEdit) {
                Log::info('PermissionManagement: Updating existing permission', ['permissionId' => $this->permissionId]);
                $permission = Permission::find($this->permissionId);
                $permission->update($permissionData);
                $message = 'Permission updated successfully!';
                Log::info('PermissionManagement: Permission updated successfully');
            } else {
                Log::info('PermissionManagement: Creating new permission');
                $permission = Permission::create($permissionData);
                $message = 'Permission created successfully!';
                Log::info('PermissionManagement: Permission created successfully', ['newId' => $permission->id]);
            }

            $this->closeCreateForm();
            $this->dispatch('show-toast', ['message' => $message, 'type' => 'success']);
            Log::info('PermissionManagement: Save operation completed successfully');

        } catch (\Exception $e) {
            Log::error('PermissionManagement: Error during save operation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->dispatch('show-toast', ['message' => 'Error saving permission. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }

    public function confirmDelete($id)
    {
        $this->deletePermissionId = $id;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->deletePermissionId = null;
        $this->showDeleteConfirm = false;
    }

    public function delete()
    {
        try {
            $permission = Permission::findOrFail($this->deletePermissionId);
            
            // Check if permission is assigned to any roles
            if ($permission->roles()->count() > 0) {
                $this->dispatch('show-toast', [
                    'message' => 'Cannot delete permission. It is assigned to ' . $permission->roles()->count() . ' role(s).', 
                    'type' => 'error'
                ]);
                $this->cancelDelete();
                return;
            }
            
            $permission->delete();
            
            $this->cancelDelete();
            $this->dispatch('show-toast', ['message' => 'Permission deleted successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            Log::error('PermissionManagement: Error deleting permission', [
                'error' => $e->getMessage(),
                'permissionId' => $this->deletePermissionId
            ]);
            $this->dispatch('show-toast', ['message' => 'Error deleting permission. Please try again.', 'type' => 'error']);
        }
    }

    public function edit($id)
    {
        Log::info('PermissionManagement: Edit button clicked', ['permissionId' => $id]);
        try {
            $permission = Permission::findOrFail($id);
            $this->permissionId = $id;
            $this->name = $permission->name;
            $this->description = $permission->description;
            $this->isEdit = true;
            $this->showCreateForm = true;

            Log::info('PermissionManagement: Edit form populated successfully', [
                'permissionId' => $id,
            ]);
        } catch (\Exception $e) {
            Log::error('PermissionManagement: Error populating edit form', [
                'error' => $e->getMessage(),
                'permissionId' => $id
            ]);
            $this->dispatch('show-toast', ['message' => 'Error loading permission data.', 'type' => 'error']);
        }
    }

    public function resetForm()
    {
        $this->permissionId = null;
        $this->name = '';
        $this->description = '';
        $this->isEdit = false;
        $this->resetErrorBag();
    }
}