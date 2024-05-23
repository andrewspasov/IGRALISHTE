<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountCategoriesSeeder extends Seeder
{
    public function run()
    {
        $discountCategories = ['Xmas Promo', 'Summer Sale', 'Black Friday'];
        
        foreach ($discountCategories as $category) {
            DB::table('discount_categories')->insert(['discount_category_name' => $category]);
        }
    }
}
