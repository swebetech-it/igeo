<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class DemoProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::updateOrCreate(
            ['name' => 'Solar Pump X1'],
            [
                'description' => 'High-efficiency solar water pump for smart irrigation.',
                'short_description' => 'Solar water pump for irrigation.',
                'price' => 1200000,
                'is_active' => true,
                'stock_quantity' => 10,
                'order' => 1
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Organic Fertilizer'],
            [
                'description' => '100% natural organic fertilizer for sustainable farming.',
                'short_description' => 'Natural organic fertilizer.',
                'price' => 45000,
                'is_active' => true,
                'stock_quantity' => 50,
                'order' => 2
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Precision Seed Drill'],
            [
                'description' => 'Automated seed drill for precise planting and high yield.',
                'short_description' => 'Automated seed drill.',
                'price' => 3500000,
                'is_active' => true,
                'stock_quantity' => 5,
                'order' => 3
            ]
        );
    }
}
