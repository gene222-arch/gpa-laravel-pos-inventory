<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultModelHasPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('model_has_permissions')
            ->insert([
                [
                    'permission_id' => 1,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 2,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 3,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 4,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 5,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 6,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 7,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 8,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 9,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 10,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 11,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 12,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 13,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 14,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 15,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 16,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 17,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 18,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 19,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 20,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 21,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
                [
                    'permission_id' => 22,
                    'model_type' => 'App\Models\User',
                    'model_id' => 1
                ],
            ]);
    }
}
