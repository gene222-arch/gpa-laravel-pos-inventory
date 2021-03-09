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
            ->leftJoin('pos', 'pos.customer_id', '=', 'customers.id')
            ->leftJoin('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->selectRaw('
                customers.id as id,
                customers.name as customer,
                DATE_FORMAT(MIN(pos.created_at), "%M %d %Y") as first_visit,
                DATE_FORMAT(MAX(pos.created_at), "%M %d %Y") as last_visit,
                COUNT(pos.customer_id) as total_visits,
                CASE
                    WHEN
                        SUM(pos_details.total) IS NULL
                    THEN
                        0.00
                    ELSE
                        SUM(pos_details.total)
                END as total_spent
            ')
            ->where('customers.id', '!=', 1)
            ->groupBy('customers.id')
            ->orderByDesc('customers.created_at')
            ->get()
            ->toArray();
    }



}
