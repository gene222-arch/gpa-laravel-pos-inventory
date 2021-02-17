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
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->selectRaw('
                customers.id as id,
                customers.name as customer,
                DATE_FORMAT(MIN(pos.created_at), "%M %d %Y") as first_visit,
                DATE_FORMAT(MAX(pos.created_at), "%M %d %Y") as last_visit,
                COUNT(pos.customer_id) as total_visits,
                SUM(pos_details.total) as total_spent
            ')
            ->groupBy('customers.id')
            ->get()
            ->toArray();
    }

}
