<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $category = Category::first();
        $unit = Unit::where('symbol', 'pcs')->first();
        $supplier = Supplier::first();

        $products = [
            [
                'name' => 'Indomie Goreng',
                'cost_price' => 2500,
                'selling_price' => 3000,
                'min_stock' => 10,
                'max_stock' => 100,
                'current_stock' => 50,
            ],
            [
                'name' => 'Aqua 600ml',
                'cost_price' => 3000,
                'selling_price' => 4000,
                'min_stock' => 24,
                'max_stock' => 240,
                'current_stock' => 120,
            ],
            [
                'name' => 'Chitato Sapi Panggang',
                'cost_price' => 8000,
                'selling_price' => 10000,
                'min_stock' => 5,
                'max_stock' => 50,
                'current_stock' => 30,
            ],
            [
                'name' => 'Beras Ramos 5kg',
                'cost_price' => 60000,
                'selling_price' => 70000,
                'min_stock' => 10,
                'max_stock' => 100,
                'current_stock' => 45,
            ],
            [
                'name' => 'Minyak Goreng Bimoli 2L',
                'cost_price' => 35000,
                'selling_price' => 40000,
                'min_stock' => 12,
                'max_stock' => 120,
                'current_stock' => 60,
            ],
        ];

        foreach ($products as $product) {
            $product['category_id'] = $category->id;
            $product['unit_id'] = $unit->id;
            $product['supplier_id'] = $supplier->id;
            $product['is_active'] = true;

            Product::create($product);
        }
    }
}