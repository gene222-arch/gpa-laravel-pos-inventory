<?php

namespace App\Traits\Payment;

use App\Models\PosPayment;
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
        $salesInfo = DB::table('sales')
            ->join('sales_details', 'sales_details.sales_id', '=', 'sales.id')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->join('users', 'users.id', '=', 'sales.cashier_id')
            ->selectRaw('
                sales.id as id,
                customers.name as customer,
                users.name as cashier,
                DATE_FORMAT(sales.created_at, "%M %d, %Y") as paid_at
            ')
            ->first();

        $salesDetails = DB::table('sales_details')
            ->join('products', 'products.id', '=', 'sales_details.product_id')
            ->selectRaw('
                sales_details.id as id,
                products.name as product_description,
                sales_details.quantity as quantity,
                sales_details.price as price,
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
