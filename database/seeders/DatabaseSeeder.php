<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed all data
        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            ExperienceSeeder::class,
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            CareerSeeder::class,
        ]);
    }
}