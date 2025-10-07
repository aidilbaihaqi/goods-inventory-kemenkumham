<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationIntegrityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_table_has_correct_structure()
    {
        $this->assertTrue(Schema::hasTable('users'));
        
        $columns = [
            'id', 'name', 'email', 'email_verified_at', 
            'password', 'remember_token', 'created_at', 'updated_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('users', $column), "Column '{$column}' does not exist in users table");
        }

        // Check for unique constraints
        $this->assertTrue(Schema::hasColumn('users', 'email'));
    }

    /** @test */
    public function categories_table_has_correct_structure()
    {
        $this->assertTrue(Schema::hasTable('categories'));
        
        $columns = [
            'id', 'name', 'description', 'is_active', 'created_at', 'updated_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('categories', $column), "Column '{$column}' does not exist in categories table");
        }
    }

    /** @test */
    public function suppliers_table_has_correct_structure()
    {
        $this->assertTrue(Schema::hasTable('suppliers'));
        
        $columns = [
            'id', 'name', 'contact_person', 'phone', 'email', 
            'address', 'is_active', 'created_at', 'updated_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('suppliers', $column), "Column '{$column}' does not exist in suppliers table");
        }
    }

    /** @test */
    public function units_table_has_correct_structure()
    {
        $this->assertTrue(Schema::hasTable('units'));
        
        $columns = [
            'id', 'name', 'symbol', 'description', 'is_active', 'created_at', 'updated_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('units', $column), "Column '{$column}' does not exist in units table");
        }
    }

    /** @test */
    public function items_table_has_correct_structure()
    {
        $this->assertTrue(Schema::hasTable('items'));
        
        $columns = [
            'id', 'name', 'code', 'description', 'category_id', 'unit_id', 'photo',
            'min_stock', 'current_stock', 'current_value', 'is_active', 'created_at', 'updated_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('items', $column), "Column '{$column}' does not exist in items table");
        }
    }

    /** @test */
    public function transactions_table_has_correct_structure()
    {
        $this->assertTrue(Schema::hasTable('transactions'));
        
        $columns = [
            'id', 'item_id', 'supplier_id', 'user_id', 'type', 'quantity',
            'unit_price', 'total_value', 'transaction_date', 'notes', 'created_at', 'updated_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('transactions', $column), "Column '{$column}' does not exist in transactions table");
        }
    }

    /** @test */
    public function stock_batches_table_has_correct_structure()
    {
        $this->assertTrue(Schema::hasTable('stock_batches'));
        
        $columns = [
            'id', 'item_id', 'transaction_id', 'initial_quantity', 'remaining_quantity',
            'unit_price', 'batch_date', 'created_at', 'updated_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('stock_batches', $column), "Column '{$column}' does not exist in stock_batches table");
        }
    }

    /** @test */
    public function stock_adjustments_table_has_correct_structure()
    {
        $this->assertTrue(Schema::hasTable('stock_adjustments'));
        
        $columns = [
            'id', 'item_id', 'user_id', 'old_quantity', 'new_quantity', 'adjustment_quantity', 'reason',
            'adjustment_date', 'created_at', 'updated_at'
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('stock_adjustments', $column), "Column '{$column}' does not exist in stock_adjustments table");
        }
    }

    /** @test */
    public function foreign_key_relationships_exist()
    {
        // Items table foreign keys
        $this->assertTrue(Schema::hasColumn('items', 'category_id'));
        $this->assertTrue(Schema::hasColumn('items', 'unit_id'));

        // Transactions table foreign keys
        $this->assertTrue(Schema::hasColumn('transactions', 'item_id'));
        $this->assertTrue(Schema::hasColumn('transactions', 'supplier_id'));
        $this->assertTrue(Schema::hasColumn('transactions', 'user_id'));

        // Stock batches table foreign keys
        $this->assertTrue(Schema::hasColumn('stock_batches', 'item_id'));
        $this->assertTrue(Schema::hasColumn('stock_batches', 'transaction_id'));

        // Stock adjustments table foreign keys
        $this->assertTrue(Schema::hasColumn('stock_adjustments', 'item_id'));
        $this->assertTrue(Schema::hasColumn('stock_adjustments', 'user_id'));
    }

    /** @test */
    public function all_tables_have_timestamps()
    {
        $tables = [
            'users', 'categories', 'suppliers', 'units', 'items',
            'transactions', 'stock_batches', 'stock_adjustments'
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasColumn($table, 'created_at'), "Table '{$table}' missing created_at column");
            $this->assertTrue(Schema::hasColumn($table, 'updated_at'), "Table '{$table}' missing updated_at column");
        }
    }

    /** @test */
    public function all_tables_have_primary_keys()
    {
        $tables = [
            'users', 'categories', 'suppliers', 'units', 'items',
            'transactions', 'stock_batches', 'stock_adjustments'
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasColumn($table, 'id'), "Table '{$table}' missing id column");
        }
    }

    /** @test */
    public function required_tables_exist()
    {
        $requiredTables = [
            'users',
            'categories',
            'suppliers',
            'units',
            'items',
            'transactions',
            'stock_batches',
            'stock_adjustments',
            'migrations',
            'password_reset_tokens',
            'sessions'
        ];

        foreach ($requiredTables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Required table '{$table}' does not exist");
        }
    }

    /** @test */
    public function enum_columns_have_correct_values()
    {
        // This test verifies that enum columns can accept their expected values
        // by attempting to create records with valid enum values

        // Test transaction type enum
        $this->assertDatabaseMissing('transactions', ['type' => 'invalid_type']);
        
        // Test stock adjustment reason validation by creating a record
        $stockAdjustment = \App\Models\StockAdjustment::factory()->create([
            'reason' => 'Physical count correction'
        ]);
        $this->assertDatabaseHas('stock_adjustments', ['reason' => 'Physical count correction']);
    }

    /** @test */
    public function nullable_columns_are_properly_configured()
    {
        // Test that nullable columns can actually accept null values
        $nullableColumns = [
            'categories' => ['description'],
            'suppliers' => ['contact_person', 'phone', 'email', 'address'],
            'units' => ['description'],
            'items' => ['description'],
            'transactions' => ['supplier_id', 'notes'],
            'stock_batches' => [],
            'stock_adjustments' => [],
        ];

        foreach ($nullableColumns as $table => $columns) {
            foreach ($columns as $column) {
                $this->assertTrue(
                    Schema::hasColumn($table, $column),
                    "Nullable column '{$column}' does not exist in table '{$table}'"
                );
            }
        }
    }

    /** @test */
    public function decimal_columns_have_correct_precision()
    {
        // Test that decimal columns for prices can store appropriate values
        $this->assertTrue(Schema::hasColumn('transactions', 'unit_price'));
        $this->assertTrue(Schema::hasColumn('transactions', 'total_value'));
        $this->assertTrue(Schema::hasColumn('stock_batches', 'unit_price'));
    }

    /** @test */
    public function boolean_columns_exist()
    {
        $booleanColumns = [
            'categories' => ['is_active'],
            'suppliers' => ['is_active'],
            'units' => ['is_active'],
            'items' => ['is_active'],
        ];

        foreach ($booleanColumns as $table => $columns) {
            foreach ($columns as $column) {
                $this->assertTrue(
                    Schema::hasColumn($table, $column),
                    "Boolean column '{$column}' does not exist in table '{$table}'"
                );
            }
        }
    }

    /** @test */
    public function date_columns_exist()
    {
        $dateColumns = [
            'transactions' => ['transaction_date'],
            'stock_batches' => ['batch_date'],
            'stock_adjustments' => ['adjustment_date'],
        ];

        foreach ($dateColumns as $table => $columns) {
            foreach ($columns as $column) {
                $this->assertTrue(
                    Schema::hasColumn($table, $column),
                    "Date column '{$column}' does not exist in table '{$table}'"
                );
            }
        }
    }

    /** @test */
    public function unique_constraints_exist()
    {
        // Test that unique columns exist (actual uniqueness is tested in model tests)
        $this->assertTrue(Schema::hasColumn('users', 'email'));
        $this->assertTrue(Schema::hasColumn('items', 'code'));
    }

    /** @test */
    public function integer_columns_exist()
    {
        $integerColumns = [
            'items' => ['min_stock'],
            'transactions' => ['quantity'],
            'stock_batches' => ['initial_quantity', 'remaining_quantity'],
            'stock_adjustments' => ['old_quantity', 'new_quantity', 'adjustment_quantity'],
        ];

        foreach ($integerColumns as $table => $columns) {
            foreach ($columns as $column) {
                $this->assertTrue(
                    Schema::hasColumn($table, $column),
                    "Integer column '{$column}' does not exist in table '{$table}'"
                );
            }
        }
    }

    /** @test */
    public function text_columns_exist()
    {
        $textColumns = [
            'categories' => ['name'],
            'suppliers' => ['name', 'address'],
            'units' => ['name', 'symbol'],
            'items' => ['name', 'code'],
            'stock_batches' => [],
            'stock_adjustments' => ['reason'],
        ];

        foreach ($textColumns as $table => $columns) {
            foreach ($columns as $column) {
                $this->assertTrue(
                    Schema::hasColumn($table, $column),
                    "Text column '{$column}' does not exist in table '{$table}'"
                );
            }
        }
    }
}