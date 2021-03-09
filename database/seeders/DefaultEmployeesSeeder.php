<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')
            ->insert([
                'name' => 'Administrator',
                'email' => 'genephillip222@gmail.com',
                'phone' => 154898456984,
                'role' => 'Super Admin',
                'created_at' => now()
            ]);
    }
}
