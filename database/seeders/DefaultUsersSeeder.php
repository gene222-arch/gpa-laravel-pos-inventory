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
    }


    public function createAdminAccount()
    {
        DB::table('users')
            ->insert([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'created_at' => now()
            ]);
    }
}
