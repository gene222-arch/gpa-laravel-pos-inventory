<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DefaultPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->categoryPermissions();
        $this->productPermissions();
    }

    public function categoryPermissions()
    {
        Permission::create([
            'name' => 'view_categories',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'create_categories',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'update_categories',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'delete_categories',
            'guard_name' => 'api'
        ]);
    }


    public function productPermissions()
    {
        Permission::create([
            'name' => 'view_products',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'create_products',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'update_products',
            'guard_name' => 'api'
        ]);

        Permission::create([
            'name' => 'delete_products',
            'guard_name' => 'api'
        ]);
    }


}
