<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use App\Traits\HasPermissions;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeManagement extends Component
{
    use WithPagination, HasPermissions;

    public $first_name, $last_name, $email, $phone, $position, $department, $user_id, $role_id, $hire_date, $status = 'active';
    public $employeeId;
    public $isEdit = false;
    public $search = '';
    public $statusFilter = '';
    public $departmentFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // For user account creation
    public $createUserAccount = false;
    public $userName, $userPassword, $userPasswordConfirmation;
    
    // UI state
    public $showCreateForm = false;
    public $showEditForm = false;
    public $showDeleteConfirm = false;
    public $deleteEmployeeId = null;
    public $isLoading = false;

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email' . ($this->isEdit ? ',' . $this->employeeId : ''),
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            
            // User account rules (only when creating user account)
            'userName' => 'required_if:createUserAccount,true|string|max:255|unique:users,name',
            'userPassword' => 'required_if:createUserAccount,true|string|min:8|confirmed',
        ];
    }

    protected function messages()
    {
        return [
            'role_id.required' => 'The role field is required.',
            'role_id.exists' => 'The selected role is invalid.',
            'userName.unique' => 'This username is already taken.',
            'userPassword.confirmed' => 'The password confirmation does not match.',
        ];
    }

    public function mount()
    {
        // Check if user has permission to view employees
        $this->authorizePermission('view_employees');
        
        // Initialize with default values
        $this->hire_date = now()->format('Y-m-d');
    }

    public function openCreateForm()
    {
        // Check if user has permission to create employees
        $this->authorizePermission('create_employees');
        
        Log::info('EmployeeManagement: openCreateForm button clicked');
        try {
            $this->resetForm();
            $this->showCreateForm = true;
            Log::info('EmployeeManagement: Create form opened successfully');
        } catch (\Exception $e) {
            Log::error('EmployeeManagement: Error opening create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function closeCreateForm()
    {
        Log::info('EmployeeManagement: closeCreateForm button clicked');
        try {
            $this->showCreateForm = false;
            $this->resetForm();
            Log::info('EmployeeManagement: Create form closed successfully');
        } catch (\Exception $e) {
            Log::error('EmployeeManagement: Error closing create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $employees = Employee::with(['user', 'role'])
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('position', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->departmentFilter, function ($query) {
                $query->where('department', $this->departmentFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        // Get statistics for display
        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('status', 'active')->count(),
            'inactive' => Employee::where('status', 'inactive')->count(),
        ];

        $roles = Role::all();
        $departments = Employee::select('department')->whereNotNull('department')->distinct()->pluck('department');

        return view('livewire.employee-management', compact('employees', 'stats', 'roles', 'departments'));
    }

    public function save()
    {
        // Check permissions based on whether we're creating or editing
        if ($this->isEdit) {
            $this->authorizePermission('edit_employees');
        } else {
            $this->authorizePermission('create_employees');
        }
        
        Log::info('EmployeeManagement: Save button clicked', [
            'isEdit' => $this->isEdit,
            'employeeId' => $this->employeeId,
        ]);

        $this->isLoading = true;

        try {
            Log::info('EmployeeManagement: Starting validation');
            
            // Validate the employee data
            $this->validate();

            $employeeData = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'position' => $this->position,
                'department' => $this->department,
                'role_id' => $this->role_id,
                'hire_date' => $this->hire_date,
                'status' => $this->status,
            ];

            DB::beginTransaction();

            if ($this->isEdit) {
                Log::info('EmployeeManagement: Updating existing employee', ['employeeId' => $this->employeeId]);
                $employee = Employee::find($this->employeeId);
                $employee->update($employeeData);
                
                // Update associated user if needed
                if ($this->user_id && $employee->user) {
                    $employee->user->update([
                        'name' => $this->first_name . ' ' . $this->last_name,
                        'email' => $this->email,
                        'role' => $this->getRoleConstant($this->role_id),
                    ]);
                }
                
                $message = 'Employee updated successfully!';
                Log::info('EmployeeManagement: Employee updated successfully');
            } else {
                Log::info('EmployeeManagement: Creating new employee');
                $employee = Employee::create($employeeData);
                
                // Create user account if requested
                if ($this->createUserAccount) {
                    $user = User::create([
                        'name' => $this->userName ?: ($this->first_name . ' ' . $this->last_name),
                        'email' => $this->email,
                        'password' => Hash::make($this->userPassword),
                        'role' => $this->getRoleConstant($this->role_id),
                    ]);
                    
                    // Associate user with employee
                    $employee->update(['user_id' => $user->id]);
                }
                
                $message = 'Employee created successfully!';
                Log::info('EmployeeManagement: Employee created successfully', ['newId' => $employee->id]);
            }

            DB::commit();

            $this->closeCreateForm();
            $this->dispatch('show-toast', ['message' => $message, 'type' => 'success']);
            Log::info('EmployeeManagement: Save operation completed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('EmployeeManagement: Error during save operation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->dispatch('show-toast', ['message' => 'Error saving employee. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }

    private function getRoleConstant($roleId)
    {
        $role = Role::find($roleId);
        if (!$role) return User::ROLE_USER;
        
        // Map role names to User model constants
        switch (strtolower($role->name)) {
            case 'admin':
                return User::ROLE_ADMIN;
            case 'manager':
                return User::ROLE_MANAGER;
            case 'finance':
                return User::ROLE_FINANCE;
            default:
                return User::ROLE_USER;
        }
    }

    public function confirmDelete($id)
    {
        // Check if user has permission to delete employees
        $this->authorizePermission('delete_employees');
        
        $this->deleteEmployeeId = $id;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->deleteEmployeeId = null;
        $this->showDeleteConfirm = false;
    }

    public function delete()
    {
        // Check if user has permission to delete employees
        $this->authorizePermission('delete_employees');
        
        try {
            $employee = Employee::findOrFail($this->deleteEmployeeId);
            
            // Delete associated user account if exists
            if ($employee->user_id) {
                User::destroy($employee->user_id);
            }
            
            $employee->delete();
            
            $this->cancelDelete();
            $this->dispatch('show-toast', ['message' => 'Employee deleted successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            Log::error('EmployeeManagement: Error deleting employee', [
                'error' => $e->getMessage(),
                'employeeId' => $this->deleteEmployeeId
            ]);
            $this->dispatch('show-toast', ['message' => 'Error deleting employee. Please try again.', 'type' => 'error']);
        }
    }

    public function edit($id)
    {
        // Check if user has permission to edit employees
        $this->authorizePermission('edit_employees');
        
        Log::info('EmployeeManagement: Edit button clicked', ['employeeId' => $id]);
        try {
            $employee = Employee::with('user')->findOrFail($id);
            $this->employeeId = $id;
            $this->first_name = $employee->first_name;
            $this->last_name = $employee->last_name;
            $this->email = $employee->email;
            $this->phone = $employee->phone;
            $this->position = $employee->position;
            $this->department = $employee->department;
            $this->role_id = $employee->role_id;
            $this->hire_date = $employee->hire_date ? $employee->hire_date->format('Y-m-d') : null;
            $this->status = $employee->status;
            $this->user_id = $employee->user_id;
            
            // Pre-populate user account info if exists
            if ($employee->user) {
                $this->userName = $employee->user->name;
            }
            
            $this->isEdit = true;
            $this->showCreateForm = true;

            Log::info('EmployeeManagement: Edit form populated successfully', [
                'employeeId' => $id,
            ]);
        } catch (\Exception $e) {
            Log::error('EmployeeManagement: Error populating edit form', [
                'error' => $e->getMessage(),
                'employeeId' => $id
            ]);
            $this->dispatch('show-toast', ['message' => 'Error loading employee data.', 'type' => 'error']);
        }
    }

    public function resetForm()
    {
        $this->employeeId = null;
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->phone = '';
        $this->position = '';
        $this->department = '';
        $this->role_id = '';
        $this->hire_date = now()->format('Y-m-d');
        $this->status = 'active';
        $this->user_id = null;
        $this->isEdit = false;
        
        // Reset user account fields
        $this->createUserAccount = false;
        $this->userName = '';
        $this->userPassword = '';
        $this->userPasswordConfirmation = '';
        
        $this->resetErrorBag();
    }

    public function updatedCreateUserAccount($value)
    {
        if ($value) {
            // Pre-populate username with employee name when creating user account
            $this->userName = $this->first_name . ' ' . $this->last_name;
        }
    }

    public function updatedFirstName($value)
    {
        if (!$this->isEdit && $this->createUserAccount) {
            // Update username when first name changes (only when creating)
            $this->userName = $value . ' ' . $this->last_name;
        }
    }

    public function updatedLastName($value)
    {
        if (!$this->isEdit && $this->createUserAccount) {
            // Update username when last name changes (only when creating)
            $this->userName = $this->first_name . ' ' . $value;
        }
    }
}