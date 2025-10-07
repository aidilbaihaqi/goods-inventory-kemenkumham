<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companies = [
            'PT Mitra Teknologi Indonesia',
            'CV Sumber Rejeki Mandiri',
            'PT Karya Bersama Nusantara',
            'UD Harapan Jaya',
            'PT Solusi Prima Indonesia',
            'CV Berkah Abadi',
            'PT Cahaya Terang Sejahtera',
            'UD Maju Bersama',
            'PT Anugerah Sukses Mandiri',
            'CV Jaya Makmur',
            'PT Bintang Timur',
            'UD Sinar Harapan',
            'PT Mega Karya Indonesia',
            'CV Surya Indah',
            'PT Nusantara Jaya Abadi'
        ];

        return [
            'name' => $this->faker->randomElement($companies),
            'contact_person' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'address' => $this->faker->address(),
            'is_active' => $this->faker->boolean(90), // 90% chance to be active
        ];
    }

    /**
     * Indicate that the supplier is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the supplier is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}