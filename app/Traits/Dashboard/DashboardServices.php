<?php

namespace App\Traits\Dashboard;

use Illuminate\Support\Facades\DB;

trait DashboardServices
{

    /**
     * Undocumented function
     *
     */
    public function getSalesSummary()
    {
        DB::statement('SET sql_mode="" ');

        $result = DB::table('users')
        ->selectRaw('
            (
                SELECT
                    CASE
                        WHEN
                            SUM(pos_details.sub_total + pos_details.tax) IS NULL
                        THEN
                            0.00
                        ELSE
                            SUM(pos_details.sub_total + pos_details.tax)
                    END
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos_details.pos_id = pos.id
                WHERE
                    pos.status
                NOT IN ("Cancelled", "Pending")
            )
            AS gross_sales,
            (
                SELECT
                    CASE
                        WHEN
                            SUM(pos_details.sub_total + pos_details.tax) IS NULL
                        THEN
                            0.00
                        ELSE
                            SUM(pos_details.sub_total + pos_details.tax)
                    END
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos_details.pos_id = pos.id
                WHERE
                    pos.status
                NOT IN ("Cancelled", "Pending")
            ) - (
                SELECT
                    CASE
                        WHEN
                            SUM(pos_details.quantity * products.cost) IS NULL
                        THEN
                            0.00
                        ELSE
                            SUM(pos_details.quantity * products.cost)
                    END
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos.id = pos_details.pos_id
                INNER JOIN
                    products
                ON
                    products.id = pos_details.product_id
                WHERE
                    pos.status
                NOT IN ("Cancelled", "Pending")
            ) as gross_profit,

            (
                SELECT
                    CASE
                        WHEN
                            SUM(sales_return_details.total) IS NULL
                        THEN
                            0.00
                        ELSE
                            SUM(sales_return_details.total)
                    END
                FROM
                    sales_return_details
            )
            AS sales_return,
            (
                SELECT
                    CASE
                        WHEN
                            SUM(bad_order_details.amount) IS NULL
                        THEN
                            0.00
                        ELSE
                            SUM(bad_order_details.amount)
                    END
                FROM
                    `bad_order_details`
            )
            AS purchase_return,
            (
                SELECT
                    CASE
                        WHEN
                            SUM(pos_details.discount) IS NULL
                        THEN
                            0.00
                        ELSE
                            SUM(pos_details.discount)
                    END
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos_details.pos_id = pos.id
                WHERE
                    pos.status
                NOT IN("Cancelled", "Pending")
            )
            AS discounts,
            (
                SELECT
                    CASE
                        WHEN
                            SUM(pos_details.quantity * products.cost) IS NULL
                        THEN
                            0.00
                        ELSE
                            SUM(pos_details.quantity * products.cost)
                    END
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos.id = pos_details.pos_id
                INNER JOIN
                    products
                ON
                    products.id = pos_details.product_id
                WHERE
                    pos.status
                NOT IN ("Cancelled", "Pending")
            )
            AS total_cost_of_goods_sold,
            (
                SELECT
                    CASE
                        WHEN
                            ROUND(
                                (
                                    SUM(pos_details.sub_total + pos_details.tax) -
                                    SUM(pos_details.quantity * products.cost)
                                )
                            / SUM(pos_details.sub_total + pos_details.tax) * 100
                            ,2) IS NULL
                        THEN
                            0.00
                        ELSE
                        ROUND(
                            (
                                SUM(pos_details.sub_total + pos_details.tax) -
                                SUM(pos_details.quantity * products.cost)
                            )
                        / SUM(pos_details.sub_total + pos_details.tax) * 100
                        ,2)
                    END
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos.id = pos_details.pos_id
                INNER JOIN
                    products
                ON
                    products.id = pos_details.product_id
                WHERE
                    pos.status
                NOT IN ("Cancelled", "Pending")
            )
            AS margin_percentage,
            (
                SELECT
                    CASE
                        WHEN
                            ROUND((
                                SUM(pos_details.sub_total + pos_details.tax) -
                                SUM(pos_details.quantity * products.cost))
                            , 2) IS NULL
                        THEN
                            0.00
                        ELSE
                            ROUND((
                                SUM(pos_details.sub_total + pos_details.tax) -
                                SUM(pos_details.quantity * products.cost))
                            , 2)
                        END
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos.id = pos_details.pos_id
                INNER JOIN
                    products
                ON
                    products.id = pos_details.product_id
                WHERE
                    pos.status
                NOT IN ("Cancelled", "Pending")

            )
            AS margin_sales,
            ((
                SELECT
                    CASE
                        WHEN
                            SUM(pos_details.total) IS NULL
                        THEN
                            0.00
                        ELSE
                            SUM(pos_details.total)
                    END
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos_details.pos_id = pos.id
                WHERE
                    pos.status
                NOT IN ("Cancelled", "Pending")
            ) -
            (
                SELECT
                    CASE
                        WHEN
                            SUM(sales_return_details.total) IS NULL
                        THEN
                            0.00
                        ELSE
                            SUM(sales_return_details.total)
                    END
                FROM
                sales_return_details
            )) as net_sales,
            (
                SELECT 	
                    COUNT(id)
                FROM 
                    purchase_order
                WHERE 
                    status 
                NOT IN ("Cancelled", "Closed") 
            ) as pending_purchase_orders,
            (
                SELECT 	
                    COUNT(id)
                FROM 
                    invoices
                WHERE 
                    status != "Paid"
            ) as pending_invoices,
            (
                SELECT 	
                    COUNT(id)
                FROM 
                    customers
            ) as total_no_of_customers

        ')
        ->first();

        if (!$result)
        {
            return [];
        }

        return $result;
    }

    public function getMonthlySales(int $year = null): array
    {
        DB::statement('SET sql_mode="" ');

        $result = DB::table('pos')
            ->selectRaw('
                    MONTH(pos.created_at) as month_num,
                    SUM(pos_details.sub_total + pos_details.tax) as sales
            ')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->whereNotIn('pos.status', ['Pending', 'Cancelled'])
            ->whereYear('pos.created_at', date('Y'))
            ->when(!is_null($year), function ($q, $year) {
                return $q->whereYear('pos.created_at', $year);
            })
            ->groupByRaw('MONTH(pos.created_at)')
            ->get()
            ->toArray();

            $data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            if (!$result)
            {
                return [];
            }

            foreach ($result as $value)
            {
                $data[$value->month_num - 1] = $value->sales;
            }

            return $data;
    }



    public function getPendingInvoices(): array
    {
        $result = DB::table('invoices')
            ->selectRaw('
                customers.name as name,
                DATE_FORMAT(invoices.created_at, "%M %d %Y") as invoice_date
            ')
            ->join('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->where('invoices.status', '!=', 'Paid')
            ->where('customers.name', '!=', 'walk-in')
            ->limit(10)
            ->groupBy('invoices.id')
            ->get()
            ->toArray();

        if (!$result)
        {
            return [];
        }

        return $result;
    }


    public function getInProcessPurchaseOrders(): array
    {
        $result = DB::table('purchase_order')
            ->selectRaw('
                DATE_FORMAT(purchase_order.purchase_order_date, "%M %d, %Y") as po_date,
                suppliers.name as supplier
            ')
            ->join('purchase_order_details', 'purchase_order_details.purchase_order_id', '=', 'purchase_order.id')
            ->join('suppliers', 'suppliers.id', '=', 'purchase_order.supplier_id')
            ->whereNotIn('purchase_order.status', ['Cancelled', 'Closed'])
            ->limit(10)
            ->get()
            ->toArray();


        if (!$result)
        {
            return [];
        }

        return $result;
    }



    public function getNotifications()
    {
        
    }

}
