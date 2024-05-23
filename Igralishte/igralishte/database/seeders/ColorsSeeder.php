<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::create(['color_name' => 'Red', 'color_code' => '#FF0000']);
        Color::create(['color_name' => 'Yellow', 'color_code' => '#FFFF00']);
        Color::create(['color_name' => 'Green', 'color_code' => '#008000']);
        Color::create(['color_name' => 'Blue', 'color_code' => '#0000FF']);
        Color::create(['color_name' => 'Pink', 'color_code' => '#FFC0CB']);
        Color::create(['color_name' => 'Purple', 'color_code' => '#800080']);
        Color::create(['color_name' => 'Gray', 'color_code' => '#808080']);
        Color::create(['color_name' => 'White', 'color_code' => '#FFFFFF']);
        Color::create(['color_name' => 'Black', 'color_code' => '#000000']);
    }
}
