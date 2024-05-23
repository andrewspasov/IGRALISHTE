<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'product_name' => 'Test Product5',
            'description' => 'This is a test product for demdqwonstrdweation purposes.',
            'price' => 99.99,
            'how_many' => 5,
            'size_id' => 2,
            'color_id' => 2,
            'brand_id' => 2,
            'brand_category_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'is_active' => 1,
        ]);
    }
}
