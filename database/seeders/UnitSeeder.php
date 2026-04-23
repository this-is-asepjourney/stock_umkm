<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $units = [
            ['name' => 'Pieces', 'symbol' => 'pcs'],
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Liter', 'symbol' => 'L'],
            ['name' => 'Box', 'symbol' => 'box'],
            ['name' => 'Pack', 'symbol' => 'pack'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}