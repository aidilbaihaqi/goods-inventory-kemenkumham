<?php

namespace Database\Factories;

use App\Models\StockAdjustment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockAdjustment>
 */
class StockAdjustmentFactory extends Factory
{
    protected $model = StockAdjustment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $oldQuantity = $this->faker->numberBetween(0, 200);
        $adjustmentQuantity = $this->faker->numberBetween(-50, 50);
        $newQuantity = max(0, $oldQuantity + $adjustmentQuantity);
        
        $reasons = [
            'Koreksi stok fisik berdasarkan stock opname',
            'Barang rusak dan tidak dapat digunakan',
            'Kehilangan barang',
            'Barang kadaluarsa',
            'Kesalahan pencatatan sebelumnya',
            'Penyesuaian hasil audit internal',
            'Barang hilang karena pencurian',
            'Kerusakan akibat bencana alam',
            'Penyesuaian sistem baru',
            'Koreksi kesalahan input data',
            'Barang dikembalikan ke supplier',
            'Penyesuaian karena perubahan unit',
            'Koreksi stok awal tahun',
            'Penyesuaian hasil verifikasi',
            'Barang dipindahkan ke unit lain'
        ];
        
        return [
            'item_id' => Item::factory(),
            'old_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'adjustment_quantity' => $adjustmentQuantity,
            'reason' => $this->faker->randomElement($reasons),
            'user_id' => User::factory(),
            'adjustment_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the adjustment is an increase.
     */
    public function increase(): static
    {
        return $this->state(function (array $attributes) {
            $oldQuantity = $this->faker->numberBetween(10, 100);
            $adjustmentQuantity = $this->faker->numberBetween(1, 50);
            $newQuantity = $oldQuantity + $adjustmentQuantity;
            
            $reasons = [
                'Koreksi stok fisik berdasarkan stock opname',
                'Kesalahan pencatatan sebelumnya - stok kurang tercatat',
                'Penyesuaian hasil audit internal',
                'Koreksi kesalahan input data',
                'Penyesuaian sistem baru',
                'Koreksi stok awal tahun'
            ];
            
            return [
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'adjustment_quantity' => $adjustmentQuantity,
                'reason' => $this->faker->randomElement($reasons),
            ];
        });
    }

    /**
     * Indicate that the adjustment is a decrease.
     */
    public function decrease(): static
    {
        return $this->state(function (array $attributes) {
            $oldQuantity = $this->faker->numberBetween(20, 200);
            $adjustmentQuantity = $this->faker->numberBetween(-50, -1);
            $newQuantity = max(0, $oldQuantity + $adjustmentQuantity);
            
            $reasons = [
                'Barang rusak dan tidak dapat digunakan',
                'Kehilangan barang',
                'Barang kadaluarsa',
                'Barang hilang karena pencurian',
                'Kerusakan akibat bencana alam',
                'Barang dikembalikan ke supplier',
                'Koreksi stok fisik berdasarkan stock opname'
            ];
            
            return [
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'adjustment_quantity' => $adjustmentQuantity,
                'reason' => $this->faker->randomElement($reasons),
            ];
        });
    }

    /**
     * Create adjustment for specific item.
     */
    public function forItem(Item $item): static
    {
        return $this->state(fn (array $attributes) => [
            'item_id' => $item->id,
        ]);
    }

    /**
     * Create adjustment by specific user.
     */
    public function byUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Create recent adjustment (within last month).
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'adjustment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Create old adjustment (older than 3 months).
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'adjustment_date' => $this->faker->dateTimeBetween('-1 year', '-3 months'),
        ]);
    }
}