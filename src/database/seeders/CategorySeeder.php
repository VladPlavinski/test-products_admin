<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Товары для дома',
                'description' => 'Различные товары для дома',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Товары для сада',
                'description' => 'Вёдра, лейки и всё, что может пригодиться в саду',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Электроника',
                'description' => 'Различная домашняя электроника',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Авто',
                'description' => 'Дополнительные товары для автомобилей',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Одежда',
                'description' => 'Предметы одежды',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
