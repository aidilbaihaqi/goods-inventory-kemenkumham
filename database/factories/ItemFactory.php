<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $items = [
            // Elektronik
            'Laptop Dell Inspiron 15',
            'Mouse Wireless Logitech',
            'Keyboard Mechanical',
            'Monitor LED 24 inch',
            'Printer Canon Pixma',
            'Scanner Epson',
            'Webcam HD 1080p',
            'Speaker Bluetooth',
            'Headset Gaming',
            'Power Bank 10000mAh',
            
            // Furniture
            'Meja Kerja Kayu Jati',
            'Kursi Kantor Ergonomis',
            'Lemari Arsip 4 Pintu',
            'Rak Buku Minimalis',
            'Meja Meeting 8 Orang',
            'Kursi Tamu Sofa',
            'Filing Cabinet',
            'Whiteboard Magnetic',
            'Papan Tulis Flipchart',
            'Locker Personal',
            
            // Alat Tulis
            'Pulpen Pilot G2',
            'Pensil 2B Faber Castell',
            'Kertas A4 80gsm',
            'Stapler Joyko',
            'Penggaris 30cm',
            'Spidol Whiteboard',
            'Correction Tape',
            'Amplop Putih',
            'Map Plastik',
            'Binder Clip',
            
            // Peralatan Medis
            'Termometer Digital',
            'Tensimeter Digital',
            'Masker N95',
            'Hand Sanitizer 500ml',
            'Sarung Tangan Latex',
            'Plester Luka',
            'Perban Elastis',
            'Alkohol 70%',
            'Betadine 60ml',
            'Kotak P3K',
            
            // Kendaraan
            'Mobil Dinas Toyota Avanza',
            'Motor Dinas Honda Beat',
            'Ban Mobil Bridgestone',
            'Oli Mesin Castrol',
            'Aki Mobil GS Astra',
            'Helm Safety',
            'Rompi Safety',
            'Kunci Inggris Set',
            'Jack Dongkrak',
            'Toolkit Lengkap'
        ];

        $itemName = $this->faker->randomElement($items);
        $minStock = $this->faker->numberBetween(5, 50);
        $currentStock = $this->faker->numberBetween(0, 200);
        $unitPrice = $this->faker->numberBetween(10000, 5000000);
        
        return [
            'code' => 'ITM-' . $this->faker->unique()->numerify('######'),
            'name' => $itemName,
            'description' => $this->faker->sentence(10),
            'category_id' => Category::factory(),
            'unit_id' => Unit::factory(),
            'photo' => null, // We'll skip photo generation for simplicity
            'min_stock' => $minStock,
            'current_stock' => $currentStock,
            'current_value' => $currentStock * $unitPrice,
            'is_active' => $this->faker->boolean(90), // 90% chance to be active
        ];
    }

    /**
     * Indicate that the item is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the item is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the item has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(function (array $attributes) {
            $minStock = $this->faker->numberBetween(10, 30);
            $currentStock = $this->faker->numberBetween(0, $minStock);
            $unitPrice = $this->faker->numberBetween(10000, 5000000);
            
            return [
                'min_stock' => $minStock,
                'current_stock' => $currentStock,
                'current_value' => $currentStock * $unitPrice,
            ];
        });
    }

    /**
     * Indicate that the item has good stock.
     */
    public function goodStock(): static
    {
        return $this->state(function (array $attributes) {
            $minStock = $this->faker->numberBetween(5, 20);
            $currentStock = $this->faker->numberBetween($minStock + 10, 200);
            $unitPrice = $this->faker->numberBetween(10000, 5000000);
            
            return [
                'min_stock' => $minStock,
                'current_stock' => $currentStock,
                'current_value' => $currentStock * $unitPrice,
            ];
        });
    }
}