<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DefaultModelPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }


    public function giveAdminPermissions()
    {
        $permissions = Permission::all();
        $admin = User::find(1);

        $admin->givePermissionTo($permissions);

    }

    /**
     * ! Permissions not assigned
     *
     * @return void
     */
    public function giveManagerPermissions()
    {
        $permissions = Permission::all();
        $manager = User::find(2);

        $manager->givePermissionTo($permissions);
    }


    public function giveCashuerPermissions()
    {
        $permissions = Permission::all();
        $cashier = User::find(3);

    }

}
