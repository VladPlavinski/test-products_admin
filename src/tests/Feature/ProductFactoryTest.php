<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;
use \Database\Seeders\CategorySeeder;

class ProductFactoryTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }

    public function test_can_create_product_with_existing_category()
    {
        $product = Product::factory()->create();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);

        $this->assertTrue(Category::where('id', $product->category_id)->exists());
    }

    public function test_uses_one_of_existing_categories()
    {
        $existingCategoryIds = Category::all()->pluck('id')->toArray();

        $products = Product::factory()->count(10)->create();

        foreach ($products as $product) {
            $this->assertContains($product->category_id, $existingCategoryIds);
        }
    }

    public function test_respects_manual_category_assignment()
    {
        $specificCategory = Category::where('name', 'Электроника')->first();

        $product = Product::factory()->create([
            'category_id' => $specificCategory->id
        ]);

        $this->assertEquals($specificCategory->id, $product->category_id);
    }

    public function test_creates_valid_products_for_each_category()
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            $product = Product::factory()->create([
                'category_id' => $category->id
            ]);

            $this->assertEquals($category->id, $product->category_id);
            $this->assertNotNull($product->name);
            $this->assertNotNull($product->price);
        }
    }

    public function test_can_create_products_with_specific_name()
    {
        $customName = 'Igame Laptop';

        $product = Product::factory()->create([
            'name' => $customName
        ]);

        $this->assertEquals($customName, $product->name);
    }

    public function test_belongs_to_existing_category()
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertNotNull($product->category->name);
    }

    public function test_handles_empty_categories_gracefully()
    {
        Category::query()->delete();

        try {
            $product = Product::factory()->create();

            $this->assertNotNull($product->category_id);
            $this->assertTrue(Category::where('id', $product->category_id)->exists());
        } catch (\Exception $e) {
            $this->assertStringContainsString('category', $e->getMessage());
        }
    }
}
