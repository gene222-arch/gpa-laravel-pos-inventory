<?php

namespace App\Traits\Payment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait ReceiptServices
{

    public function getSales (string $date = NULL) 
    {
        DB::statement('SET sql_mode="" ');

        return DB::table('sales')
            ->join('sales_details', 'sales_details.sales_id', '=', 'sales.id')
            ->selectRaw('
                sales.id as id,
                sales.payment_type as payment_type,
                SUM(sales_details.total) as total,
                DATE_FORMAT(sales.created_at, "%h:%i %p") as paid_at
            ')
            ->when($date, function($q) use ($date) {
                return $q->whereDate('sales.created_at', '=', $date);
            })
            ->groupBy('sales.id')
            ->orderByDesc('sales.created_at')
            ->when(Auth::user()->can('View All Receipts') === false, function ($q) {
                return $q->limit(5);
            })
            ->get()
            ->toArray();
    }


    public function getSalesDetails (int $receiptId)
    {
        DB::statement('SET sql_mode="" ');

        $salesInfo = DB::table('sales')
            ->join('sales_details', 'sales_details.sales_id', '=', 'sales.id')
            ->leftJoin('customers', 'customers.id', '=', 'sales.customer_id')
            ->leftJoin('users', 'users.id', '=', 'sales.cashier_id')
            ->selectRaw('
                sales.id as id,
                customers.name as customer,
                users.name as cashier,
                DATE_FORMAT(sales.created_at, "%M %d, %Y") as paid_at,
                FORMAT(SUM(sales_details.sub_total), 2) as sub_total,
                FORMAT(SUM(sales_details.discount), 2) as total_discount,
                FORMAT(SUM(sales_details.tax), 2) total_tax,
                FORMAT(SUM(sales_details.total), 2) as total
            ')
            ->where('sales.id', '=', $receiptId)
            ->groupBy('id')
            ->first();

        $salesDetails = DB::table('sales_details')
            ->join('products', 'products.id', '=', 'sales_details.product_id')
            ->selectRaw('
                sales_details.id as id,
                products.name as product_description,
                sales_details.quantity as quantity,
                sales_details.price as price,
                sales_details.discount as discount,
                sales_details.sub_total as amount
            ')
            ->where('sales_details.sales_id', '=', $receiptId)
            ->get()
            ->toArray();

        return !($salesInfo && $salesDetails)
            ? [
                'salesInfo' => [],
                'salesDetails' => []
            ]
            : [
                'salesInfo' => $salesInfo,
                'salesDetails' => $salesDetails
            ];
    }
}
