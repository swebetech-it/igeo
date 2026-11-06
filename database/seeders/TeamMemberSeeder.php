<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeamMember::truncate();

        $members = [
            [
                'name' => 'Dr. Sarah Mlewa',
                'position' => 'Chief Agronomist',
                'bio' => 'PhD in Plant Science with 15 years experienced in precision agriculture and soil health monitoring.',
                'image_path' => null, // or add a placeholder path if available
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'James K. Mwangi',
                'position' => 'Head of Pharma Logistics',
                'bio' => 'Expert in GDP-compliant cold chain management and pharmaceutical supply chain optimization.',
                'image_path' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Aisha Bakari',
                'position' => 'Sustainability Director',
                'bio' => 'Leading our initiatives in regenerative farming and carbon footprint reduction.',
                'image_path' => null,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Eng. David Juma',
                'position' => 'IoT Systems Architect',
                'bio' => 'Designing the sensor networks that power our real-time field monitoring systems.',
                'image_path' => null,
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($members as $member) {
            TeamMember::create($member);
        }
    }
}
