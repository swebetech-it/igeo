<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Employee Management
            ['name' => 'view_employees', 'description' => 'View employee list'],
            ['name' => 'create_employees', 'description' => 'Create new employees'],
            ['name' => 'edit_employees', 'description' => 'Edit existing employees'],
            ['name' => 'delete_employees', 'description' => 'Delete employees'],
            
            // Role Management
            ['name' => 'view_roles', 'description' => 'View roles list'],
            ['name' => 'create_roles', 'description' => 'Create new roles'],
            ['name' => 'edit_roles', 'description' => 'Edit existing roles'],
            ['name' => 'delete_roles', 'description' => 'Delete roles'],
            ['name' => 'assign_permissions', 'description' => 'Assign permissions to roles'],
            
            // Permission Management
            ['name' => 'view_permissions', 'description' => 'View permissions list'],
            ['name' => 'create_permissions', 'description' => 'Create new permissions'],
            ['name' => 'edit_permissions', 'description' => 'Edit existing permissions'],
            ['name' => 'delete_permissions', 'description' => 'Delete permissions'],
            
            // Client Management
            ['name' => 'view_clients', 'description' => 'View client list'],
            ['name' => 'create_clients', 'description' => 'Create new clients'],
            ['name' => 'edit_clients', 'description' => 'Edit existing clients'],
            ['name' => 'delete_clients', 'description' => 'Delete clients'],
            
            // Project Management
            ['name' => 'view_projects', 'description' => 'View project list'],
            ['name' => 'create_projects', 'description' => 'Create new projects'],
            ['name' => 'edit_projects', 'description' => 'Edit existing projects'],
            ['name' => 'delete_projects', 'description' => 'Delete projects'],
            
            // Finance Management
            ['name' => 'view_finances', 'description' => 'View financial data'],
            ['name' => 'manage_expenses', 'description' => 'Manage expenses'],
            ['name' => 'manage_income', 'description' => 'Manage income'],
            ['name' => 'view_reports', 'description' => 'View financial reports'],
            
            // Document Management
            ['name' => 'view_documents', 'description' => 'View documents'],
            ['name' => 'upload_documents', 'description' => 'Upload new documents'],
            ['name' => 'delete_documents', 'description' => 'Delete documents'],
            
            // Blog Management
            ['name' => 'view_blogs', 'description' => 'View blog posts'],
            ['name' => 'create_blogs', 'description' => 'Create new blog posts'],
            ['name' => 'edit_blogs', 'description' => 'Edit existing blog posts'],
            ['name' => 'delete_blogs', 'description' => 'Delete blog posts'],
            
            // Service Management
            ['name' => 'view_services', 'description' => 'View services'],
            ['name' => 'create_services', 'description' => 'Create new services'],
            ['name' => 'edit_services', 'description' => 'Edit existing services'],
            ['name' => 'delete_services', 'description' => 'Delete services'],
            
            // Gallery Management
            ['name' => 'view_gallery', 'description' => 'View gallery'],
            ['name' => 'upload_gallery', 'description' => 'Upload new gallery items'],
            ['name' => 'delete_gallery', 'description' => 'Delete gallery items'],
            
            // Experience Management
            ['name' => 'view_experience', 'description' => 'View experience entries'],
            ['name' => 'create_experience', 'description' => 'Create new experience entries'],
            ['name' => 'edit_experience', 'description' => 'Edit existing experience entries'],
            ['name' => 'delete_experience', 'description' => 'Delete experience entries'],
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }
    }
}