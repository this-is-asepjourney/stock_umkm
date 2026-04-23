<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'code' => 'SUP-' . $this->faker->unique()->numberBetween(100, 999),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'address' => $this->faker->address(),
            'contact_person' => $this->faker->name(),
            'payment_term' => $this->faker->randomElement(['Cash', 'Net 30', 'Net 60']),
            'is_active' => true,
        ];
    }
}