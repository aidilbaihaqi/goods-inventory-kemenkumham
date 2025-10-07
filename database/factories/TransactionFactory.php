<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement([Transaction::TYPE_IN, Transaction::TYPE_OUT]);
        $quantity = $this->faker->numberBetween(1, 100);
        $unitPrice = $this->faker->numberBetween(5000, 1000000);
        $totalValue = $quantity * $unitPrice;
        
        return [
            'item_id' => Item::factory(),
            'type' => $type,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_value' => $totalValue,
            'reference_no' => $this->generateReferenceNo($type),
            'notes' => $this->faker->optional(0.7)->sentence(),
            'supplier_id' => $type === Transaction::TYPE_IN ? Supplier::factory() : null,
            'user_id' => User::factory(),
            'transaction_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Generate reference number based on transaction type.
     */
    private function generateReferenceNo(string $type): string
    {
        $prefix = $type === Transaction::TYPE_IN ? 'IN' : 'OUT';
        $date = now()->format('Ymd');
        $random = $this->faker->unique()->numerify('####');
        
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Indicate that the transaction is incoming.
     */
    public function incoming(): static
    {
        return $this->state(function (array $attributes) {
            $quantity = $this->faker->numberBetween(1, 100);
            $unitPrice = $this->faker->numberBetween(5000, 1000000);
            
            return [
                'type' => Transaction::TYPE_IN,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_value' => $quantity * $unitPrice,
                'reference_no' => $this->generateReferenceNo(Transaction::TYPE_IN),
                'supplier_id' => Supplier::factory(),
            ];
        });
    }

    /**
     * Indicate that the transaction is outgoing.
     */
    public function outgoing(): static
    {
        return $this->state(function (array $attributes) {
            $quantity = $this->faker->numberBetween(1, 50);
            $unitPrice = $this->faker->numberBetween(5000, 1000000);
            
            return [
                'type' => Transaction::TYPE_OUT,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_value' => $quantity * $unitPrice,
                'reference_no' => $this->generateReferenceNo(Transaction::TYPE_OUT),
                'supplier_id' => null,
            ];
        });
    }

    /**
     * Create transaction with specific item.
     */
    public function forItem(Item $item): static
    {
        return $this->state(fn (array $attributes) => [
            'item_id' => $item->id,
        ]);
    }

    /**
     * Create transaction with specific supplier.
     */
    public function fromSupplier(Supplier $supplier): static
    {
        return $this->state(fn (array $attributes) => [
            'supplier_id' => $supplier->id,
            'type' => Transaction::TYPE_IN,
        ]);
    }

    /**
     * Create transaction with specific user.
     */
    public function byUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}