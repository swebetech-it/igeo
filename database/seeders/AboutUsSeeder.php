<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutUs::truncate();
        
        AboutUs::create([
            'title' => 'iGEO AGRO COMPANY LIMITED',
            'content' => "iGEO AGRO COMPANY LIMITED is an integrated agribusiness focused on delivering sustainable, innovative and high-quality agricultural products and services across regional and international markets. We combine farm-level expertise, modern agro-processing, and reliable supply chain solutions to support farmers, institutions and commercial partners from seed to shelf.\n\nCore Services:\n• Crop Production: High-quality commercial farming operations, focused on climate resilient and market-preferred crops.\n• Agro-Processing: Post-harvest handling and value-add processing — cleaning, grading, sorting, packaging, and transformation.\n• Inputs & Distribution: Sourcing and distribution of quality seeds, fertilizers, and crop protection products.\n• Supply Chain & Exports: End-to-end logistics, cold chain management, customs and export documentation.\n• Digital Farming & Advisory: Farm advisory services, precision agriculture tools, and farm-data analytics.\n• Research & Innovation: R&D partnerships to pilot climate-smart agronomy.\n\nSupply Chain & Infrastructure:\niGEO AGRO operates a network of collection centers, processing facilities and logistics partners designed to ensure timely movement of produce from farms to markets.\n\nSustainability & Social Impact:\nSustainability is embedded in our operations. We promote regenerative farming practices, water-efficient irrigation, and reduction of food loss.\n\nContact:\nEmail: info@igeo.com\nPhone: +255713035438",
            'mission' => 'To transform agriculture through responsible production, value-added processing, trusted supply chains, and digital innovation that improves yields, livelihoods and food security.',
            'vision' => 'To be a leading force in sustainable agribusiness — empowering farmers, preserving ecosystems, and delivering nutritious produce to communities worldwide.',
            'values' => 'People-first approach and employee development • Entrepreneurial spirit and adaptability • Trust, transparency and ethical conduct • Commitment to delivering impact',
            'founded_year' => 2018,
            'employees_count' => 120,
            'projects_completed' => 450,
            'clients_served' => 85,
            'is_active' => true,
        ]);

        AboutUs::create([
            'title' => 'iGEO Pharmacy and Consulting',
            'content' => "iGEO Pharmacy and Consulting is a forward-thinking healthcare partner dedicated to improving patient access to medicines and healthcare solutions worldwide. With expertise in pharmaceutical distribution, consulting, and patient support programs, iGEO bridges the gap between innovative therapies, healthcare providers, and patients in need.\n\nOur Services:\nPatient Access Solutions:\n• Expanded Access Programs (EAPs) and Named Patient Programs (NPPs)\n• Post-trial access support and Real-world data generation\n\nPharmacy & Supply Chain Services:\n• Pre-wholesale and wholesale distribution\n• Licensed pharmacy services and Global logistics across 160+ countries\n\nConsulting & Advisory:\n• Regulatory strategy, Market access consulting, Product launch planning\n\nWhy Choose iGEO?\n• Global Reach • Therapeutic Expertise • Patient-Centered Approach",
            'mission' => 'To ensure equitable access to quality medicines and empower healthcare organizations with tailored consulting solutions.',
            'vision' => 'To be a global leader in pharmaceutical access and healthcare consulting, bridging gaps in care.',
            'values' => 'Integrity • Equity & Access • Collaboration • Sustainability',
            'founded_year' => 2020,
            'employees_count' => 50,
            'projects_completed' => 120,
            'clients_served' => 200,
            'is_active' => true,
        ]);
    }
}
