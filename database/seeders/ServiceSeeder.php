<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Support\RegulatoryServicesCatalog;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (RegulatoryServicesCatalog::all() as $item) {
            Service::updateOrCreate(
                ['name' => $item['name']],
                [
                    'description' => $item['description'],
                    'picture' => null,
                    'features' => $item['features'],
                    'is_active' => true,
                ]
            );
        }
    }
}
