<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            [
                'name' => 'Gudang Utama',
                'code' => 'WH-001',
                'type' => 'warehouse',
            ],
            [
                'name' => 'Rak A',
                'code' => 'RACK-A',
                'type' => 'rack',
            ],
            [
                'name' => 'Rak B',
                'code' => 'RACK-B',
                'type' => 'rack',
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}