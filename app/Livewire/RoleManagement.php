<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class RoleManagement extends Component
{
    use WithPagination;

    public $name, $description;
    public $roleId;
    public $isEdit = false;
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // For permissions
    public $selectedPermissions = [];
    public $showPermissionsModal = false;
    
    // UI state
    public $showCreateForm = false;
    public $showEditForm = false;
    public $showDeleteConfirm = false;
    public $deleteRoleId = null;
    public $isLoading = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name' . ($this->isEdit ? ',' . $this->roleId : ''),
            'description' => 'nullable|string|max:500',
        ];
    }

    public function openCreateForm()
    {
        Log::info('RoleManagement: openCreateForm button clicked');
        try {
            $this->resetForm();
            $this->showCreateForm = true;
            Log::info('RoleManagement: Create form opened successfully');
        } catch (\Exception $e) {
            Log::error('RoleManagement: Error opening create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function closeCreateForm()
    {
        Log::info('RoleManagement: closeCreateForm button clicked');
        try {
            $this->showCreateForm = false;
            $this->resetForm();
            Log::info('RoleManagement: Create form closed successfully');
        } catch (\Exception $e) {
            Log::error('RoleManagement: Error closing create form', [
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
        $roles = Role::with('permissions')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        // Get statistics for display
        $stats = [
            'total' => Role::count(),
            'rolesWithPermissions' => Role::has('permissions')->count(),
        ];

        $permissions = Permission::all();

        return view('livewire.role-management', compact('roles', 'stats', 'permissions'));
    }

    public function save()
    {
        Log::info('RoleManagement: Save button clicked', [
            'isEdit' => $this->isEdit,
            'roleId' => $this->roleId,
        ]);

        $this->isLoading = true;

        try {
            Log::info('RoleManagement: Starting validation');
            $this->validate();

            $roleData = [
                'name' => $this->name,
                'description' => $this->description,
            ];

            if ($this->isEdit) {
                Log::info('RoleManagement: Updating existing role', ['roleId' => $this->roleId]);
                $role = Role::find($this->roleId);
                $role->update($roleData);
                $message = 'Role updated successfully!';
                Log::info('RoleManagement: Role updated successfully');
            } else {
                Log::info('RoleManagement: Creating new role');
                $role = Role::create($roleData);
                $message = 'Role created successfully!';
                Log::info('RoleManagement: Role created successfully', ['newId' => $role->id]);
            }

            $this->closeCreateForm();
            $this->dispatch('show-toast', ['message' => $message, 'type' => 'success']);
            Log::info('RoleManagement: Save operation completed successfully');

        } catch (\Exception $e) {
            Log::error('RoleManagement: Error during save operation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->dispatch('show-toast', ['message' => 'Error saving role. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteRoleId = $id;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->deleteRoleId = null;
        $this->showDeleteConfirm = false;
    }

    public function delete()
    {
        try {
            $role = Role::findOrFail($this->deleteRoleId);
            
            // Check if role is assigned to any employees
            if ($role->employees()->count() > 0) {
                $this->dispatch('show-toast', [
                    'message' => 'Cannot delete role. It is assigned to ' . $role->employees()->count() . ' employee(s).', 
                    'type' => 'error'
                ]);
                $this->cancelDelete();
                return;
            }
            
            $role->delete();
            
            $this->cancelDelete();
            $this->dispatch('show-toast', ['message' => 'Role deleted successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            Log::error('RoleManagement: Error deleting role', [
                'error' => $e->getMessage(),
                'roleId' => $this->deleteRoleId
            ]);
            $this->dispatch('show-toast', ['message' => 'Error deleting role. Please try again.', 'type' => 'error']);
        }
    }

    public function edit($id)
    {
        Log::info('RoleManagement: Edit button clicked', ['roleId' => $id]);
        try {
            $role = Role::findOrFail($id);
            $this->roleId = $id;
            $this->name = $role->name;
            $this->description = $role->description;
            $this->isEdit = true;
            $this->showCreateForm = true;

            Log::info('RoleManagement: Edit form populated successfully', [
                'roleId' => $id,
            ]);
        } catch (\Exception $e) {
            Log::error('RoleManagement: Error populating edit form', [
                'error' => $e->getMessage(),
                'roleId' => $id
            ]);
            $this->dispatch('show-toast', ['message' => 'Error loading role data.', 'type' => 'error']);
        }
    }

    public function managePermissions($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
            $this->roleId = $id;
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
            $this->showPermissionsModal = true;
        } catch (\Exception $e) {
            Log::error('RoleManagement: Error loading permissions', [
                'error' => $e->getMessage(),
                'roleId' => $id
            ]);
            $this->dispatch('show-toast', ['message' => 'Error loading permissions.', 'type' => 'error']);
        }
    }

    public function savePermissions()
    {
        try {
            $role = Role::findOrFail($this->roleId);
            $role->permissions()->sync($this->selectedPermissions);
            
            $this->showPermissionsModal = false;
            $this->dispatch('show-toast', ['message' => 'Permissions updated successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            Log::error('RoleManagement: Error saving permissions', [
                'error' => $e->getMessage(),
                'roleId' => $this->roleId
            ]);
            $this->dispatch('show-toast', ['message' => 'Error saving permissions. Please try again.', 'type' => 'error']);
        }
    }

    public function resetForm()
    {
        $this->roleId = null;
        $this->name = '';
        $this->description = '';
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    public function updatedSelectedPermissions($value)
    {
        // This method is called when permissions are selected/deselected
        // We can add any logic here if needed
    }
}