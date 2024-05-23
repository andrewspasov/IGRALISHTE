<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('brand_categories')->insert([
            ['category_name' => 'Pants'],
            ['category_name' => 'Jeans'],
            ['category_name' => 'Hoodies'],
            ['category_name' => 'Skirts'],
            ['category_name' => 'Sneakers'],
            ['category_name' => 'Jackets'],
            ['category_name' => 'Shirts'],
        ]);
    }
}
