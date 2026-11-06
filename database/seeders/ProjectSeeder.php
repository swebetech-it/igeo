<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Client;
use App\Models\Income;
use App\Models\Expense;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get clients to assign projects to
        $clients = Client::all();

        if ($clients->isEmpty()) {
            // Create a few basic clients if none exist
            $client1 = Client::create([
                'name' => 'ABC Construction Corp',
                'email' => 'contact@abcconstruction.com',
                'phone' => '+1 (555) 123-4567',
                'company' => 'ABC Construction Corp',
                'project_type' => 'Commercial',
                'status' => 'active'
            ]);

            $client2 = Client::create([
                'name' => 'Metro City Government',
                'email' => 'projects@metrocity.gov',
                'phone' => '+1 (555) 987-6543',
                'company' => 'Metro City Government',
                'project_type' => 'Infrastructure',
                'status' => 'active'
            ]);

            $clients = collect([$client1, $client2]);
        }

        // Create sample projects
        $projects = [
            [
                'client_id' => $clients->random()->id,
                'name' => 'Downtown Office Complex',
                'description' => '15-story modern office building with underground parking and retail space.',
                'type' => 'commercial',
                'status' => 'active',
                'budget' => 2500000.00,
                'actual_cost' => 1850000.00,
                'start_date' => '2024-01-15',
                'end_date' => '2025-12-31',
                'location' => '123 Main Street, Downtown',
                'project_manager' => 'John Smith',
                'progress_percentage' => 65,
                'notes' => 'Project progressing ahead of schedule.'
            ],
            [
                'client_id' => $clients->random()->id,
                'name' => 'Residential Housing Development',
                'description' => '50-unit residential complex with amenities.',
                'type' => 'residential',
                'status' => 'active',
                'budget' => 1800000.00,
                'actual_cost' => 1200000.00,
                'start_date' => '2024-06-01',
                'end_date' => '2025-08-30',
                'location' => 'Greenfield Estates',
                'project_manager' => 'Sarah Johnson',
                'progress_percentage' => 45,
                'notes' => 'Weather delays in foundation work.'
            ],
            [
                'client_id' => $clients->random()->id,
                'name' => 'Highway Bridge Reconstruction',
                'description' => 'Complete reconstruction of Highway 101 bridge over Metro River.',
                'type' => 'infrastructure',
                'status' => 'completed',
                'budget' => 3200000.00,
                'actual_cost' => 3150000.00,
                'start_date' => '2023-03-01',
                'end_date' => '2024-02-28',
                'completion_date' => '2024-02-15',
                'location' => 'Highway 101, Metro River Crossing',
                'project_manager' => 'Mike Davis',
                'progress_percentage' => 100,
                'notes' => 'Project completed under budget and ahead of schedule.'
            ],
            [
                'client_id' => $clients->random()->id,
                'name' => 'Industrial Warehouse Facility',
                'description' => 'Large-scale warehouse and distribution center.',
                'type' => 'industrial',
                'status' => 'planning',
                'budget' => 4500000.00,
                'actual_cost' => 0.00,
                'start_date' => '2025-01-15',
                'end_date' => '2026-06-30',
                'location' => 'Industrial Park East',
                'project_manager' => 'Lisa Wilson',
                'progress_percentage' => 0,
                'notes' => 'Permits pending approval.'
            ]
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Create sample incomes for each project
            if ($project->status !== 'planning') {
                $incomes = [
                    [
                        'project_id' => $project->id,
                        'name' => 'Initial Payment',
                        'description' => 'First milestone payment',
                        'type' => 'initial_deposit',
                        'amount' => $project->budget * 0.3,
                        'date' => $project->start_date,
                        'due_date' => $project->start_date,
                        'status' => 'received',
                        'received_amount' => $project->budget * 0.3,
                        'received_date' => $project->start_date,
                        'payment_method' => 'bank_transfer'
                    ],
                    [
                        'project_id' => $project->id,
                        'name' => 'Progress Payment 1',
                        'description' => 'First progress payment',
                        'type' => 'milestone_payment',
                        'amount' => $project->budget * 0.25,
                        'date' => date('Y-m-d', strtotime($project->start_date . ' +3 months')),
                        'due_date' => date('Y-m-d', strtotime($project->start_date . ' +3 months')),
                        'status' => $project->progress_percentage > 30 ? 'received' : 'pending',
                        'received_amount' => $project->progress_percentage > 30 ? $project->budget * 0.25 : 0,
                        'received_date' => $project->progress_percentage > 30 ? date('Y-m-d', strtotime($project->start_date . ' +3 months')) : null,
                        'payment_method' => 'bank_transfer'
                    ]
                ];

                foreach ($incomes as $incomeData) {
                    Income::create($incomeData);
                }

                // Create sample expenses for each project
                $expenses = [
                    [
                        'project_id' => $project->id,
                        'name' => 'Construction Materials',
                        'description' => 'Steel, concrete, and other building materials',
                        'category' => 'materials',
                        'amount' => $project->actual_cost * 0.4,
                        'date' => $project->start_date,
                        'vendor' => 'Metro Supply Co.',
                        'payment_method' => 'bank_transfer',
                        'status' => 'paid'
                    ],
                    [
                        'project_id' => $project->id,
                        'name' => 'Labor Costs',
                        'description' => 'Construction crew wages',
                        'category' => 'labor',
                        'amount' => $project->actual_cost * 0.35,
                        'date' => date('Y-m-d', strtotime($project->start_date . ' +1 month')),
                        'vendor' => 'Construction Workers Union',
                        'payment_method' => 'check',
                        'status' => $project->progress_percentage > 20 ? 'paid' : 'pending'
                    ],
                    [
                        'project_id' => $project->id,
                        'name' => 'Equipment Rental',
                        'description' => 'Heavy machinery and equipment rental',
                        'category' => 'equipment',
                        'amount' => $project->actual_cost * 0.15,
                        'date' => date('Y-m-d', strtotime($project->start_date . ' +2 months')),
                        'vendor' => 'Heavy Equipment Rentals Inc.',
                        'payment_method' => 'credit_card',
                        'status' => $project->progress_percentage > 40 ? 'paid' : 'pending'
                    ]
                ];

                foreach ($expenses as $expenseData) {
                    Expense::create($expenseData);
                }
            }
        }

        $this->command->info('Projects seeded successfully with sample incomes and expenses!');
        $this->command->info('Total projects created: ' . count($projects));
        $this->command->info('Sample financial data has been generated for testing the dashboard.');
    }
}
