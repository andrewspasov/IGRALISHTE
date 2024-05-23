<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizesSeeder extends Seeder
{
    public function run()
    {
        $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL'];

        foreach ($sizes as $size) {
            DB::table('sizes')->insert(['size' => $size]);
        }
    }
}
