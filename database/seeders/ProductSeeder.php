<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Solar Pump X1',
                'short_description' => 'Solar water pump for irrigation.',
                'description' => 'A high-efficiency solar water pump designed for small to medium-sized farms. Easy to install and virtually no running costs.',
                'price' => 1200000.00,
                'stock_quantity' => 10,
                'image' => 'products/solar-pump.png',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Organic Fertilizer',
                'short_description' => 'Natural organic fertilizer.',
                'description' => 'Premium nutrient-rich organic fertilizer that improves soil health and increases crop yield naturally.',
                'price' => 45000.00,
                'stock_quantity' => 50,
                'image' => 'products/organic-fertilizer.png',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Precision Seed Drill',
                'short_description' => 'Automated seed drill.',
                'description' => 'High-precision automated seed drill for optimal spacing and depth control, ensuring maximum germination rates.',
                'price' => 3500000.00,
                'stock_quantity' => 5,
                'image' => 'products/seed-drill.png',
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
