<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Transaction;
use App\Models\StockBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_item()
    {
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();

        $itemData = [
            'name' => 'Laptop Dell Inspiron',
            'code' => 'LPT-001',
            'description' => 'Laptop untuk keperluan kantor',
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'min_stock' => 5,
            'current_stock' => 10,
            'is_active' => true,
        ];

        $item = Item::create($itemData);

        $this->assertInstanceOf(Item::class, $item);
        $this->assertEquals($itemData['name'], $item->name);
        $this->assertEquals($itemData['code'], $item->code);
        $this->assertEquals($itemData['description'], $item->description);
        $this->assertEquals($itemData['category_id'], $item->category_id);
        $this->assertEquals($itemData['unit_id'], $item->unit_id);
        $this->assertEquals($itemData['min_stock'], $item->min_stock);
        $this->assertEquals($itemData['current_stock'], $item->current_stock);
        $this->assertTrue($item->is_active);
        $this->assertDatabaseHas('items', $itemData);
    }

    /** @test */
    public function it_can_read_an_item()
    {
        $item = Item::factory()->create([
            'name' => 'Test Item',
            'code' => 'TST-001',
        ]);

        $foundItem = Item::find($item->id);

        $this->assertInstanceOf(Item::class, $foundItem);
        $this->assertEquals($item->name, $foundItem->name);
        $this->assertEquals($item->code, $foundItem->code);
    }

    /** @test */
    public function it_can_update_an_item()
    {
        $item = Item::factory()->create();
        $newCategory = Category::factory()->create();
        $newUnit = Unit::factory()->create();

        $updateData = [
            'name' => 'Updated Item Name',
            'code' => 'UPD-001',
            'description' => 'Updated description',
            'category_id' => $newCategory->id,
            'unit_id' => $newUnit->id,
            'min_stock' => 15,
            'current_stock' => 25,
            'is_active' => false,
        ];

        $item->update($updateData);

        $this->assertEquals($updateData['name'], $item->fresh()->name);
        $this->assertEquals($updateData['code'], $item->fresh()->code);
        $this->assertEquals($updateData['description'], $item->fresh()->description);
        $this->assertEquals($updateData['category_id'], $item->fresh()->category_id);
        $this->assertEquals($updateData['unit_id'], $item->fresh()->unit_id);
        $this->assertEquals($updateData['min_stock'], $item->fresh()->min_stock);
        $this->assertEquals($updateData['current_stock'], $item->fresh()->current_stock);
        $this->assertFalse($item->fresh()->is_active);
        $this->assertDatabaseHas('items', array_merge(['id' => $item->id], $updateData));
    }

    /** @test */
    public function it_can_delete_an_item()
    {
        $item = Item::factory()->create();
        $itemId = $item->id;

        $item->delete();

        $this->assertDatabaseMissing('items', ['id' => $itemId]);
        $this->assertNull(Item::find($itemId));
    }

    /** @test */
    public function it_belongs_to_category()
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $item = Item::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $item->category);
        $this->assertEquals($category->id, $item->category->id);
        $this->assertEquals('Electronics', $item->category->name);
    }

    /** @test */
    public function it_belongs_to_unit()
    {
        $unit = Unit::factory()->create(['name' => 'Piece', 'symbol' => 'pcs']);
        $item = Item::factory()->create(['unit_id' => $unit->id]);

        $this->assertInstanceOf(Unit::class, $item->unit);
        $this->assertEquals($unit->id, $item->unit->id);
        $this->assertEquals('Piece', $item->unit->name);
        $this->assertEquals('pcs', $item->unit->symbol);
    }

    /** @test */
    public function it_has_many_transactions_relationship()
    {
        $item = Item::factory()->create();
        $transactions = Transaction::factory(3)->create(['item_id' => $item->id]);

        $this->assertCount(3, $item->transactions);
        $this->assertInstanceOf(Transaction::class, $item->transactions->first());
    }

    /** @test */
    public function it_has_many_stock_batches_relationship()
    {
        $item = Item::factory()->create();
        $stockBatches = StockBatch::factory(2)->create(['item_id' => $item->id]);

        $this->assertCount(2, $item->stockBatches);
        $this->assertInstanceOf(StockBatch::class, $item->stockBatches->first());
    }

    /** @test */
    public function it_casts_is_active_to_boolean()
    {
        $item = Item::factory()->create(['is_active' => 1]);

        $this->assertIsBool($item->is_active);
        $this->assertTrue($item->is_active);

        $item->update(['is_active' => 0]);
        $this->assertIsBool($item->fresh()->is_active);
        $this->assertFalse($item->fresh()->is_active);
    }

    /** @test */
    public function it_casts_stock_values_to_decimals()
    {
        $item = Item::factory()->create([
            'min_stock' => 10,
            'current_stock' => 25.50
        ]);

        $this->assertIsNumeric($item->min_stock);
        $this->assertIsNumeric($item->current_stock);
    }

    /** @test */
    public function it_has_default_min_stock_value()
    {
        $item = Item::create([
            'name' => 'Test Item',
            'code' => 'TST-001',
            'category_id' => Category::factory()->create()->id,
            'unit_id' => Unit::factory()->create()->id,
            'current_stock' => 10,
            // min_stock has default value of 0
        ]);

        $this->assertEquals(0, $item->min_stock);
    }

    /** @test */
    public function it_requires_name_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Item::create([
            'code' => 'TST-001',
            'category_id' => Category::factory()->create()->id,
            'unit_id' => Unit::factory()->create()->id,
            'min_stock' => 5,
            'current_stock' => 10,
        ]);
    }

    /** @test */
    public function it_requires_code_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Item::create([
            'name' => 'Test Item',
            'category_id' => Category::factory()->create()->id,
            'unit_id' => Unit::factory()->create()->id,
            'min_stock' => 5,
            'current_stock' => 10,
        ]);
    }

    /** @test */
    public function it_requires_category_id_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Item::create([
            'name' => 'Test Item',
            'code' => 'TST-001',
            'unit_id' => Unit::factory()->create()->id,
            'min_stock' => 5,
            'current_stock' => 10,
        ]);
    }

    /** @test */
    public function it_requires_unit_id_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Item::create([
            'name' => 'Test Item',
            'code' => 'TST-001',
            'category_id' => Category::factory()->create()->id,
            'minimum_stock' => 5,
            'current_stock' => 10,
        ]);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $item = Item::factory()->create();

        $this->assertInstanceOf(Item::class, $item);
        $this->assertNotNull($item->name);
        $this->assertNotNull($item->code);
        $this->assertNotNull($item->category_id);
        $this->assertNotNull($item->unit_id);
        $this->assertIsNumeric($item->min_stock);
        $this->assertIsNumeric($item->current_stock);
        $this->assertIsBool($item->is_active);
    }

    /** @test */
    public function it_can_create_active_item_with_factory()
    {
        $item = Item::factory()->active()->create();

        $this->assertTrue($item->is_active);
    }

    /** @test */
    public function it_can_create_inactive_item_with_factory()
    {
        $item = Item::factory()->inactive()->create();

        $this->assertFalse($item->is_active);
    }

    /** @test */
    public function it_can_store_office_equipment_items()
    {
        $category = Category::factory()->create(['name' => 'Office Equipment']);
        $unit = Unit::factory()->create(['name' => 'Piece', 'symbol' => 'pcs']);

        $item = Item::factory()->create([
            'name' => 'Printer Canon IP2770',
            'code' => 'PRT-001',
            'category_id' => $category->id,
            'unit_id' => $unit->id,
        ]);
        
        $this->assertEquals('Printer Canon IP2770', $item->name);
        $this->assertEquals('PRT-001', $item->code);
        $this->assertEquals('Office Equipment', $item->category->name);
        $this->assertEquals('pcs', $item->unit->symbol);
    }

    /** @test */
    public function it_can_check_stock_below_minimum()
    {
        $item = Item::factory()->create([
            'min_stock' => 10,
            'current_stock' => 5
        ]);

        $this->assertTrue($item->current_stock < $item->min_stock);
    }

    /** @test */
    public function it_can_have_zero_current_stock()
    {
        $item = Item::factory()->create(['current_stock' => 0]);

        $this->assertEquals(0, $item->current_stock);
        $this->assertIsNumeric($item->current_stock);
    }

    /** @test */
    public function code_should_be_unique()
    {
        $item1 = Item::factory()->create(['code' => 'UNIQUE-001']);
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Item::factory()->create(['code' => 'UNIQUE-001']);
    }
}