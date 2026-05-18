<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductDetailPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_product_detail_page()
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

        $response = $this->get(route('product', ['id' => $product->id]));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Product')
            ->has('id')
        );
    }
}
