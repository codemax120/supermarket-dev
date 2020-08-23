<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Role::create(['name' => 'Admin']);
        \App\Role::create(['name' => 'SupermarketManager']);
        \App\Role::create(['name' => 'BranchManager']);
    }
}
