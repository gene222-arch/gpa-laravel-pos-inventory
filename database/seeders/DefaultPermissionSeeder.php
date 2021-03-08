<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        $this->permissions();
    }

    public function permissions()
    {
        Permission::create([
            [
                'name' => 'View Dashboard',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage POS',
                'guard_name' => 'api'
            ],
            [
                'name' => 'View Reports',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Products',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Import Products',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Categories',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Discounts',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage System Permission',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Purchase Orders',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Bad Orders',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Suppliers',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Stock Adjustments',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Customers',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Access Rights',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Employees',
                'guard_name' => 'api'
            ],
            [
                'name' => 'View Transactions',
                'guard_name' => 'api'
            ],
            [
                'name' => 'View All Receipts',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Invoices',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Settings',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Sales Returns',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Account',
                'guard_name' => 'api'
            ],
            [
                'name' => 'View Receipts',
                'guard_name' => 'api'
            ],
        ]);
    }


}
