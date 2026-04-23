<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $name = $this->faker->unique()->words(3, true);
        $costPrice = $this->faker->numberBetween(5000, 50000);

        return [
            'category_id' => Category::factory(),
            'unit_id' => Unit::factory(),
            'supplier_id' => Supplier::factory(),

            'code' => 'PRD-' . $this->faker->unique()->numberBetween(1000, 9999),
            'barcode' => $this->faker->unique()->ean13(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),

            'cost_price' => $costPrice,
            'selling_price' => $costPrice + $this->faker->numberBetween(1000, 25000),

            'min_stock' => 10,
            'max_stock' => 100,
            'current_stock' => $this->faker->numberBetween(0, 200),

            'is_active' => true,
        ];
    }
}