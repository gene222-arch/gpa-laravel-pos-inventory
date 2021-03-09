<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class DefaultPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')
            ->insert([
                [
                    'name' => 'View Dashboard',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage POS',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'View Reports',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Products',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Import Products',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Categories',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Discounts',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage System Permission',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Purchase Orders',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Bad Orders',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Suppliers',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Stock Adjustments',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Customers',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Access Rights',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Employees',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'View Transactions',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'View All Receipts',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Invoices',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Settings',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Sales Returns',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'Manage Account',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
                [
                    'name' => 'View Receipts',
                    'guard_name' => 'api',
                    'created_at' => now()
                ],
            ]);
    }


}
