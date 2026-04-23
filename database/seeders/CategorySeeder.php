<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Makanan', 'color' => '#10B981'],
            ['name' => 'Minuman', 'color' => '#3B82F6'],
            ['name' => 'Snack', 'color' => '#F59E0B'],
            ['name' => 'Sembako', 'color' => '#EF4444'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'color' => $category['color'],
                'is_active' => true
            ]);
        }
    }
}