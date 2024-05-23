<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'first_name' => 'Regular',
            'last_name' => 'User',
            'username' => 'regular',
            'email' => 'regular@mail.com',
            'password' => Hash::make('regular27'),
            'role' => 'user',
        ]);
    }
}
