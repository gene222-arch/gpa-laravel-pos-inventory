<?php

namespace Database\Seeders;

use App\Models\AccessRights;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultAccessRightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('access_rights')
            ->insert([
                'role_id' => 1,
                'back_office' => true,
                'pos' => true,
                'created_at' => now()
            ]);
    }
}
