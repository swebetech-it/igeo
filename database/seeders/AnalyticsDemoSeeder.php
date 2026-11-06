<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Client;
use App\Models\Role;
use Carbon\Carbon;

class AnalyticsDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create a demo client for project association
        $client = Client::first() ?? Client::create([
            'name' => 'Demo Client',
            'email' => 'client@example.com',
            'phone' => '1234567890',
            'company' => 'Demo Company',
            'status' => 'active'
        ]);

        // Create Projects (attach client_id)
        $projects = collect([
            ['name' => 'Mall Renovation', 'type' => 'construction', 'status' => 'active', 'budget' => 500000, 'start_date' => now()->subMonths(4), 'client_id' => $client->id],
            ['name' => 'Bridge Construction', 'type' => 'infrastructure', 'status' => 'active', 'budget' => 1200000, 'start_date' => now()->subMonths(6), 'client_id' => $client->id],
            ['name' => 'Warehouse Build', 'type' => 'construction', 'status' => 'completed', 'budget' => 300000, 'start_date' => now()->subMonths(8), 'end_date' => now()->subMonth(), 'client_id' => $client->id],
        ])->map(function($p) {
            return Project::firstOrCreate(['name' => $p['name']], $p);
        });

        // Ensure base roles exist
        $engineer = Role::firstOrCreate(['name' => 'Engineer']);
        $supervisor = Role::firstOrCreate(['name' => 'Supervisor']);
        $foreman = Role::firstOrCreate(['name' => 'Foreman']);
        $technician = Role::firstOrCreate(['name' => 'Technician']);

        // Create Employees with role_id
        $employees = collect([
            ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'status' => 'active', 'position' => 'Engineer', 'role_id' => $engineer->id],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane@example.com', 'status' => 'active', 'position' => 'Supervisor', 'role_id' => $supervisor->id],
            ['first_name' => 'Ali', 'last_name' => 'Khan', 'email' => 'ali@example.com', 'status' => 'active', 'position' => 'Foreman', 'role_id' => $foreman->id],
            ['first_name' => 'Mary', 'last_name' => 'Lee', 'email' => 'mary@example.com', 'status' => 'inactive', 'position' => 'Technician', 'role_id' => $technician->id],
        ])->map(function($e) {
            return Employee::firstOrCreate(['email' => $e['email']], $e);
        });

        // Assign employees to projects
        $projects[0]->employees()->syncWithoutDetaching([
            $employees[0]->id => ['role' => 'Engineer', 'assigned_at' => now()->subMonths(3)],
            $employees[1]->id => ['role' => 'Supervisor', 'assigned_at' => now()->subMonths(3)],
        ]);
        $projects[1]->employees()->syncWithoutDetaching([
            $employees[0]->id => ['role' => 'Engineer', 'assigned_at' => now()->subMonths(5)],
            $employees[2]->id => ['role' => 'Foreman', 'assigned_at' => now()->subMonths(5)],
        ]);
        $projects[2]->employees()->syncWithoutDetaching([
            $employees[1]->id => ['role' => 'Supervisor', 'assigned_at' => now()->subMonths(7)],
        ]);

        // Income type pool (matches Income::getTypes keys)
        $incomeTypes = [
            'initial_deposit',
            'milestone_payment',
            'progress_payment',
            'final_payment',
            'change_order',
            'bonus',
            'retention_release',
            'other'
        ];

        $paymentMethods = ['cash','check','credit_card','bank_transfer','financing','wire_transfer'];

        // Generate Income and Expenses for last 90 days
        foreach ($projects as $project) {
            for ($i = 0; $i < 90; $i++) {
                $date = Carbon::now()->subDays(90 - $i);
                // Some days income
                if (rand(0, 2) === 1) {
                    $amount = rand(2000, 20000);
                    $received = min(rand(1000, 15000), $amount);
                    $type = $incomeTypes[array_rand($incomeTypes)];
                    Income::create([
                        'project_id' => $project->id,
                        'name' => ucfirst(str_replace('_',' ', $type)).' - '.$project->name.' '.$date->format('Ymd'),
                        'type' => $type,
                        'amount' => $amount,
                        'received_amount' => $received,
                        'status' => $received >= $amount ? 'received' : 'partial',
                        'date' => $date,
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'invoice_number' => 'INV-'.strtoupper(substr(md5($project->id.$date->format('Ymd').$type),0,8)),
                        'due_date' => $date->copy()->addDays(rand(5,30)),
                    ]);
                }
                // Expenses almost daily
                $category = ['Labor','Equipment','Materials','Transport'][rand(0,3)];
                Expense::create([
                    'project_id' => $project->id,
                    'name' => $category.' Expense - '.$project->name.' '.$date->format('Ymd'),
                    'amount' => rand(1000, 8000),
                    'category' => $category,
                    'date' => $date,
                ]);
            }
        }
    }
}
