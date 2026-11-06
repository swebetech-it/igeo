<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'System Administrator with full access'],
            ['name' => 'manager', 'description' => 'Manager with access to most features'],
            ['name' => 'finance', 'description' => 'Finance Officer with financial access'],
            ['name' => 'employee', 'description' => 'Regular employee with limited access'],
        ];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}