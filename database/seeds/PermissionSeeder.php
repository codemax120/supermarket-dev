<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Permission::insert([
            //-- Users
            ['name' => 'view_users'],
            ['name' => 'add_users'],
            ['name' => 'edit_users'],
            ['name' => 'delete_users'],
            //-- Supermarket
            ['name' => 'view_supermarkets'],
            ['name' => 'add_supermarkets'],
            ['name' => 'edit_supermarkets'],
            ['name' => 'delete_supermarkets'],
            //-- Supermarket-branches
            ['name' => 'view_supermarket_branches'],
            ['name' => 'add_supermarket_branches'],
            ['name' => 'edit_supermarket_branches'],
            ['name' => 'delete_supermarket_branches'],
            //-- Categories
            ['name' => 'view_categories'],
            ['name' => 'add_categories'],
            ['name' => 'edit_categories'],
            ['name' => 'delete_categories'],
            //-- Products
            ['name' => 'view_products'],
            ['name' => 'add_products'],
            ['name' => 'edit_products'],
            ['name' => 'delete_products'],
            //-- Inventories
            ['name' => 'view_inventories'],
            ['name' => 'add_inventories'],
            ['name' => 'edit_inventories'],
            ['name' => 'delete_inventories'],

            // Movements

            // Reporting

        ]);
    }
}
