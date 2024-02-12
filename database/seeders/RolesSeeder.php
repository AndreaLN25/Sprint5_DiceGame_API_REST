<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

use Spatie\Permission\Models\Role;


class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(["name" => "admin",'guard_name' => 'api']);
        $playerRole = Role::create(["name" => "player",'guard_name' => 'api']);


        Permission::create(['name'=>'registerUser']);
        Permission::create(['name'=>'login']);

        Permission::create(['name'=>'updateUser'])->assignRole($adminRole);
        Permission::create(['name'=>'logout'])->syncRoles([$adminRole,$playerRole]);

        

        Permission::create(['name'=>'getPlayerList'])->assignRole($adminRole);
        Permission::create(['name'=>'getAverageSuccessPercentage'])->syncRoles([$adminRole,$playerRole]);
        Permission::create(['name'=>'getWorstPlayer'])->assignRole($adminRole);
        Permission::create(['name'=>'getBestPlayer'])->assignRole($adminRole);


        Permission::create(['name'=>'playGame'])->assignRole($playerRole);
        Permission::create(['name'=>'deleteGames'])->assignRole($playerRole);
        Permission::create(['name'=>'getPlayerGames'])->assignRole($playerRole);
    }
}
