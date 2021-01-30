<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdminAccount();
        $this->createManagerAccount();
        $this->createCashierAccount();
    }


    public function createAdminAccount()
    {
        DB::table('users')->updateOrInsert([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin@admin.com'),
        ]);
    }

    public function createManagerAccount()
    {
        DB::table('users')->updateOrInsert([
            'name' => 'Manager',
            'email' => 'manager@manager.com',
            'password' => Hash::make('manager@manager.com'),
        ]);
    }

    public function createCashierAccount()
    {
        DB::table('users')->updateOrInsert([
            'name' => 'Cashier',
            'email' => 'cashier@cashier.com',
            'password' => Hash::make('cashier@cashier.com'),
        ]);
    }


}
