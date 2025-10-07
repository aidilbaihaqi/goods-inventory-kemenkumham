<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\StockBatch;
use App\Models\StockAdjustment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Create admin user
        $adminUser = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin-2@kemenkumham.go.id',
        ]);

        // Create additional users
        $users = User::factory(5)->create();
        $allUsers = $users->push($adminUser);

        // Create categories (ensure unique names)
        $categoryNames = [
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

        $categories = collect();
        foreach ($categoryNames as $name => $description) {
            $categories->push(Category::create([
                'name' => $name,
                'description' => $description,
                'is_active' => true,
            ]));
        }

        // Create units (ensure unique names)
        $unitData = [
            ['name' => 'Piece', 'symbol' => 'pcs', 'description' => 'Satuan untuk barang yang dihitung per buah'],
            ['name' => 'Kilogram', 'symbol' => 'kg', 'description' => 'Satuan berat dalam kilogram'],
            ['name' => 'Liter', 'symbol' => 'L', 'description' => 'Satuan volume dalam liter'],
            ['name' => 'Meter', 'symbol' => 'm', 'description' => 'Satuan panjang dalam meter'],
            ['name' => 'Box', 'symbol' => 'box', 'description' => 'Satuan untuk barang yang dikemas dalam kotak'],
            ['name' => 'Pack', 'symbol' => 'pack', 'description' => 'Satuan untuk barang yang dikemas dalam paket'],
            ['name' => 'Set', 'symbol' => 'set', 'description' => 'Satuan untuk barang yang dijual dalam satu set'],
            ['name' => 'Unit', 'symbol' => 'unit', 'description' => 'Satuan umum untuk barang elektronik'],
            ['name' => 'Rim', 'symbol' => 'rim', 'description' => 'Satuan untuk kertas (500 lembar)'],
            ['name' => 'Lusin', 'symbol' => 'dz', 'description' => 'Satuan untuk 12 buah barang']
        ];

        $units = collect();
        foreach ($unitData as $data) {
            $units->push(Unit::create(array_merge($data, ['is_active' => true])));
        }

        // Create suppliers
        $suppliers = Supplier::factory(15)->create();

        // Create items with existing categories and units
        $items = collect();
        for ($i = 0; $i < 50; $i++) {
            $item = Item::factory()->create([
                'category_id' => $categories->random()->id,
                'unit_id' => $units->random()->id,
            ]);
            $items->push($item);
        }

        // Create some items with low stock
        for ($i = 0; $i < 10; $i++) {
            $item = Item::factory()->lowStock()->create([
                'category_id' => $categories->random()->id,
                'unit_id' => $units->random()->id,
            ]);
            $items->push($item);
        }

        // Create transactions
        $transactions = collect();

        // Create incoming transactions (purchases)
        for ($i = 0; $i < 100; $i++) {
            $transaction = Transaction::factory()->incoming()->create([
                'item_id' => $items->random()->id,
                'supplier_id' => $suppliers->random()->id,
                'user_id' => $allUsers->random()->id,
            ]);
            $transactions->push($transaction);
        }

        // Create outgoing transactions (usage)
        for ($i = 0; $i < 80; $i++) {
            $transaction = Transaction::factory()->outgoing()->create([
                'item_id' => $items->random()->id,
                'user_id' => $allUsers->random()->id,
            ]);
            $transactions->push($transaction);
        }

        // Create stock batches for incoming transactions
        $incomingTransactions = $transactions->where('type', Transaction::TYPE_IN);
        foreach ($incomingTransactions as $transaction) {
            StockBatch::factory()->create([
                'item_id' => $transaction->item_id,
                'transaction_id' => $transaction->id,
                'initial_quantity' => $transaction->quantity,
                'remaining_quantity' => $transaction->quantity * (rand(20, 100) / 100), // 20-100% remaining
                'unit_price' => $transaction->unit_price,
                'batch_date' => $transaction->transaction_date,
            ]);
        }

        // Create additional stock batches
        StockBatch::factory(30)->create([
            'item_id' => fn() => $items->random()->id,
            'transaction_id' => fn() => $incomingTransactions->random()->id,
        ]);

        // Create stock adjustments
        for ($i = 0; $i < 25; $i++) {
            StockAdjustment::factory()->create([
                'item_id' => $items->random()->id,
                'user_id' => $allUsers->random()->id,
            ]);
        }

        // Update item current stock based on transactions and adjustments
        foreach ($items as $item) {
            $incomingTotal = Transaction::where('item_id', $item->id)
                ->where('type', Transaction::TYPE_IN)
                ->sum('quantity');

            $outgoingTotal = Transaction::where('item_id', $item->id)
                ->where('type', Transaction::TYPE_OUT)
                ->sum('quantity');

            $adjustmentTotal = StockAdjustment::where('item_id', $item->id)
                ->sum('adjustment_quantity');

            $currentStock = $incomingTotal - $outgoingTotal + $adjustmentTotal;
            $currentStock = max(0, $currentStock); // Ensure non-negative

            // Calculate average unit price from recent transactions
            $avgPrice = Transaction::where('item_id', $item->id)
                ->where('type', Transaction::TYPE_IN)
                ->avg('unit_price') ?? 50000;

            $item->update([
                'current_stock' => $currentStock,
                'current_value' => $currentStock * $avgPrice,
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . $allUsers->count() . ' users (including admin)');
        $this->command->info('- ' . $categories->count() . ' categories');
        $this->command->info('- ' . $units->count() . ' units');
        $this->command->info('- ' . $suppliers->count() . ' suppliers');
        $this->command->info('- ' . $items->count() . ' items');
        $this->command->info('- ' . $transactions->count() . ' transactions');
        $this->command->info('- Stock batches and adjustments');
        $this->command->info('');
        $this->command->info('Admin login: admin@kemenkumham.go.id');
    }
}
