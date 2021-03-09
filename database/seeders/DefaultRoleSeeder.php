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
        DB::table('roles')
            ->insert([
                'name' => 'Super Admin',
                'guard_name' => 'api',
                'created_at' => now()
            ]);
    }
}
