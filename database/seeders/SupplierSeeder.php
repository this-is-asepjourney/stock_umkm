<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => 'PT Sumber Makmur',
                'phone' => '021-5551234',
                'email' => 'sales@sumbermakmur.co.id',
                'address' => 'Jl. Industri No. 123, Jakarta',
                'contact_person' => 'Budi Santoso',
            ],
            [
                'name' => 'CV Berkah Jaya',
                'phone' => '031-7775678',
                'email' => 'info@berkahjaya.com',
                'address' => 'Jl. Raya Surabaya No. 45, Surabaya',
                'contact_person' => 'Ahmad Rizki',
            ],
            [
                'name' => 'UD Maju Bersama',
                'phone' => '022-8889012',
                'email' => 'order@majubersama.com',
                'address' => 'Jl. Bandung Indah No. 78, Bandung',
                'contact_person' => 'Siti Aminah',
            ],
        ];

        foreach ($suppliers as $supplier) {
            $supplier['code'] = Supplier::generateCode();
            Supplier::create($supplier);
        }
    }
}