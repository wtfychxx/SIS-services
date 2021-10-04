<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'fadhliyulyanto@gmail.com',
            'name' => 'Fadhli',
            'password' => \Hash::make('123123123'),
            'status' => 'active'
        ]);
    }
}
