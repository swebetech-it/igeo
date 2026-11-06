<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all roles
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();
        $finance = Role::where('name', 'finance')->first();
        $employee = Role::where('name', 'employee')->first();
        
        // Get all permissions
        $permissions = Permission::all();
        
        // Assign all permissions to admin
        if ($admin) {
            $admin->permissions()->sync($permissions->pluck('id'));
        }
        
        // Assign management permissions to manager
        if ($manager) {
            $managerPermissions = Permission::whereIn('name', [
                'view_employees', 'create_employees', 'edit_employees',
                'view_clients', 'create_clients', 'edit_clients', 'delete_clients',
                'view_projects', 'create_projects', 'edit_projects', 'delete_projects',
                'view_documents', 'upload_documents', 'delete_documents',
                'view_blogs', 'create_blogs', 'edit_blogs', 'delete_blogs',
                'view_services', 'create_services', 'edit_services', 'delete_services',
                'view_gallery', 'upload_gallery', 'delete_gallery',
                'view_experience', 'create_experience', 'edit_experience', 'delete_experience',
            ])->pluck('id');
            
            $manager->permissions()->sync($managerPermissions);
        }
        
        // Assign finance permissions to finance role
        if ($finance) {
            $financePermissions = Permission::whereIn('name', [
                'view_finances', 'manage_expenses', 'manage_income', 'view_reports',
            ])->pluck('id');
            
            $finance->permissions()->sync($financePermissions);
        }
        
        // Assign basic permissions to employee
        if ($employee) {
            $employeePermissions = Permission::whereIn('name', [
                'view_projects', 'view_documents', 'view_blogs', 'view_services',
            ])->pluck('id');
            
            $employee->permissions()->sync($employeePermissions);
        }
    }
}