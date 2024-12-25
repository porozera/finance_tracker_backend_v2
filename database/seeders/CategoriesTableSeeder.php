<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Housing', 'icon' => 'house', 'color' => '#FF5733'],
            ['name' => 'Transportation', 'icon' => 'car', 'color' => '#33FF57'],
            ['name' => 'Food', 'icon' => 'restaurant', 'color' => '#FF33A6'],
            ['name' => 'Utilities', 'icon' => 'plug', 'color' => '#33A6FF'],
            // Add more categories if needed
        ]);
    }
}
