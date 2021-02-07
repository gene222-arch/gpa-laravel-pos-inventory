<?php

namespace App\Traits\Customer;

use Illuminate\Support\Facades\DB;

trait CustomerServices
{
    /**
     *
     * @return array
     */
    public function loadCustomers(): array
    {
        DB::statement('SET sql_mode = "" ');

        return DB::table('customers')
            ->join('pos', 'pos.customer_id', '=', 'customers.id')
            ->selectRaw('
                customers.id as customer_id,
                customers.name as customer_name,
                DATE(MIN(pos.created_at)) as first_visit,
                DATE(MAX(pos.created_at)) as last_visited,
                COUNT(pos.customer_id) as total_visits
            ')
            ->groupBy('customers.id')
            ->get()
            ->toArray();
    }

}
