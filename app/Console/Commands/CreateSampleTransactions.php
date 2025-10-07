<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CreateSampleTransactions extends Command
{
    protected $signature = 'create:sample-transactions';
    protected $description = 'Create sample transactions for testing dashboard widgets';

    public function handle()
    {
        $this->info('Creating sample transactions...');

        // Get existing data
        $items = Item::where('is_active', true)->get();
        $users = User::all();
        $suppliers = Supplier::where('is_active', true)->get();

        if ($items->isEmpty() || $users->isEmpty()) {
            $this->error('No items or users found. Please run the seeder first.');
            return;
        }

        // Create transactions for the last 6 months
        $now = Carbon::now();
        $transactionCount = 0;

        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            
            // Create 5-15 transactions per month
            $monthlyTransactions = rand(5, 15);
            
            for ($j = 0; $j < $monthlyTransactions; $j++) {
                $item = $items->random();
                $user = $users->random();
                $type = collect(['masuk', 'keluar'])->random();
                $quantity = rand(1, 50);
                $unitPrice = rand(10000, 500000);
                $totalValue = $quantity * $unitPrice;
                
                // Random date within the month
                $transactionDate = $month->copy()->addDays(rand(0, $month->daysInMonth - 1));
                
                Transaction::create([
                    'item_id' => $item->id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_value' => $totalValue,
                    'reference_no' => 'REF-' . $transactionDate->format('Ymd') . '-' . str_pad($j + 1, 3, '0', STR_PAD_LEFT),
                    'notes' => 'Sample transaction for ' . $type . ' barang',
                    'supplier_id' => $type === 'masuk' && $suppliers->isNotEmpty() ? $suppliers->random()->id : null,
                    'user_id' => $user->id,
                    'transaction_date' => $transactionDate,
                ]);
                
                $transactionCount++;
            }
        }

        $this->info("Created {$transactionCount} sample transactions successfully!");
        return 0;
    }
}
