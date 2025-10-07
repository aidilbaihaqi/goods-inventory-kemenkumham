<?php

namespace Tests\Unit;

use App\Models\StockAdjustment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockAdjustmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_stock_adjustment()
    {
        $item = Item::factory()->create();
        $user = User::factory()->create();

        $stockAdjustmentData = [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'old_quantity' => 50,
            'new_quantity' => 60,
            'adjustment_quantity' => 10,
            'reason' => 'Stock correction after physical count',
            'adjustment_date' => now(),
        ];

        $stockAdjustment = StockAdjustment::create($stockAdjustmentData);

        $this->assertInstanceOf(StockAdjustment::class, $stockAdjustment);
        $this->assertEquals($stockAdjustmentData['item_id'], $stockAdjustment->item_id);
        $this->assertEquals($stockAdjustmentData['user_id'], $stockAdjustment->user_id);
        $this->assertEquals($stockAdjustmentData['old_quantity'], $stockAdjustment->old_quantity);
        $this->assertEquals($stockAdjustmentData['new_quantity'], $stockAdjustment->new_quantity);
        $this->assertEquals($stockAdjustmentData['adjustment_quantity'], $stockAdjustment->adjustment_quantity);
        $this->assertEquals($stockAdjustmentData['reason'], $stockAdjustment->reason);
        $this->assertDatabaseHas('stock_adjustments', $stockAdjustmentData);
    }

    /** @test */
    public function it_can_read_a_stock_adjustment()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'old_quantity' => 100,
            'new_quantity' => 95,
            'adjustment_quantity' => -5,
            'reason' => 'Damaged goods',
        ]);

        $foundStockAdjustment = StockAdjustment::find($stockAdjustment->id);

        $this->assertInstanceOf(StockAdjustment::class, $foundStockAdjustment);
        $this->assertEquals($stockAdjustment->type, $foundStockAdjustment->type);
        $this->assertEquals($stockAdjustment->quantity, $foundStockAdjustment->quantity);
        $this->assertEquals($stockAdjustment->reason, $foundStockAdjustment->reason);
    }

    /** @test */
    public function it_can_update_a_stock_adjustment()
    {
        $stockAdjustment = StockAdjustment::factory()->create();
        $newItem = Item::factory()->create();
        $newUser = User::factory()->create();

        $updateData = [
            'item_id' => $newItem->id,
            'user_id' => $newUser->id,
            'old_quantity' => 80,
            'new_quantity' => 65,
            'adjustment_quantity' => -15,
            'reason' => 'Updated reason for adjustment',
        ];

        $stockAdjustment->update($updateData);

        $this->assertEquals($updateData['item_id'], $stockAdjustment->fresh()->item_id);
        $this->assertEquals($updateData['user_id'], $stockAdjustment->fresh()->user_id);
        $this->assertEquals($updateData['old_quantity'], $stockAdjustment->fresh()->old_quantity);
        $this->assertEquals($updateData['new_quantity'], $stockAdjustment->fresh()->new_quantity);
        $this->assertEquals($updateData['adjustment_quantity'], $stockAdjustment->fresh()->adjustment_quantity);
        $this->assertEquals($updateData['reason'], $stockAdjustment->fresh()->reason);
        $this->assertDatabaseHas('stock_adjustments', array_merge(['id' => $stockAdjustment->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_stock_adjustment()
    {
        $stockAdjustment = StockAdjustment::factory()->create();
        $stockAdjustmentId = $stockAdjustment->id;

        $stockAdjustment->delete();

        $this->assertDatabaseMissing('stock_adjustments', ['id' => $stockAdjustmentId]);
        $this->assertNull(StockAdjustment::find($stockAdjustmentId));
    }

    /** @test */
    public function it_belongs_to_item()
    {
        $item = Item::factory()->create(['name' => 'Test Item']);
        $stockAdjustment = StockAdjustment::factory()->create(['item_id' => $item->id]);

        $this->assertInstanceOf(Item::class, $stockAdjustment->item);
        $this->assertEquals($item->id, $stockAdjustment->item->id);
        $this->assertEquals('Test Item', $stockAdjustment->item->name);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create(['name' => 'Test User']);
        $stockAdjustment = StockAdjustment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $stockAdjustment->user);
        $this->assertEquals($user->id, $stockAdjustment->user->id);
        $this->assertEquals('Test User', $stockAdjustment->user->name);
    }

    /** @test */
    public function it_casts_quantities_to_decimal()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'old_quantity' => '50.00',
            'new_quantity' => '60.00',
            'adjustment_quantity' => '10.00'
        ]);

        $this->assertIsNumeric($stockAdjustment->old_quantity);
        $this->assertIsNumeric($stockAdjustment->new_quantity);
        $this->assertIsNumeric($stockAdjustment->adjustment_quantity);
        $this->assertEquals(50.00, $stockAdjustment->old_quantity);
        $this->assertEquals(60.00, $stockAdjustment->new_quantity);
        $this->assertEquals(10.00, $stockAdjustment->adjustment_quantity);
    }

    /** @test */
    public function it_casts_adjustment_date_to_datetime()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'adjustment_date' => '2024-01-15 10:30:00'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $stockAdjustment->adjustment_date);
    }

    /** @test */
    public function it_can_calculate_adjustment_quantity()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'old_quantity' => 100,
            'new_quantity' => 120,
            'adjustment_quantity' => 20
        ]);

        $this->assertEquals(20, $stockAdjustment->adjustment_quantity);
        $this->assertEquals(100, $stockAdjustment->old_quantity);
        $this->assertEquals(120, $stockAdjustment->new_quantity);
    }

    /** @test */
    public function it_can_create_positive_adjustment()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'old_quantity' => 50,
            'new_quantity' => 75,
            'adjustment_quantity' => 25
        ]);

        $this->assertGreaterThan(0, $stockAdjustment->adjustment_quantity);
        $this->assertEquals(25, $stockAdjustment->adjustment_quantity);
    }

    /** @test */
    public function it_can_create_negative_adjustment()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'old_quantity' => 100,
            'new_quantity' => 85,
            'adjustment_quantity' => -15
        ]);

        $this->assertLessThan(0, $stockAdjustment->adjustment_quantity);
        $this->assertEquals(-15, $stockAdjustment->adjustment_quantity);
    }

    /** @test */
    public function it_requires_item_id_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        StockAdjustment::create([
            'user_id' => User::factory()->create()->id,
            'old_quantity' => 50,
            'new_quantity' => 60,
            'adjustment_quantity' => 10,
            'reason' => 'Test reason',
            'adjustment_date' => now(),
        ]);
    }

    /** @test */
    public function it_requires_user_id_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        StockAdjustment::create([
            'item_id' => Item::factory()->create()->id,
            'old_quantity' => 50,
            'new_quantity' => 60,
            'adjustment_quantity' => 10,
            'reason' => 'Test reason',
            'adjustment_date' => now(),
        ]);
    }

    /** @test */
    public function it_requires_old_quantity_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        StockAdjustment::create([
            'item_id' => Item::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'new_quantity' => 60,
            'adjustment_quantity' => 10,
            'reason' => 'Test reason',
            'adjustment_date' => now(),
        ]);
    }

    /** @test */
    public function it_requires_new_quantity_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        StockAdjustment::create([
            'item_id' => Item::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'old_quantity' => 50,
            'adjustment_quantity' => 10,
            'reason' => 'Test reason',
            'adjustment_date' => now(),
        ]);
    }

    /** @test */
    public function it_requires_reason_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        StockAdjustment::create([
            'item_id' => Item::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'old_quantity' => 50,
            'new_quantity' => 60,
            'adjustment_quantity' => 10,
            'adjustment_date' => now(),
        ]);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $stockAdjustment = StockAdjustment::factory()->create();

        $this->assertInstanceOf(StockAdjustment::class, $stockAdjustment);
        $this->assertNotNull($stockAdjustment->item_id);
        $this->assertNotNull($stockAdjustment->user_id);
        $this->assertIsNumeric($stockAdjustment->old_quantity);
        $this->assertIsNumeric($stockAdjustment->new_quantity);
        $this->assertIsNumeric($stockAdjustment->adjustment_quantity);
        $this->assertNotNull($stockAdjustment->reason);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $stockAdjustment->adjustment_date);
    }

    /** @test */
    public function it_can_calculate_positive_adjustment()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'old_quantity' => 50,
            'new_quantity' => 60,
            'adjustment_quantity' => 10
        ]);

        $this->assertEquals(10, $stockAdjustment->adjustment_quantity);
        $this->assertGreaterThan($stockAdjustment->old_quantity, $stockAdjustment->new_quantity);
    }

    /** @test */
    public function it_can_calculate_negative_adjustment()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'old_quantity' => 60,
            'new_quantity' => 50,
            'adjustment_quantity' => -10
        ]);

        $this->assertEquals(-10, $stockAdjustment->adjustment_quantity);
        $this->assertLessThan($stockAdjustment->old_quantity, $stockAdjustment->new_quantity);
    }

    /** @test */
    public function it_can_store_common_adjustment_reasons()
    {
        $commonReasons = [
            'Physical count correction',
            'Damaged goods',
            'Expired items',
            'Lost items',
            'Found items',
            'System error correction',
            'Theft',
            'Quality control rejection',
        ];

        foreach ($commonReasons as $reason) {
            $stockAdjustment = StockAdjustment::factory()->create(['reason' => $reason]);
            
            $this->assertEquals($reason, $stockAdjustment->reason);
        }
    }

    /** @test */
    public function it_can_store_large_adjustment_quantities()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'old_quantity' => 500,
            'new_quantity' => 1500,
            'adjustment_quantity' => 1000
        ]);
        
        $this->assertEquals(1000, $stockAdjustment->adjustment_quantity);
        $this->assertIsNumeric($stockAdjustment->adjustment_quantity);
    }

    /** @test */
    public function it_can_track_adjustment_history()
    {
        $item = Item::factory()->create();
        
        // Create multiple adjustments for the same item
        $adjustment1 = StockAdjustment::factory()->create([
            'item_id' => $item->id,
            'old_quantity' => 50,
            'new_quantity' => 60,
            'adjustment_quantity' => 10,
            'reason' => 'Initial stock correction'
        ]);

        $adjustment2 = StockAdjustment::factory()->create([
            'item_id' => $item->id,
            'old_quantity' => 60,
            'new_quantity' => 55,
            'adjustment_quantity' => -5,
            'reason' => 'Damaged goods removal'
        ]);

        $adjustments = StockAdjustment::where('item_id', $item->id)->get();

        $this->assertCount(2, $adjustments);
        $this->assertEquals(10, $adjustments->where('adjustment_quantity', '>', 0)->first()->adjustment_quantity);
        $this->assertEquals(-5, $adjustments->where('adjustment_quantity', '<', 0)->first()->adjustment_quantity);
    }

    /** @test */
    public function it_can_calculate_net_adjustment_for_item()
    {
        $item = Item::factory()->create();
        
        // Create adjustments
        StockAdjustment::factory()->create([
            'item_id' => $item->id,
            'old_quantity' => 100,
            'new_quantity' => 120,
            'adjustment_quantity' => 20
        ]);

        StockAdjustment::factory()->create([
            'item_id' => $item->id,
            'old_quantity' => 120,
            'new_quantity' => 112,
            'adjustment_quantity' => -8
        ]);

        StockAdjustment::factory()->create([
            'item_id' => $item->id,
            'old_quantity' => 112,
            'new_quantity' => 117,
            'adjustment_quantity' => 5
        ]);

        $positiveAdjustments = StockAdjustment::where('item_id', $item->id)
            ->where('adjustment_quantity', '>', 0)
            ->sum('adjustment_quantity');

        $negativeAdjustments = StockAdjustment::where('item_id', $item->id)
            ->where('adjustment_quantity', '<', 0)
            ->sum('adjustment_quantity');

        $netAdjustment = $positiveAdjustments + $negativeAdjustments;

        $this->assertEquals(25, $positiveAdjustments);
        $this->assertEquals(-8, $negativeAdjustments);
        $this->assertEquals(17, $netAdjustment);
    }

    /** @test */
    public function it_can_store_indonesian_adjustment_reasons()
    {
        $stockAdjustment = StockAdjustment::factory()->create([
            'reason' => 'Penyesuaian stok fisik'
        ]);
        
        $this->assertEquals('Penyesuaian stok fisik', $stockAdjustment->reason);
    }
}