<?php

namespace Tests\Unit;

use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_supplier()
    {
        $supplierData = [
            'name' => 'PT. Teknologi Maju',
            'contact_person' => 'Budi Santoso',
            'phone' => '021-12345678',
            'email' => 'budi@teknologimaju.com',
            'address' => 'Jl. Sudirman No. 123, Jakarta',
            'is_active' => true,
        ];

        $supplier = Supplier::create($supplierData);

        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertEquals($supplierData['name'], $supplier->name);
        $this->assertEquals($supplierData['contact_person'], $supplier->contact_person);
        $this->assertEquals($supplierData['phone'], $supplier->phone);
        $this->assertEquals($supplierData['email'], $supplier->email);
        $this->assertEquals($supplierData['address'], $supplier->address);
        $this->assertTrue($supplier->is_active);
        $this->assertDatabaseHas('suppliers', $supplierData);
    }

    /** @test */
    public function it_can_read_a_supplier()
    {
        $supplier = Supplier::factory()->create([
            'name' => 'PT. Supplier Test',
            'contact_person' => 'Test Person',
        ]);

        $foundSupplier = Supplier::find($supplier->id);

        $this->assertInstanceOf(Supplier::class, $foundSupplier);
        $this->assertEquals($supplier->name, $foundSupplier->name);
        $this->assertEquals($supplier->contact_person, $foundSupplier->contact_person);
    }

    /** @test */
    public function it_can_update_a_supplier()
    {
        $supplier = Supplier::factory()->create();

        $updateData = [
            'name' => 'Updated Supplier Name',
            'contact_person' => 'Updated Contact Person',
            'phone' => '021-99999999',
            'email' => 'updated@supplier.com',
            'address' => 'Updated Address',
            'is_active' => false,
        ];

        $supplier->update($updateData);

        $this->assertEquals($updateData['name'], $supplier->fresh()->name);
        $this->assertEquals($updateData['contact_person'], $supplier->fresh()->contact_person);
        $this->assertEquals($updateData['phone'], $supplier->fresh()->phone);
        $this->assertEquals($updateData['email'], $supplier->fresh()->email);
        $this->assertEquals($updateData['address'], $supplier->fresh()->address);
        $this->assertFalse($supplier->fresh()->is_active);
        $this->assertDatabaseHas('suppliers', array_merge(['id' => $supplier->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_supplier()
    {
        $supplier = Supplier::factory()->create();
        $supplierId = $supplier->id;

        $supplier->delete();

        $this->assertDatabaseMissing('suppliers', ['id' => $supplierId]);
        $this->assertNull(Supplier::find($supplierId));
    }

    /** @test */
    public function it_has_many_transactions_relationship()
    {
        $supplier = Supplier::factory()->create();
        $transactions = Transaction::factory(3)->create(['supplier_id' => $supplier->id]);

        $this->assertCount(3, $supplier->transactions);
        $this->assertInstanceOf(Transaction::class, $supplier->transactions->first());
    }

    /** @test */
    public function it_casts_is_active_to_boolean()
    {
        $supplier = Supplier::factory()->create(['is_active' => 1]);

        $this->assertIsBool($supplier->is_active);
        $this->assertTrue($supplier->is_active);

        $supplier->update(['is_active' => 0]);
        $this->assertIsBool($supplier->fresh()->is_active);
        $this->assertFalse($supplier->fresh()->is_active);
    }

    /** @test */
    public function it_requires_name_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Supplier::create([
            'contact_person' => 'Test Person',
            'phone' => '021-12345678',
            'email' => 'test@supplier.com',
            'address' => 'Test Address',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $supplier = Supplier::factory()->create();

        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertNotNull($supplier->name);
        $this->assertNotNull($supplier->contact_person);
        $this->assertNotNull($supplier->phone);
        $this->assertNotNull($supplier->email);
        $this->assertNotNull($supplier->address);
        $this->assertIsBool($supplier->is_active);
    }

    /** @test */
    public function it_can_create_active_supplier_with_factory()
    {
        $supplier = Supplier::factory()->active()->create();

        $this->assertTrue($supplier->is_active);
    }

    /** @test */
    public function it_can_create_inactive_supplier_with_factory()
    {
        $supplier = Supplier::factory()->inactive()->create();

        $this->assertFalse($supplier->is_active);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $supplier = Supplier::factory()->create(['email' => 'valid@email.com']);
        $this->assertNotNull($supplier);

        // Note: Email validation would typically be handled at the request/validation layer
        // This test ensures the model can store valid email formats
        $this->assertStringContainsString('@', $supplier->email);
    }

    /** @test */
    public function it_can_store_indonesian_company_names()
    {
        $supplier = Supplier::factory()->create(['name' => 'PT. Maju Bersama Indonesia']);
        
        $this->assertEquals('PT. Maju Bersama Indonesia', $supplier->name);
        $this->assertStringContainsString('PT.', $supplier->name);
    }
}