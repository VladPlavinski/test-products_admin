<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Inertia\Testing\AssertableInertia as Assert;

class ProductsIndexPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_products_index_page()
    {
        $response = $this->get(route('products'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Products')
        );
    }
}
