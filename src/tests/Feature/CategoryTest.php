<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_create_a_category()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);
    }

    public function test_can_read_a_category()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $foundCategory = Category::find($category->id);

        $this->assertEquals('Электроника', $foundCategory->name);
        $this->assertEquals('Различная домашняя электроника', $foundCategory->description);
    }

    public function test_can_update_a_category()
    {
        $category = Category::create([
            'name' => 'Электроника старая',
            'description' => 'Одинаковая домашняя электроника',
        ]);

        $category->update([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);
    }

    public function test_can_delete_a_category()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $category->delete();

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_has_many_products()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product1 = Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $product2 = Product::create([
            'name' => 'MouseXS',
            'description' => 'Игровая мышь',
            'price' => 29.99,
            'category_id' => $category->id,
        ]);

        $this->assertCount(2, $category->products);
        $this->assertTrue($category->products->contains($product1));
        $this->assertTrue($category->products->contains($product2));
    }

    public function test_has_timestamps()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $this->assertNotNull($category->created_at);
        $this->assertNotNull($category->updated_at);
        $this->assertInstanceOf(Carbon::class, $category->created_at);
        $this->assertInstanceOf(Carbon::class, $category->updated_at);
    }

    public function test_requires_name_when_creating()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Category::create([
            'description' => 'Без имени',
        ]);
    }

    public function test_can_get_products_count()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $this->assertEquals(1, $category->products()->count());
    }
}
