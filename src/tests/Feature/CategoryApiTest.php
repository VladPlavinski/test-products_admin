<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \Database\Seeders\CategorySeeder;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_categories_list_without_pagination()
    {
        $this->seed(CategorySeeder::class);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'products_count', 'created_at']
                ]
            ])
            ->assertJsonCount(5, 'data');
    }

    public function test_returns_empty_array_when_no_categories()
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_includes_products_count_for_each_category()
    {
        $category1 = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);
        $category2 = Category::create([
            'name' => 'Авто',
            'description' => 'Дополнительные товары для автомобилей',
        ]);

        Product::factory()->count(5)->create(['category_id' => $category1->id]);
        Product::factory()->count(3)->create(['category_id' => $category2->id]);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.products_count', 5)
            ->assertJsonPath('data.1.products_count', 3);
    }

    public function test_returns_correct_category_data_structure()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'products_count',
                        'created_at'
                    ]
                ]
            ])
            ->assertJsonPath('data.0.name', $category->name)
            ->assertJsonPath('data.0.description', $category->description);
    }

    public function test_returns_products_count_zero_for_category_without_products()
    {
        $category = Category::create([
            'name' => 'Электроника',
            'description' => 'Различная домашняя электроника',
        ]);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.products_count', 0);
    }
}
