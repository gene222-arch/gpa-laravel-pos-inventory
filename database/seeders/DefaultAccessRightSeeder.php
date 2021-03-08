<?php

namespace Database\Seeders;

use App\Models\AccessRights;
use Illuminate\Database\Seeder;

class DefaultAccessRightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccessRights::create([
            'role_id' => 1,
            'back_office' => true,
            'pos' => true
        ]);
    }
}
