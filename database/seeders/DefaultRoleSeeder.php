<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createSuperAdminRole();
    }

    public function createSuperAdminRole()
    {
        Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'api',
        ]);
    }
}
