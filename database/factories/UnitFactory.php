<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition()
    {
        $units = [
            ['name' => 'Pieces', 'symbol' => 'pcs'],
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Liter', 'symbol' => 'L'],
            ['name' => 'Box', 'symbol' => 'box'],
            ['name' => 'Pack', 'symbol' => 'pack'],
        ];

        $unit = $this->faker->randomElement($units);

        return [
            'name' => $unit['name'],
            'symbol' => $unit['symbol'],
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}