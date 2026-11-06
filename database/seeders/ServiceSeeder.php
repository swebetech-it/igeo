<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing services
        Service::truncate();
        
        $services = [
            // iGEO Agro Services
            [
                'name' => 'Crop Production',
                'description' => 'High-quality commercial farming operations focused on climate-resilient and market-preferred crops. Our agronomists apply best-practice crop management to maximize yield and quality.',
                'picture' => 'crop-production.jpg',
                'features' => [
                    'Commercial Farming',
                    'Climate-Resilient Crops',
                    'Best-Practice Management',
                    'Yield Maximization',
                    'Quality Assurance',
                    'Sustainable Practices'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Agro-Processing',
                'description' => 'Post-harvest handling and value-add processing—cleaning, grading, sorting, packaging, and transformation into higher-value products for retail and industrial customers.',
                'picture' => 'agro-processing.jpg',
                'features' => [
                    'Cleaning & Grading',
                    'sorting & Packaging',
                    'Value Addition',
                    'Retail Readiness',
                    'Industrial Supply',
                    'Quality Control'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Inputs & Distribution',
                'description' => 'Sourcing and distribution of quality seeds, fertilizers, and crop protection products, combined with training and financing solutions for smallholders and commercial farms.',
                'picture' => 'inputs-distribution.jpg',
                'features' => [
                    'Quality Seeds',
                    'Fertilizers',
                    'Crop Protection',
                    'Farmer Training',
                    'Financing Solutions',
                    'Commercial Supply'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Supply Chain & Exports',
                'description' => 'End-to-end logistics, cold chain management, customs and export documentation, and market access services to connect producers with regional and global buyers.',
                'picture' => 'supply-chain.jpg',
                'features' => [
                    'End-to-End Logistics',
                    'Cold Chain Management',
                    'Export Documentation',
                    'Market Access',
                    'Global Connectivity',
                    'Customs Clearance'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Digital Farming & Advisory',
                'description' => 'Farm advisory services, precision agriculture tools, and farm-data analytics to optimize input use, improve traceability, and increase profitability.',
                'picture' => 'digital-farming.jpg',
                'features' => [
                    'Farm Advisory',
                    'Precision Tools',
                    'Data Analytics',
                    'Input Optimization',
                    'Traceability',
                    'Profitability Analysis'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Research & Innovation',
                'description' => 'R&D partnerships to pilot climate-smart agronomy, seed variety trials, and sustainable post-harvest technologies.',
                'picture' => 'research-innovation.jpg',
                'features' => [
                    'R&D Partnerships',
                    'Climate-Smart Agronomy',
                    'Seed Trials',
                    'Post-Harvest Tech',
                    'Sustainable Solutions',
                    'Innovation Pilots'
                ],
                'is_active' => true,
            ],
            
            // iGEO Pharmacy Services
            [
                'name' => 'Patient Access Solutions',
                'description' => 'Includes Expanded Access Programs (EAPs), Named Patient Programs (NPPs), post-trial access support, and real-world data (RWD) generation.',
                'picture' => 'patient-access.jpg',
                'features' => [
                    'Expanded Access Programs',
                    'Named Patient Programs',
                    'Post-Trial Support',
                    'RWD Generation',
                    'Patient Support',
                    'Access Equity'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Pharmacy & Supply Chain',
                'description' => 'Pre-wholesale and wholesale distribution, licensed pharmacy services, and global logistics management across 160+ countries.',
                'picture' => 'pharmacy-supply.jpg',
                'features' => [
                    'Wholesale Distribution',
                    'Licensed Pharmacy',
                    'Global Logistics',
                    '160+ Countries',
                    'Supply Management',
                    'Secure Distribution'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Consulting & Advisory',
                'description' => 'Regulatory strategy, market access consulting, health economics insight, and product launch planning.',
                'picture' => 'consulting-advisory.jpg',
                'features' => [
                    'Regulatory Strategy',
                    'Market Access',
                    'Health Economics',
                    'Product Launch',
                    'Strategic Planning',
                    'Expert Advisory'
                ],
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}