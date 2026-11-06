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
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@igeo.co.tz'],
            [
                'name' => 'DSS Admin',
                'email' => 'admin@igeo.co.tz',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => User::ROLE_ADMIN,
            ]
        );

        // Create iGEO Admin user
        $igeoAdmin = User::updateOrCreate(
            ['email' => 'info@igeo.co.tz'],
            [
                'name' => 'iGEO Admin',
                'email' => 'info@igeo.co.tz',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => User::ROLE_ADMIN,
            ]
        );

    
        $this->command->info('Users seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@igeo.co.tz / password');
        $this->command->info('iGEO Admin: info@igeo.co.tz / password');
        $this->command->info('Demo: demo@igeo.co.tz / demo123');
        $this->command->info('Test: test@example.com / password');
    }
}