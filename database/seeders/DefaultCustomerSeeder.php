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
                'email' => '',
                'phone' => '',
                'address' => '',
                'city' => '',
                'province' => '',
                'postal_code' => '',
                'country' => '',
                'created_at' => now(),
            ]);
    }
}
