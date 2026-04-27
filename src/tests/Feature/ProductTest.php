<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_create_a_product()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product = Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);
    }

    public function test_can_read_a_product()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product = Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $foundProduct = Product::find($product->id);

        $this->assertEquals('Igame Laptop', $foundProduct->name);
        $this->assertEquals('Игровой ноутбук', $foundProduct->description);
        $this->assertEquals(999.99, $foundProduct->price);
        $this->assertEquals($category->id, $foundProduct->category_id);
    }

    public function test_can_update_a_product()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product = Product::create([
            'name' => 'Igame Notop',
            'description' => 'Игровой небук',
            'price' => 3.03,
            'category_id' => $category->id,
        ]);

        $product->update([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.00,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.00,
        ]);
    }

    public function test_can_soft_delete_a_product()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product = Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $product->delete();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);

        $this->assertNotNull($product->fresh()->deleted_at);
    }

    public function test_can_restore_a_soft_deleted_product()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product = Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $product->delete();
        $product->restore();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null,
        ]);
    }

    public function test_belongs_to_a_category()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product = Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
        $this->assertEquals('Электроника', $product->category->name);
    }

    public function test_has_timestamps()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product = Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $this->assertNotNull($product->created_at);
        $this->assertNotNull($product->updated_at);
        $this->assertInstanceOf(Carbon::class, $product->created_at);
        $this->assertInstanceOf(Carbon::class, $product->updated_at);
    }

    public function test_requires_name_when_creating()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Product::create([
            'description' => 'Без имени',
            'price' => 199.99,
            'category_id' => $category->id,
        ]);
    }

    public function test_requires_price_when_creating()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Product::create([
            'name' => 'Бесплатный телефон',
            'description' => 'Без цены',
            'category_id' => $category->id,
        ]);
    }

    public function test_requires_valid_category_id()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Product::create([
            'name' => 'Любой товар',
            'description' => 'С несуществующей категорией',
            'price' => 199.99,
            'category_id' => 1000,
        ]);
    }

    public function test_can_scope_active_products()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $activeProduct = Product::create([
            'name' => 'Igame active Laptop',
            'description' => 'Активный игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $deletedProduct = Product::create([
            'name' => 'Igame deleted Laptop',
            'description' => 'Удалённый игровой ноутбук',
            'price' => 111.11,
            'category_id' => $category->id,
        ]);
        $deletedProduct->delete();

        $activeProducts = Product::all();
        $allProducts = Product::withTrashed()->get();

        $this->assertCount(1, $activeProducts);
        $this->assertCount(2, $allProducts);
        $this->assertEquals('Igame active Laptop', $activeProducts->first()->name);
    }

    public function test_has_price_as_float()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product = Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $category->id,
        ]);

        $this->assertIsFloat($product->price);
        $this->assertEquals(999.99, $product->price);
    }

    public function test_can_use_factory()
    {
        Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $product = Product::factory()->create();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
        $this->assertNotNull($product->name);
        $this->assertNotNull($product->price);
    }
}
