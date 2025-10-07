<?php

namespace Database\Factories;

use App\Models\StockBatch;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockBatch>
 */
class StockBatchFactory extends Factory
{
    protected $model = StockBatch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $initialQuantity = $this->faker->numberBetween(10, 500);
        $usedPercentage = $this->faker->numberBetween(0, 80); // 0-80% used
        $remainingQuantity = $initialQuantity * (100 - $usedPercentage) / 100;
        
        return [
            'item_id' => Item::factory(),
            'transaction_id' => Transaction::factory()->incoming(),
            'initial_quantity' => $initialQuantity,
            'remaining_quantity' => $remainingQuantity,
            'unit_price' => $this->faker->numberBetween(5000, 1000000),
            'batch_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the batch is fully used (exhausted).
     */
    public function exhausted(): static
    {
        return $this->state(fn (array $attributes) => [
            'remaining_quantity' => 0,
        ]);
    }

    /**
     * Indicate that the batch is partially used.
     */
    public function partiallyUsed(): static
    {
        return $this->state(function (array $attributes) {
            $initialQuantity = $this->faker->numberBetween(50, 200);
            $usedPercentage = $this->faker->numberBetween(20, 70);
            $remainingQuantity = $initialQuantity * (100 - $usedPercentage) / 100;
            
            return [
                'initial_quantity' => $initialQuantity,
                'remaining_quantity' => $remainingQuantity,
            ];
        });
    }

    /**
     * Indicate that the batch is unused (full).
     */
    public function unused(): static
    {
        return $this->state(function (array $attributes) {
            $initialQuantity = $this->faker->numberBetween(10, 100);
            
            return [
                'initial_quantity' => $initialQuantity,
                'remaining_quantity' => $initialQuantity,
            ];
        });
    }

    /**
     * Create batch for specific item.
     */
    public function forItem(Item $item): static
    {
        return $this->state(fn (array $attributes) => [
            'item_id' => $item->id,
        ]);
    }

    /**
     * Create batch for specific transaction.
     */
    public function forTransaction(Transaction $transaction): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_id' => $transaction->id,
            'item_id' => $transaction->item_id,
        ]);
    }

    /**
     * Create recent batch (within last 3 months).
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'batch_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Create old batch (older than 6 months).
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'batch_date' => $this->faker->dateTimeBetween('-2 years', '-6 months'),
        ]);
    }
}