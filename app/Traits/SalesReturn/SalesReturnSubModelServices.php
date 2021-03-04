<?php

namespace App\Traits\SalesReturn;

use Illuminate\Support\Facades\DB;

trait SalesReturnSubModelServices
{
/**
     * Undocumented function
     *
     * @param integer $customerId
     * @return array
     */
    public function findCustomerOrderForSalesReturn(int $posId): array
    {
        $order = DB::table('pos')
            ->selectRaw('
                id,
                cashier,
                DATE_FORMAT(pos.created_at, "%M %d, %Y") as purchased_at
            ')
            ->where('id', '=', $posId)
            ->whereNotIn('pos.status', ['Cancelled', 'Pending'])
            ->first();

        $orderDetails = DB::table('pos_details')
            ->selectRaw('
                pos_details.id as id,
                pos_details.product_id as product_id,
                products.name as product_description,
                pos_details.quantity as ordered_quantity,
                products.price as price,
                pos_details.unit_of_measurement,
                pos_details.discount,
                pos_details.sub_total,
                pos_details.tax,
                pos_details.total
            ')
            ->join('products', 'products.id', '=', 'pos_details.product_id')
            ->leftJoin('sales_return_details', 'sales_return_details.pos_details_id', '=', 'pos_details.id')
            ->where('pos_details.pos_id', '=', $posId)
            ->whereRaw('sales_return_details.pos_details_id IS NULL')
            ->whereRaw('sales_return_details.product_id IS NULL')
            ->get()
            ->toArray();

        if ($order && count($orderDetails))
        {
            foreach ($orderDetails as $val)
            {
                $val->defect = '';
                $val->quantity = 0;
                $val->price = \number_format($val->price, 2);
                $val->discount = \number_format($val->discount, 2);
                $val->tax = \number_format($val->tax, 2);
                $val->sub_total = \number_format($val->sub_total, 2);
                $val->total = \number_format($val->total, 2);
            }

            return [
                'pos' => $order,
                'items' => $orderDetails
            ];
        }

        return [];
    }



    /**
     *
     * @return array
     */
    public function getCustomersWithOrders(): array
    {
        DB::statement('SET sql_mode = "" ');

        return DB::table('pos')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->leftJoin('sales_return_details', 'sales_return_details.pos_details_id', '=', 'pos_details.id')
            ->whereRaw('sales_return_details.pos_details_id IS NULL')
            ->whereRaw('sales_return_details.product_id IS NULL')
            ->selectRaw('
                pos.id as id,
                COUNT(pos_details.id) as number_of_orders
            ')
            ->whereNotIn('pos.status', ['Cancelled', 'Pending'])
            ->groupBy('pos.id')
            ->having('number_of_orders', '>', 0)
            ->get()
            ->toArray();
    }
}