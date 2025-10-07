<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $units = [
            ['name' => 'Piece', 'symbol' => 'pcs', 'description' => 'Satuan untuk barang yang dihitung per buah'],
            ['name' => 'Kilogram', 'symbol' => 'kg', 'description' => 'Satuan berat dalam kilogram'],
            ['name' => 'Gram', 'symbol' => 'g', 'description' => 'Satuan berat dalam gram'],
            ['name' => 'Liter', 'symbol' => 'L', 'description' => 'Satuan volume dalam liter'],
            ['name' => 'Meter', 'symbol' => 'm', 'description' => 'Satuan panjang dalam meter'],
            ['name' => 'Centimeter', 'symbol' => 'cm', 'description' => 'Satuan panjang dalam centimeter'],
            ['name' => 'Box', 'symbol' => 'box', 'description' => 'Satuan untuk barang yang dikemas dalam kotak'],
            ['name' => 'Pack', 'symbol' => 'pack', 'description' => 'Satuan untuk barang yang dikemas dalam paket'],
            ['name' => 'Set', 'symbol' => 'set', 'description' => 'Satuan untuk barang yang dijual dalam satu set'],
            ['name' => 'Unit', 'symbol' => 'unit', 'description' => 'Satuan umum untuk barang elektronik'],
            ['name' => 'Rim', 'symbol' => 'rim', 'description' => 'Satuan untuk kertas (500 lembar)'],
            ['name' => 'Lusin', 'symbol' => 'dz', 'description' => 'Satuan untuk 12 buah barang'],
            ['name' => 'Gross', 'symbol' => 'gr', 'description' => 'Satuan untuk 144 buah barang'],
            ['name' => 'Roll', 'symbol' => 'roll', 'description' => 'Satuan untuk barang yang digulung'],
            ['name' => 'Botol', 'symbol' => 'btl', 'description' => 'Satuan untuk barang cair dalam botol']
        ];

        $unit = $this->faker->randomElement($units);
        
        return [
            'name' => $unit['name'],
            'symbol' => $unit['symbol'],
            'description' => $unit['description'],
            'is_active' => $this->faker->boolean(95), // 95% chance to be active
        ];
    }

    /**
     * Indicate that the unit is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the unit is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}