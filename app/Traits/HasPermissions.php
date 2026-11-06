<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

trait HasPermissions
{
    /**
     * Check if the authenticated user has a specific permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        // If user is admin, they have all permissions
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has an employee record
        if ($user->employee) {
            // Check if employee's role has the permission
            return $user->employee->role->hasPermission($permission);
        }
        
        // Fallback to user role if no employee record
        switch ($user->role) {
            case User::ROLE_MANAGER:
                // Managers have most permissions except admin-specific ones
                return in_array($permission, [
                    'view_employees', 'create_employees', 'edit_employees',
                    'view_clients', 'create_clients', 'edit_clients', 'delete_clients',
                    'view_projects', 'create_projects', 'edit_projects', 'delete_projects',
                    'view_documents', 'upload_documents', 'delete_documents',
                    'view_blogs', 'create_blogs', 'edit_blogs', 'delete_blogs',
                    'view_services', 'create_services', 'edit_services', 'delete_services',
                    'view_gallery', 'upload_gallery', 'delete_gallery',
                    'view_experience', 'create_experience', 'edit_experience', 'delete_experience',
                    'view_finances', 'manage_expenses', 'manage_income', 'view_reports',
                ]);
                
            case User::ROLE_FINANCE:
                // Finance users have finance-related permissions
                return in_array($permission, [
                    'view_finances', 'manage_expenses', 'manage_income', 'view_reports',
                ]);
                
            default:
                return false;
        }
    }
    
    /**
     * Check if the authenticated user has any of the specified permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission($permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if the authenticated user has all of the specified permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Authorize a user for a specific permission, aborting if not authorized
     *
     * @param string $permission
     * @param string $message
     * @param int $code
     * @return void
     */
    public function authorizePermission($permission, $message = 'Unauthorized access', $code = 403)
    {
        if (!$this->hasPermission($permission)) {
            abort($code, $message);
        }
    }
    
    /**
     * Authorize a user for any of the specified permissions, aborting if not authorized
     *
     * @param array $permissions
     * @param string $message
     * @param int $code
     * @return void
     */
    public function authorizeAnyPermission($permissions, $message = 'Unauthorized access', $code = 403)
    {
        if (!$this->hasAnyPermission($permissions)) {
            abort($code, $message);
        }
    }
}