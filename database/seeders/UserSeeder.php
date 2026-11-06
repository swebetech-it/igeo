<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update Super Admin: info@igeo.co.tz
        $superAdmin = User::firstOrNew(['email' => 'info@igeo.co.tz']);
        $superAdmin->name = $superAdmin->name ?: 'iGEO Super Admin';
        if (!$superAdmin->exists) {
            $superAdmin->password = Hash::make('password');
        }
        $superAdmin->email_verified_at = $superAdmin->email_verified_at ?: now();
        $superAdmin->role = User::ROLE_SUPERADMIN;
        $superAdmin->save();

        // Create or update Admin user: admin@igeo.co.tz
        $admin = User::firstOrNew(['email' => 'admin@igeo.co.tz']);
        $admin->name = $admin->name ?: 'iGEO Admin';
        if (!$admin->exists) {
            $admin->password = Hash::make('password');
        }
        $admin->email_verified_at = $admin->email_verified_at ?: now();
        $admin->role = User::ROLE_ADMIN;
        $admin->save();

        $this->command->info('Users seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Super Admin: info@igeo.co.tz / password (if newly created)');
        $this->command->info('Admin: admin@igeo.co.tz / password (if newly created)');
    }
}
