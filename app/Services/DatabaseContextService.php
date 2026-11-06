<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Service;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class DatabaseContextService
{
    public function getBusinessContext()
    {
        $products = Product::active()->get(['name', 'description', 'price', 'stock_quantity']);
        $services = Service::all(['name', 'description']);
        
        $context = "You are a Sales Agent for Igeo. Here is our current inventory and services:\n\n";
        
        $context .= "Products:\n";
        foreach ($products as $product) {
            $context .= "- {$product->name}: {$product->description}. Price: TSh " . number_format($product->price, 2) . ". Stock: {$product->stock_quantity}\n";
        }
        
        $context .= "\nServices:\n";
        foreach ($services as $service) {
            $context .= "- {$service->name}: {$service->description}\n";
        }
        
        return $context;
    }

    public function getSchemaContext()
    {
        return "You can assist users with ordering products. When they want to buy, guide them to the shop page or ask for their details (Name, Phone, Items).";
    }
}
