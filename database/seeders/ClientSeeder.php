<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Metro City Corporation',
                'email' => 'contact@metrocity.gov',
                'phone' => '+1 (555) 123-4567',
                'company' => 'Metro City Government',
                'project_type' => 'Infrastructure Development',
                'status' => 'active',
                'notes' => 'Major client for infrastructure projects including roads and bridges. Located at 123 Government Plaza, Metro City.',
                'is_public' => true,
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'mjohnson@johnsonmail.com',
                'phone' => '+1 (555) 234-5678',
                'company' => 'Johnson Family Trust',
                'project_type' => 'Residential Construction',
                'status' => 'active',
                'notes' => 'Custom luxury home construction with sustainable features. Located at 456 Oak Street, Suburban Heights.',
                'is_public' => false,
            ],
            [
                'name' => 'David Chen',
                'email' => 'facilities@techcore.com',
                'phone' => '+1 (555) 345-6789',
                'company' => 'TechCore Industries',
                'project_type' => 'Commercial Projects',
                'status' => 'active',
                'notes' => 'Modern office complex with smart building technology. Located at 789 Innovation Drive, Tech Park.',
                'is_public' => true,
            ],
            [
                'name' => 'Dr. Sarah Martinez',
                'email' => 'construction@regionalmed.org',
                'phone' => '+1 (555) 456-7890',
                'company' => 'Regional Medical Center',
                'project_type' => 'Healthcare Facility',
                'status' => 'active',
                'notes' => 'Hospital expansion and modernization project. Budget: $10M+. Located at 321 Health Plaza, Medical District.',
                'is_public' => true,
            ],
            [
                'name' => 'Prof. Robert Williams',
                'email' => 'planning@stateuni.edu',
                'phone' => '+1 (555) 567-8901',
                'company' => 'State University',
                'project_type' => 'Educational Building',
                'status' => 'active',
                'notes' => 'New engineering building and research facilities. Located at 654 Campus Drive, University Town.',
                'is_public' => true,
            ],
            [
                'name' => 'Margaret Thompson',
                'email' => 'director@heritage.org',
                'phone' => '+1 (555) 678-9012',
                'company' => 'Heritage Preservation Society',
                'project_type' => 'Renovation & Remodeling',
                'status' => 'completed',
                'notes' => 'Historic building restoration while preserving architectural integrity. Located at 987 Historic Lane, Old Town.',
                'is_public' => true,
            ],
            [
                'name' => 'James Anderson',
                'email' => 'development@greenvalley.com',
                'phone' => '+1 (555) 789-0123',
                'company' => 'Green Valley Developers',
                'project_type' => 'Residential Development',
                'status' => 'active',
                'notes' => 'Sustainable residential community development. Located at 147 Valley Road, Green Valley.',
            ],
            [
                'name' => 'Linda Rodriguez',
                'email' => 'projects@industrial.com',
                'phone' => '+1 (555) 890-1234',
                'company' => 'Industrial Solutions Inc.',
                'project_type' => 'Industrial Facility',
                'status' => 'active',
                'notes' => 'Manufacturing facility with advanced automation systems. Located at 258 Industrial Parkway, Factory District.',
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }

        $this->command->info('Clients seeded successfully!');
    }
}
