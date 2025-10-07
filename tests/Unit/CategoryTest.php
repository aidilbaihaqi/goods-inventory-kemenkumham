<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_category()
    {
        $categoryData = [
            'name' => 'Elektronik',
            'description' => 'Peralatan elektronik dan gadget',
            'is_active' => true,
        ];

        $category = Category::create($categoryData);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($categoryData['name'], $category->name);
        $this->assertEquals($categoryData['description'], $category->description);
        $this->assertTrue($category->is_active);
        $this->assertDatabaseHas('categories', $categoryData);
    }

    /** @test */
    public function it_can_read_a_category()
    {
        $category = Category::factory()->create([
            'name' => 'Furniture',
            'description' => 'Meja, kursi, lemari dan perabotan kantor',
        ]);

        $foundCategory = Category::find($category->id);

        $this->assertInstanceOf(Category::class, $foundCategory);
        $this->assertEquals($category->name, $foundCategory->name);
        $this->assertEquals($category->description, $foundCategory->description);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $category = Category::factory()->create();

        $updateData = [
            'name' => 'Updated Category Name',
            'description' => 'Updated description',
            'is_active' => false,
        ];

        $category->update($updateData);

        $this->assertEquals($updateData['name'], $category->fresh()->name);
        $this->assertEquals($updateData['description'], $category->fresh()->description);
        $this->assertFalse($category->fresh()->is_active);
        $this->assertDatabaseHas('categories', array_merge(['id' => $category->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $category = Category::factory()->create();
        $categoryId = $category->id;

        $category->delete();

        $this->assertDatabaseMissing('categories', ['id' => $categoryId]);
        $this->assertNull(Category::find($categoryId));
    }

    /** @test */
    public function it_has_many_items_relationship()
    {
        $category = Category::factory()->create();
        $items = Item::factory(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->items);
        $this->assertInstanceOf(Item::class, $category->items->first());
    }

    /** @test */
    public function it_casts_is_active_to_boolean()
    {
        $category = Category::factory()->create(['is_active' => 1]);

        $this->assertIsBool($category->is_active);
        $this->assertTrue($category->is_active);

        $category->update(['is_active' => 0]);
        $this->assertIsBool($category->fresh()->is_active);
        $this->assertFalse($category->fresh()->is_active);
    }

    /** @test */
    public function it_requires_name_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Category::create([
            'description' => 'Test description',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $category = Category::factory()->create();

        $this->assertInstanceOf(Category::class, $category);
        $this->assertNotNull($category->name);
        $this->assertNotNull($category->description);
        $this->assertIsBool($category->is_active);
    }

    /** @test */
    public function it_can_create_active_category_with_factory()
    {
        $category = Category::factory()->active()->create();

        $this->assertTrue($category->is_active);
    }

    /** @test */
    public function it_can_create_inactive_category_with_factory()
    {
        $category = Category::factory()->inactive()->create();

        $this->assertFalse($category->is_active);
    }
}