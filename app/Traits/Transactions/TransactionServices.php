<?php

namespace App\Traits\Transactions;

use Illuminate\Support\Facades\DB;

trait TransactionServices
{

    public function customerOrders()
    {
        DB::statement('SET sql_mode="" ');

        return DB::table('pos')
            ->selectRaw("
                pos.id,
                DATE_FORMAT(pos.created_at, '%M %d, %Y') as ordered_at,
                customers.name as customer,
                pos.status as payment_type,
                SUM(pos_details.quantity) as number_of_items
            ")
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->join('customers', 'customers.id', '=', 'pos.customer_id')
            ->whereNotIn('pos.status', ['Cancelled', 'Pending'])
            ->groupBy('pos.id')
            ->get()
            ->toArray();
    }


        /**
     * Undocumented function
     *
     * @param integer $invoiceId
     * @return void
     */
    public function invoices()
    {
        DB::statement("SET sql_mode = '' ");

        return DB::table('invoices')
            ->selectRaw("
                invoices.id AS id,
                invoices.status as status,
                DATE_FORMAT(invoices.created_at, '%M %d, %Y %h:%i %p') as invoiced_at,
                customers.name AS customer_name,
                FORMAT(SUM(invoice_details.quantity), 2) AS number_of_items,
                FORMAT(SUM(invoice_details.sub_total), 2) AS sub_total,
                FORMAT(SUM(invoice_details.discount), 2) AS discount,
                FORMAT(SUM(invoice_details.tax), 2) AS tax,
                FORMAT(SUM(invoice_details.total), 2) AS total,
                DATE_FORMAT(invoices.payment_date, '%M %d, %Y') AS payment_date
            ")
            ->join('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->join('products', 'products.id', '=', 'invoice_details.product_id')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->groupBy('invoices.id')
            ->get()
            ->toArray();
    }



    /**
     * Undocumented function
     *
     * @return array
     */
    public function purchaseOrders(): array
    {
        DB::statement('SET sql_mode = "" ');

        return DB::table('purchase_order')
            ->selectRaw("
                purchase_order.id as id,
                DATE_FORMAT(purchase_order.purchase_order_date, '%M %d %Y %H:%i %p') as purchase_date,
                purchase_order.status,
                suppliers.name as supplier,
                purchase_order.total_received_quantity as received,
                purchase_order.total_ordered_quantity as ordered,
                DATE_FORMAT(purchase_order.expected_delivery_date, '%M %d %Y %H:%i %p') as expected_on,
                FORMAT(SUM(purchase_order_details.amount), 2) as total
            ")
            ->join('purchase_order_details', 'purchase_order_details.purchase_order_id', '=', 'purchase_order.id')
            ->join('suppliers', 'suppliers.id', '=', 'purchase_order.supplier_id')
            ->groupBy('id')
            ->get()
            ->toArray();
    }


    /**
     * Undocumented function
     *
     * @return array
     */
    public function receivedStocks(): array
    {
        DB::statement('SET sql_mode="" ');

        return DB::table('received_stocks')
            ->selectRaw("
                    received_stock_details.id as id,
                    DATE_FORMAT(received_stocks.created_at, '%M %d, %Y %H:%i %p') as received_at,
                    received_stocks.purchase_order_id as po_id,
                    suppliers.name as supplier,
                    received_stock_details.received_quantity as received
            ")
            ->join('received_stock_details', 'received_stock_details.received_stock_id', '=', 'received_stocks.id')
            ->join('suppliers', 'suppliers.id', '=', 'received_stocks.supplier_id')
            ->get()
            ->toArray();
    }
}
