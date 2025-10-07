<?php

namespace Tests\Unit;

use App\Models\Unit;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_unit()
    {
        $unitData = [
            'name' => 'Kilogram',
            'symbol' => 'kg',
            'description' => 'Unit untuk mengukur berat',
            'is_active' => true,
        ];

        $unit = Unit::create($unitData);

        $this->assertInstanceOf(Unit::class, $unit);
        $this->assertEquals($unitData['name'], $unit->name);
        $this->assertEquals($unitData['symbol'], $unit->symbol);
        $this->assertEquals($unitData['description'], $unit->description);
        $this->assertTrue($unit->is_active);
        $this->assertDatabaseHas('units', $unitData);
    }

    /** @test */
    public function it_can_read_a_unit()
    {
        $unit = Unit::factory()->create([
            'name' => 'Meter',
            'symbol' => 'm',
        ]);

        $foundUnit = Unit::find($unit->id);

        $this->assertInstanceOf(Unit::class, $foundUnit);
        $this->assertEquals($unit->name, $foundUnit->name);
        $this->assertEquals($unit->symbol, $foundUnit->symbol);
    }

    /** @test */
    public function it_can_update_a_unit()
    {
        $unit = Unit::factory()->create();

        $updateData = [
            'name' => 'Updated Unit Name',
            'symbol' => 'upd',
            'description' => 'Updated description',
            'is_active' => false,
        ];

        $unit->update($updateData);

        $this->assertEquals($updateData['name'], $unit->fresh()->name);
        $this->assertEquals($updateData['symbol'], $unit->fresh()->symbol);
        $this->assertEquals($updateData['description'], $unit->fresh()->description);
        $this->assertFalse($unit->fresh()->is_active);
        $this->assertDatabaseHas('units', array_merge(['id' => $unit->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_unit()
    {
        $unit = Unit::factory()->create();
        $unitId = $unit->id;

        $unit->delete();

        $this->assertDatabaseMissing('units', ['id' => $unitId]);
        $this->assertNull(Unit::find($unitId));
    }

    /** @test */
    public function it_has_many_items_relationship()
    {
        $unit = Unit::factory()->create();
        $items = Item::factory(3)->create(['unit_id' => $unit->id]);

        $this->assertCount(3, $unit->items);
        $this->assertInstanceOf(Item::class, $unit->items->first());
    }

    /** @test */
    public function it_casts_is_active_to_boolean()
    {
        $unit = Unit::factory()->create(['is_active' => 1]);

        $this->assertIsBool($unit->is_active);
        $this->assertTrue($unit->is_active);

        $unit->update(['is_active' => 0]);
        $this->assertIsBool($unit->fresh()->is_active);
        $this->assertFalse($unit->fresh()->is_active);
    }

    /** @test */
    public function it_requires_name_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Unit::create([
            'symbol' => 'test',
            'description' => 'Test description',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_requires_symbol_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Unit::create([
            'name' => 'Test Unit',
            'description' => 'Test description',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $unit = Unit::factory()->create();

        $this->assertInstanceOf(Unit::class, $unit);
        $this->assertNotNull($unit->name);
        $this->assertNotNull($unit->symbol);
        $this->assertIsBool($unit->is_active);
    }

    /** @test */
    public function it_can_create_active_unit_with_factory()
    {
        $unit = Unit::factory()->active()->create();

        $this->assertTrue($unit->is_active);
    }

    /** @test */
    public function it_can_create_inactive_unit_with_factory()
    {
        $unit = Unit::factory()->inactive()->create();

        $this->assertFalse($unit->is_active);
    }

    /** @test */
    public function it_can_store_common_measurement_units()
    {
        $commonUnits = [
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Gram', 'symbol' => 'g'],
            ['name' => 'Liter', 'symbol' => 'L'],
            ['name' => 'Meter', 'symbol' => 'm'],
            ['name' => 'Centimeter', 'symbol' => 'cm'],
            ['name' => 'Piece', 'symbol' => 'pcs'],
            ['name' => 'Box', 'symbol' => 'box'],
            ['name' => 'Pack', 'symbol' => 'pack'],
        ];

        foreach ($commonUnits as $unitData) {
            $unit = Unit::factory()->create($unitData);
            
            $this->assertEquals($unitData['name'], $unit->name);
            $this->assertEquals($unitData['symbol'], $unit->symbol);
        }
    }

    /** @test */
    public function it_can_have_optional_description()
    {
        $unit = Unit::factory()->create(['description' => null]);
        
        $this->assertNull($unit->description);
        $this->assertInstanceOf(Unit::class, $unit);
    }

    /** @test */
    public function it_can_store_indonesian_unit_names()
    {
        $unit = Unit::factory()->create([
            'name' => 'Buah',
            'symbol' => 'bh',
            'description' => 'Satuan untuk menghitung barang per buah'
        ]);
        
        $this->assertEquals('Buah', $unit->name);
        $this->assertEquals('bh', $unit->symbol);
        $this->assertStringContainsString('Satuan', $unit->description);
    }

    /** @test */
    public function symbol_should_be_short()
    {
        $unit = Unit::factory()->create(['symbol' => 'kg']);
        
        $this->assertLessThanOrEqual(10, strlen($unit->symbol));
    }
}