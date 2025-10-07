<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Elektronik' => 'Peralatan elektronik dan gadget',
            'Furniture' => 'Meja, kursi, lemari dan perabotan kantor',
            'Alat Tulis' => 'Pena, kertas, dan perlengkapan tulis kantor',
            'Komputer' => 'Laptop, desktop, dan aksesoris komputer',
            'Kendaraan' => 'Mobil dinas, motor, dan kendaraan operasional',
            'Peralatan Medis' => 'Alat kesehatan dan peralatan medis',
            'Buku & Dokumen' => 'Buku referensi, arsip, dan dokumen',
            'Peralatan Keamanan' => 'CCTV, alarm, dan peralatan keamanan',
            'Peralatan Kebersihan' => 'Alat pembersih dan perlengkapan sanitasi',
            'Peralatan Audio Visual' => 'Proyektor, speaker, dan peralatan presentasi'
        ];

        $categoryName = $this->faker->randomElement(array_keys($categories));
        
        return [
            'name' => $categoryName,
            'description' => $categories[$categoryName],
            'is_active' => $this->faker->boolean(85), // 85% chance to be active
        ];
    }

    /**
     * Indicate that the category is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}