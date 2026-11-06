<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateOrUpdateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:superadmin 
                            {email=info@igeo.co.tz : Email of the superadmin} 
                            {--name=iGEO Super Admin : Display name} 
                            {--password= : Optional password to set}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or assign Super Administrator role to an account';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $name = $this->option('name');
        $password = $this->option('password');

        $this->info("⚙️  Configuring Superadmin account for: {$email}");

        $user = User::firstOrNew(['email' => $email]);
        $isNew = !$user->exists;

        if ($isNew) {
            $user->name = $name;
            $user->password = Hash::make($password ?: 'password');
            $user->email_verified_at = now();
            $this->line("Creating new user account...");
        } else {
            if ($password) {
                $user->password = Hash::make($password);
                $this->line("Updated account password.");
            }
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
            }
            $this->line("Updating existing user account...");
        }

        $user->role = User::ROLE_SUPERADMIN;
        $user->save();

        // Ensure roles & permissions are configured if tables exist
        if (Schema::hasTable('roles') && Schema::hasTable('permissions')) {
            $superRole = Role::firstOrCreate(
                ['name' => 'superadmin'],
                ['description' => 'Super Administrator with full access to all features']
            );

            if (Schema::hasTable('permission_role')) {
                $permissions = Permission::all();
                if ($permissions->isNotEmpty()) {
                    $superRole->permissions()->sync($permissions->pluck('id'));
                }
            }
        }

        $this->info("✅ Superadmin account configured successfully!");
        $this->table(
            ['ID', 'Name', 'Email', 'Role', 'Status'],
            [[
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $isNew ? 'Created (password: ' . ($password ?: 'password') . ')' : 'Promoted to Superadmin'
            ]]
        );

        return Command::SUCCESS;
    }
}
