<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //User::factory(10)->create();
        User::create([
            // 'id' => '1',
            'name' => 'admin',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('admin');

        User::create([
            // 'id' => '2',
            'name' => 'player1',
            'email' => 'player1@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('player');

    }
}
