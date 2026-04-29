<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;
    protected $category;
    protected $products;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $this->products = Product::factory()->count(20)->create();
    }

    public function test_can_get_products_list_with_pagination()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'price', 'category']
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
                'links' => ['first', 'last', 'prev', 'next']
            ])
            ->assertJsonCount(10, 'data');
    }

    public function test_can_get_products_list_with_custom_pagination()
    {
        $response = $this->getJson('/api/products?per_page=5');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5);
    }

    public function test_can_get_single_product()
    {
        $product = $this->products->first();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'category',
                    'category_id',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.name', $product->name)
            ->assertJsonPath('data.description', $product->description)
            ->assertJsonPath('data.price', (float) $product->price);
    }

    public function test_returns_404_for_non_existent_product()
    {
        $response = $this->getJson('/api/products/99999');

        $response->assertStatus(404);
    }

    public function test_includes_category_data_in_product_response()
    {
        $product = $this->products->first();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.category_id', $this->category->id)
            ->assertJsonPath('data.category', $this->category->name);
    }
}
