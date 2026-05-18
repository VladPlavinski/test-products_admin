<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Product;
use App\Models\Category;
use Tests\TestCase;
use App\Services\ProductService;

class ProductServiceTest extends TestCase
{

    use RefreshDatabase;

    private Category $category1;
    private Category $category2;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category1 = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $this->category2 = Category::create([
            'name' => 'Авто',
            'description' => 'Дополнительные товары для автомобилей',
        ]);

        $this->product = Product::create([
            'name' => 'Igame Laptop',
            'description' => 'Игровой ноутбук',
            'price' => 999.99,
            'category_id' => $this->category1->id,
        ]);

        Product::factory()->count(14)->create();
    }

    public function test_returns_all_products_with_no_filters(): void
    {
        $result = ProductService::getProductsWithCategory(0, '', 20);

        $this->assertCount(15, $result->items());
    }

    public function test_filters_products_by_category()
    {
        $result = ProductService::getProductsWithCategory($this->category1->id, '', 10);

        foreach ($result->items() as $product) {
            $this->assertEquals($this->category1->id, $product->category_id);
        }
    }

    public function test_searches_products_by_name()
    {
        $result = ProductService::getProductsWithCategory(0, $this->product->name, 10);

        $this->assertEquals(1, $result->total());
        $this->assertEquals($this->product->name, $result->items()[0]->name);
    }

    public function test_combines_category_and_search_filters()
    {
        $result = ProductService::getProductsWithCategory($this->category1->id, $this->product->name, 10);

        $this->assertEquals(1, $result->total());
        $this->assertEquals($this->product->name, $result->items()[0]->name);
        $this->assertEquals($this->category1->id, $result->items()[0]->category_id);
    }

    public function test_uses_default_per_page_value()
    {
        $result = ProductService::getProductsWithCategory(0, '');

        $this->assertEquals(10, $result->perPage());
    }

    public function test_uses_custom_per_page_value()
    {
        $result = ProductService::getProductsWithCategory(0, '', 7);

        $this->assertCount(7, $result->items());
    }

    public function test_handles_not_existed_category()
    {
        $result = ProductService::getProductsWithCategory(999, '', 10);

        $this->assertEmpty($result->items());
    }

    public function test_handles_not_existed_search()
    {
        $result = ProductService::getProductsWithCategory(0, 'NOT EXISTED', 10);

        $this->assertEmpty($result->items());
    }

}
