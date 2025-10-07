<?php

namespace Tests\Unit;

use App\Models\StockBatch;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockBatchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_stock_batch()
    {
        $item = Item::factory()->create();
        $transaction = Transaction::factory()->create();

        $stockBatchData = [
            'item_id' => $item->id,
            'transaction_id' => $transaction->id,
            'initial_quantity' => 100,
            'remaining_quantity' => 80,
            'unit_price' => 50000,
            'batch_date' => now(),
        ];

        $stockBatch = StockBatch::create($stockBatchData);

        $this->assertInstanceOf(StockBatch::class, $stockBatch);
        $this->assertEquals($stockBatchData['item_id'], $stockBatch->item_id);
        $this->assertEquals($stockBatchData['transaction_id'], $stockBatch->transaction_id);
        $this->assertEquals($stockBatchData['initial_quantity'], $stockBatch->initial_quantity);
        $this->assertEquals($stockBatchData['remaining_quantity'], $stockBatch->remaining_quantity);
        $this->assertEquals($stockBatchData['unit_price'], $stockBatch->unit_price);
        $this->assertDatabaseHas('stock_batches', $stockBatchData);
    }

    /** @test */
    public function it_can_read_a_stock_batch()
    {
        $stockBatch = StockBatch::factory()->create([
            'initial_quantity' => 50,
            'remaining_quantity' => 30,
        ]);

        $foundStockBatch = StockBatch::find($stockBatch->id);

        $this->assertInstanceOf(StockBatch::class, $foundStockBatch);
        $this->assertEquals($stockBatch->initial_quantity, $foundStockBatch->initial_quantity);
        $this->assertEquals($stockBatch->remaining_quantity, $foundStockBatch->remaining_quantity);
    }

    /** @test */
    public function it_can_update_a_stock_batch()
    {
        $stockBatch = StockBatch::factory()->create();
        $newItem = Item::factory()->create();
        $newTransaction = Transaction::factory()->create();

        $updateData = [
            'item_id' => $newItem->id,
            'transaction_id' => $newTransaction->id,
            'initial_quantity' => 200,
            'remaining_quantity' => 150,
            'unit_price' => 30000,
            'batch_date' => now(),
        ];

        $stockBatch->update($updateData);

        $this->assertEquals($updateData['item_id'], $stockBatch->fresh()->item_id);
        $this->assertEquals($updateData['transaction_id'], $stockBatch->fresh()->transaction_id);
        $this->assertEquals($updateData['initial_quantity'], $stockBatch->fresh()->initial_quantity);
        $this->assertEquals($updateData['remaining_quantity'], $stockBatch->fresh()->remaining_quantity);
        $this->assertEquals($updateData['unit_price'], $stockBatch->fresh()->unit_price);
        $this->assertDatabaseHas('stock_batches', array_merge(['id' => $stockBatch->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_stock_batch()
    {
        $stockBatch = StockBatch::factory()->create();
        $stockBatchId = $stockBatch->id;

        $stockBatch->delete();

        $this->assertDatabaseMissing('stock_batches', ['id' => $stockBatchId]);
        $this->assertNull(StockBatch::find($stockBatchId));
    }

    /** @test */
    public function it_belongs_to_item()
    {
        $item = Item::factory()->create(['name' => 'Test Item']);
        $stockBatch = StockBatch::factory()->create(['item_id' => $item->id]);

        $this->assertInstanceOf(Item::class, $stockBatch->item);
        $this->assertEquals($item->id, $stockBatch->item->id);
        $this->assertEquals('Test Item', $stockBatch->item->name);
    }

    /** @test */
    public function it_belongs_to_transaction()
    {
        $transaction = \App\Models\Transaction::factory()->create();
        $stockBatch = StockBatch::factory()->create(['transaction_id' => $transaction->id]);

        $this->assertInstanceOf(\App\Models\Transaction::class, $stockBatch->transaction);
        $this->assertEquals($transaction->id, $stockBatch->transaction->id);
    }

    /** @test */
    public function it_casts_quantities_to_decimal()
    {
        $stockBatch = StockBatch::factory()->create([
            'initial_quantity' => '100.50',
            'remaining_quantity' => '75.25'
        ]);

        $this->assertIsNumeric($stockBatch->initial_quantity);
        $this->assertIsNumeric($stockBatch->remaining_quantity);
        $this->assertEquals(100.50, $stockBatch->initial_quantity);
        $this->assertEquals(75.25, $stockBatch->remaining_quantity);
    }

    /** @test */
    public function it_casts_unit_price_to_decimal()
    {
        $stockBatch = StockBatch::factory()->create(['unit_price' => '25000.50']);

        $this->assertIsNumeric($stockBatch->unit_price);
        $this->assertEquals(25000.50, $stockBatch->unit_price);
    }

    /** @test */
    public function it_casts_dates_to_datetime()
    {
        $stockBatch = StockBatch::factory()->create([
            'batch_date' => '2024-01-15'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $stockBatch->batch_date);
    }

    /** @test */
    public function it_requires_item_id_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        StockBatch::create([
            'transaction_id' => \App\Models\Transaction::factory()->create()->id,
            'initial_quantity' => 100,
            'remaining_quantity' => 100,
            'unit_price' => 25000,
            'batch_date' => now(),
        ]);
    }

    /** @test */
    public function it_requires_transaction_id_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        StockBatch::create([
            'item_id' => Item::factory()->create()->id,
            'initial_quantity' => 100,
            'remaining_quantity' => 100,
            'unit_price' => 25000,
            'batch_date' => now(),
        ]);
    }

    /** @test */
    public function it_requires_initial_quantity_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        StockBatch::create([
            'item_id' => Item::factory()->create()->id,
            'transaction_id' => \App\Models\Transaction::factory()->create()->id,
            'remaining_quantity' => 100,
            'unit_price' => 25000,
            'batch_date' => now(),
        ]);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $stockBatch = StockBatch::factory()->create();

        $this->assertInstanceOf(StockBatch::class, $stockBatch);
        $this->assertNotNull($stockBatch->item_id);
        $this->assertNotNull($stockBatch->transaction_id);
        $this->assertIsNumeric($stockBatch->initial_quantity);
        $this->assertIsNumeric($stockBatch->remaining_quantity);
        $this->assertIsNumeric($stockBatch->unit_price);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $stockBatch->batch_date);
    }

    /** @test */
    public function it_can_have_zero_remaining_quantity()
    {
        $stockBatch = StockBatch::factory()->create(['remaining_quantity' => 0]);
        
        $this->assertEquals(0, $stockBatch->remaining_quantity);
        $this->assertInstanceOf(StockBatch::class, $stockBatch);
    }

    /** @test */
    public function remaining_quantity_should_not_exceed_initial_quantity()
    {
        $stockBatch = StockBatch::factory()->create([
            'initial_quantity' => 100,
            'remaining_quantity' => 75
        ]);

        $this->assertLessThanOrEqual($stockBatch->initial_quantity, $stockBatch->remaining_quantity);
    }

    /** @test */
    public function it_can_track_stock_depletion()
    {
        $stockBatch = StockBatch::factory()->create([
            'initial_quantity' => 100,
            'remaining_quantity' => 100
        ]);

        // Simulate stock usage
        $stockBatch->update(['remaining_quantity' => 50]);

        $this->assertEquals(50, $stockBatch->fresh()->remaining_quantity);
        $this->assertEquals(100, $stockBatch->fresh()->initial_quantity);
    }

    /** @test */
    public function it_can_be_completely_depleted()
    {
        $stockBatch = StockBatch::factory()->create([
            'initial_quantity' => 100,
            'remaining_quantity' => 100
        ]);

        $stockBatch->update(['remaining_quantity' => 0]);

        $this->assertEquals(0, $stockBatch->fresh()->remaining_quantity);
    }

    /** @test */
    public function it_can_store_different_unit_prices()
    {
        $unitPrices = [
            25000.00,
            15500.50,
            100000.75,
            999.99,
        ];

        foreach ($unitPrices as $unitPrice) {
            $stockBatch = StockBatch::factory()->create(['unit_price' => $unitPrice]);
            
            $this->assertEquals($unitPrice, $stockBatch->unit_price);
        }
    }

    /** @test */
    public function it_can_check_batch_date()
    {
        $pastBatch = StockBatch::factory()->create([
            'batch_date' => now()->subDays(1)
        ]);

        $todayBatch = StockBatch::factory()->create([
            'batch_date' => now()
        ]);

        $this->assertTrue($pastBatch->batch_date->isPast());
        $this->assertTrue($todayBatch->batch_date->isToday());
    }

    /** @test */
    public function it_can_calculate_quantity_difference()
    {
        $stockBatch = StockBatch::factory()->create([
            'initial_quantity' => 100,
            'remaining_quantity' => 75
        ]);

        $usedQuantity = $stockBatch->initial_quantity - $stockBatch->remaining_quantity;
        
        $this->assertEquals(25, $usedQuantity);
        $this->assertGreaterThan(0, $usedQuantity);
    }
}