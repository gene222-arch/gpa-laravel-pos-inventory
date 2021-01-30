<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DefaultModelRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->assignAdminRole();
        $this->assignManagerRole();
        $this->assignCashierRole();
    }

    public function assignAdminRole()
    {
        $role = Role::find(1);
        $admin = User::find(1);

        $admin->assignRole($role);
    }

    public function assignManagerRole()
    {
        $role = Role::find(2);
        $manager = User::find(2);

        $manager->assignRole($role);
    }

    /**
     * ! Role not assigned
     *
     * @return void
     */
    public function assignCashierRole()
    {
        $role = Role::find(3);
        $cashier = User::find(3);
    }

}
