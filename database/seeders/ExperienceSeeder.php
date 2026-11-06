<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Experience;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $experiences = [
            [
                'year' => 2024,
                'title' => 'Major Infrastructure Contract',
                'description' => 'Awarded multi-million dollar highway expansion project, demonstrating our capacity for large-scale infrastructure development and reinforcing our position as industry leaders.',
                'category' => 'achievement',
            ],
            [
                'year' => 2023,
                'title' => 'Sustainability Certification',
                'description' => 'Achieved LEED Gold certification for our green building practices and unwavering commitment to environmental responsibility and sustainable construction methods.',
                'category' => 'certification',
            ],
            [
                'year' => 2022,
                'title' => 'Company Expansion',
                'description' => 'Opened new regional offices and expanded our team to 50+ skilled professionals across multiple disciplines, enhancing our service capabilities nationwide.',
                'category' => 'expansion',
            ],
            [
                'year' => 2021,
                'title' => 'Digital Innovation',
                'description' => 'Implemented advanced BIM technology and AI-powered project management systems, revolutionizing our design and construction processes.',
                'category' => 'innovation',
            ],
            [
                'year' => 2020,
                'title' => 'Excellence Award',
                'description' => 'Received the Construction Excellence Award for outstanding project delivery, innovation in construction methods, and exceptional client satisfaction rates.',
                'category' => 'award',
            ],
            [
                'year' => 2019,
                'title' => 'Healthcare Specialization',
                'description' => 'Developed specialized expertise in healthcare facility construction, completing multiple hospital and medical center projects.',
                'category' => 'specialization',
            ],
            [
                'year' => 2018,
                'title' => 'Safety Excellence',
                'description' => 'Achieved zero-accident record for the year while maintaining high productivity standards across all project sites.',
                'category' => 'safety',
            ],
            [
                'year' => 2017,
                'title' => 'Regional Expansion',
                'description' => 'Expanded operations to serve multiple states, establishing iGEO as a regional leader in construction services.',
                'category' => 'expansion',
            ],
            [
                'year' => 2015,
                'title' => 'Technology Innovation',
                'description' => 'Implemented cutting-edge BIM technology and comprehensive digital project management systems to enhance efficiency and project coordination.',
                'category' => 'technology',
            ],
            [
                'year' => 2010,
                'title' => 'Company Founded',
                'description' => 'iGEO was established with a clear vision to deliver innovative construction solutions, exceptional service, and lasting partnerships with our clients.',
                'category' => 'milestone',
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::create($experience);
        }
    }
}
