<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Create a default Company and User for seeding
        $company = \App\Models\Company::create([
            'name' => 'Demo Company',
            'email' => 'company@demo.com',
            'phone' => '08123456789',
            'address' => 'Jl. Demo No. 123',
        ]);

        $user = \App\Models\User::create([
            'name' => 'Demo Admin',
            'email' => 'demo@stock-umkm.test',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'company_id' => $company->id,
        ]);

        // 2. Login the user so BelongsToCompany trait works automatically
        auth()->login($user);

        // 3. Run the rest of the seeders
        $this->call([
            CategorySeeder::class,
            UnitSeeder::class,
            LocationSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            DemoSeeder::class,
        ]);
    }
}