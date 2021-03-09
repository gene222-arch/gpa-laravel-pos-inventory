<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSystemPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_permission')
            ->insert([
                [
                    'name' => 'POS',
                    'permission_id' => 2,
                    'created_at' => now()
                ],
                [
                    'name' => 'POS',
                    'permission_id' => 17,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 1,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 3,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 4,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 5,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 6,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 7,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 9,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 10,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 11,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 12,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 13,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 16,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 18,
                    'created_at' => now()
                ],
                [
                    'name' => 'Back Office',
                    'permission_id' => 19,
                    'created_at' => now()
                ],
            ]);
    }
}
