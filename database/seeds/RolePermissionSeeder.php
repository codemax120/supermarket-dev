<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PERMISSIONS FOR ADMIN
        $permissions = Permission::all();
        $admin = Role::where('name', 'Admin')->first();

        foreach ($permissions as $permission) {
            DB::table('role_permission')->insert([
                'role_id' => $admin->id,
                'permission_id' => $permission->id
            ]);
        }

        // PERMISSION FOR SUPERMARKET USER MANAGER
        $supermarketManager = Role::where('name', 'SupermarketManager')->first();
        $noPermissionForSupermarketManager = [
            'delete_users',
            'delete_supermarkets',
            'edit_supermarket_branches',
            'delete_supermarket_branches'
        ];

        foreach ($permissions as $permission) {
            if (!in_array($permission->name, $noPermissionForSupermarketManager)) {
                DB::table('role_permission')->insert([
                    'role_id' => $supermarketManager->id,
                    'permission_id' => $permission->id
                ]);
            }
        }

        // PERMISSIONS FOR SUPERMARKET BRANCH USER MANAGER
        $branchManager = Role::where('name', 'BranchManager')->first();
        $noPermissionForBranchManager = [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_supermarkets',
            'add_supermarkets',
            'edit_supermarkets',
            'delete_supermarkets',

            'add_supermarket_branches',
            'edit_supermarket_branches',
            'delete_supermarket_branches'
        ];

        foreach ($permissions as $permission) {
            if (!in_array($permission->name, $noPermissionForBranchManager)) {
                DB::table('role_permission')->insert([
                    'role_id' => $branchManager->id,
                    'permission_id' => $permission->id
                ]);
            }
        }


    }
}
