<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    }

    public function createAdminRole()
    {
        DB::table('roles')->insert([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);
    }


    public function createManagerRole()
    {
        DB::table('roles')->insert([
            'name' => 'manager',
            'guard_name' => 'api',
        ]);
    }


    public function createCashierRole()
    {
        DB::table('roles')->insert([
            'name' => 'cashier',
            'guard_name' => 'api',
        ]);
    }
}
