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
            'id' => 'FY202007026',
            'alias_user_id' => '15202005001',
            'email' => 'fadhliyulyanto@gmail.com',
            'name' => 'demo',
            'password' => \Hash::make('123123123'),
            'status' => 'active',
            'language__id' => 1,
            'auth_type__id' => 60
        ]);
        // User::create([
        //     'email' => 'fadhliyulyanto@gmail.com',
        //     'name' => 'demo',
        //     'password' => \Hash::make('123123123'),
        //     'status' => 'active',
        // ]);
    }
}
