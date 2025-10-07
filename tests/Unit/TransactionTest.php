<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_transaction()
    {
        $item = Item::factory()->create();
        $supplier = Supplier::factory()->create();
        $user = User::factory()->create();

        $transactionData = [
            'item_id' => $item->id,
            'supplier_id' => $supplier->id,
            'user_id' => $user->id,
            'type' => Transaction::TYPE_IN,
            'quantity' => 10,
            'unit_price' => 50000,
            'total_value' => 500000,
            'transaction_date' => now(),
            'notes' => 'Pembelian barang baru',
        ];

        $transaction = Transaction::create($transactionData);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals($transactionData['item_id'], $transaction->item_id);
        $this->assertEquals($transactionData['supplier_id'], $transaction->supplier_id);
        $this->assertEquals($transactionData['user_id'], $transaction->user_id);
        $this->assertEquals($transactionData['type'], $transaction->type);
        $this->assertEquals($transactionData['quantity'], $transaction->quantity);
        $this->assertEquals($transactionData['unit_price'], $transaction->unit_price);
        $this->assertEquals($transactionData['total_value'], $transaction->total_value);
        $this->assertEquals($transactionData['notes'], $transaction->notes);
        $this->assertDatabaseHas('transactions', $transactionData);
    }

    /** @test */
    public function it_can_read_a_transaction()
    {
        $transaction = Transaction::factory()->create([
            'type' => Transaction::TYPE_IN,
            'quantity' => 5,
        ]);

        $foundTransaction = Transaction::find($transaction->id);

        $this->assertInstanceOf(Transaction::class, $foundTransaction);
        $this->assertEquals($transaction->type, $foundTransaction->type);
        $this->assertEquals($transaction->quantity, $foundTransaction->quantity);
    }

    /** @test */
    public function it_can_update_a_transaction()
    {
        $transaction = Transaction::factory()->create();
        $newItem = Item::factory()->create();
        $newSupplier = Supplier::factory()->create();

        $updateData = [
            'item_id' => $newItem->id,
            'supplier_id' => $newSupplier->id,
            'type' => Transaction::TYPE_OUT,
            'quantity' => 15,
            'unit_price' => 75000,
            'total_value' => 1125000,
            'notes' => 'Updated transaction notes',
        ];

        $transaction->update($updateData);

        $this->assertEquals($updateData['item_id'], $transaction->fresh()->item_id);
        $this->assertEquals($updateData['supplier_id'], $transaction->fresh()->supplier_id);
        $this->assertEquals($updateData['type'], $transaction->fresh()->type);
        $this->assertEquals($updateData['quantity'], $transaction->fresh()->quantity);
        $this->assertEquals($updateData['unit_price'], $transaction->fresh()->unit_price);
        $this->assertEquals($updateData['total_value'], $transaction->fresh()->total_value);
        $this->assertEquals($updateData['notes'], $transaction->fresh()->notes);
        $this->assertDatabaseHas('transactions', array_merge(['id' => $transaction->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_transaction()
    {
        $transaction = Transaction::factory()->create();
        $transactionId = $transaction->id;

        $transaction->delete();

        $this->assertDatabaseMissing('transactions', ['id' => $transactionId]);
        $this->assertNull(Transaction::find($transactionId));
    }

    /** @test */
    public function it_belongs_to_item()
    {
        $item = Item::factory()->create(['name' => 'Test Item']);
        $transaction = Transaction::factory()->create(['item_id' => $item->id]);

        $this->assertInstanceOf(Item::class, $transaction->item);
        $this->assertEquals($item->id, $transaction->item->id);
        $this->assertEquals('Test Item', $transaction->item->name);
    }

    /** @test */
    public function it_belongs_to_supplier()
    {
        $supplier = Supplier::factory()->create(['name' => 'Test Supplier']);
        $transaction = Transaction::factory()->create(['supplier_id' => $supplier->id]);

        $this->assertInstanceOf(Supplier::class, $transaction->supplier);
        $this->assertEquals($supplier->id, $transaction->supplier->id);
        $this->assertEquals('Test Supplier', $transaction->supplier->name);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create(['name' => 'Test User']);
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $transaction->user);
        $this->assertEquals($user->id, $transaction->user->id);
        $this->assertEquals('Test User', $transaction->user->name);
    }

    /** @test */
    public function it_casts_prices_to_decimal()
    {
        $transaction = Transaction::factory()->create([
            'unit_price' => '50000.50',
            'total_value' => '500000.75'
        ]);

        $this->assertIsNumeric($transaction->unit_price);
        $this->assertIsNumeric($transaction->total_value);
        $this->assertEquals(50000.50, $transaction->unit_price);
        $this->assertEquals(500000.75, $transaction->total_value);
    }

    /** @test */
    public function it_casts_quantity_to_integer()
    {
        $transaction = Transaction::factory()->create(['quantity' => '10']);

        $this->assertIsNumeric($transaction->quantity);
        $this->assertEquals(10, $transaction->quantity);
    }

    /** @test */
    public function it_casts_transaction_date_to_datetime()
    {
        $transaction = Transaction::factory()->create([
            'transaction_date' => '2024-01-15 10:30:00'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $transaction->transaction_date);
    }

    /** @test */
    public function it_has_type_constants()
    {
        $this->assertEquals('masuk', Transaction::TYPE_IN);
        $this->assertEquals('keluar', Transaction::TYPE_OUT);
    }

    /** @test */
    public function it_can_create_incoming_transaction()
    {
        $transaction = Transaction::factory()->create(['type' => Transaction::TYPE_IN]);

        $this->assertEquals(Transaction::TYPE_IN, $transaction->type);
        $this->assertEquals('masuk', $transaction->type);
    }

    /** @test */
    public function it_can_create_outgoing_transaction()
    {
        $transaction = Transaction::factory()->create(['type' => Transaction::TYPE_OUT]);

        $this->assertEquals(Transaction::TYPE_OUT, $transaction->type);
        $this->assertEquals('keluar', $transaction->type);
    }

    /** @test */
    public function it_requires_item_id_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Transaction::create([
            'supplier_id' => Supplier::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'type' => Transaction::TYPE_IN,
            'quantity' => 10,
            'unit_price' => 50000,
            'total_value' => 500000,
            'transaction_date' => now(),
        ]);
    }

    /** @test */
    public function it_requires_type_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Transaction::create([
            'item_id' => Item::factory()->create()->id,
            'supplier_id' => Supplier::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'quantity' => 10,
            'unit_price' => 50000,
            'total_value' => 500000,
            'transaction_date' => now(),
        ]);
    }

    /** @test */
    public function it_requires_quantity_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Transaction::create([
            'item_id' => Item::factory()->create()->id,
            'supplier_id' => Supplier::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'type' => Transaction::TYPE_IN,
            'unit_price' => 50000,
            'total_value' => 500000,
            'transaction_date' => now(),
        ]);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $transaction = Transaction::factory()->create();

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertNotNull($transaction->item_id);
        $this->assertNotNull($transaction->user_id);
        $this->assertNotNull($transaction->type);
        $this->assertIsNumeric($transaction->quantity);
        $this->assertIsNumeric($transaction->unit_price);
        $this->assertIsNumeric($transaction->total_value);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $transaction->transaction_date);
    }

    /** @test */
    public function it_can_create_incoming_transaction_with_factory()
    {
        $transaction = Transaction::factory()->incoming()->create();

        $this->assertEquals(Transaction::TYPE_IN, $transaction->type);
    }

    /** @test */
    public function it_can_create_outgoing_transaction_with_factory()
    {
        $transaction = Transaction::factory()->outgoing()->create();

        $this->assertEquals(Transaction::TYPE_OUT, $transaction->type);
    }

    /** @test */
    public function it_can_have_optional_notes()
    {
        $transaction = Transaction::factory()->create(['notes' => null]);
        
        $this->assertNull($transaction->notes);
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    /** @test */
    public function it_can_have_optional_supplier()
    {
        $transaction = Transaction::factory()->create(['supplier_id' => null]);
        
        $this->assertNull($transaction->supplier_id);
        $this->assertNull($transaction->supplier);
    }

    /** @test */
    public function it_calculates_total_value_correctly()
    {
        $transaction = Transaction::factory()->create([
            'quantity' => 10,
            'unit_price' => 25000,
            'total_value' => 250000
        ]);

        $this->assertEquals($transaction->quantity * $transaction->unit_price, $transaction->total_value);
    }

    /** @test */
    public function it_can_store_large_quantities()
    {
        $transaction = Transaction::factory()->create(['quantity' => 1000]);
        
        $this->assertEquals(1000, $transaction->quantity);
        $this->assertIsNumeric($transaction->quantity);
    }

    /** @test */
    public function it_can_store_high_value_prices()
    {
        $transaction = Transaction::factory()->create([
            'unit_price' => 1000000.50,
            'total_value' => 10000000.75
        ]);
        
        $this->assertEquals(1000000.50, $transaction->unit_price);
        $this->assertEquals(10000000.75, $transaction->total_value);
    }
}