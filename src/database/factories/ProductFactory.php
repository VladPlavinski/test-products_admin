<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Seeders\CategorySeeder;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoriesIds = Category::all()->pluck('id')->toArray();
        if (empty($categoriesIds)) {
            $this->seedCategories();
            $categoriesIds = Category::all()->pluck('id')->toArray();
        }

        return [
            'name' => $this->faker->realText(30),
            'description' => $this->faker->realText(300),
            'price' => $this->faker->randomFloat(2, 10),
            'category_id' => $this->faker->randomElement($categoriesIds),
        ];
    }

    private function seedCategories(): void
    {
        $seeder = new CategorySeeder();
        $seeder->run();
    }

}
