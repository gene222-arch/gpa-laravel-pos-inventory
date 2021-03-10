<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')
            ->insert([
                'name' => 'Walk in',
                'email' => 'genephillip222@gmail.com',
                'phone' => 'NULL',
                'address' => 'NULL',
                'city' => 'NULL',
                'province' => 'NULL',
                'postal_code' => 'NULL',
                'country' => 'NULL',
                'created_at' => now(),
            ]);
    }
}
