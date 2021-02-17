<?php

namespace App\Traits\Reports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait ReportsSummaryServices
{


    /**
     * Undocumented function
     *
     */
    public function salesSummary()
    {
        DB::statement('SET sql_mode="" ');

        return DB::table('users')
        ->selectRaw('
            (
                SELECT
                    SUM(pos_details.sub_total + pos_details.tax)
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos_details.pos_id = pos.id
                WHERE
                    pos.status
                NOT IN ("Cancelled", "Processing")
            )
            AS gross_sales,
            (
                SELECT
                    CASE
                        WHEN
                            SUM(sales_return_details.total) != NULL
                        THEN
                            SUM(sales_return_details.total)
                        ELSE
                            0.00
                    END
                FROM
                    sales_return_details
            )
            AS sales_return,
            (
                SELECT
                    SUM(bad_order_details.amount)
                FROM
                    `bad_order_details`
            )
            AS purchase_return,
            (
                SELECT
                    SUM(pos_details.discount)
                FROM
                    pos
                INNER JOIN
                    pos_details
                ON
                    pos_details.pos_id = pos.id
                WHERE
                    pos.status
                NOT IN("Cancelled", "Processing")
            )
            AS discounts,


            (
                SELECT
                    SUM(pos_details.quantity * products.cost)
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
                NOT IN ("Cancelled", "Processing")
            )
            AS total_cost_of_goods_sold,
            (
                SELECT
                    ROUND(
                        (
                            SUM(pos_details.sub_total + pos_details.tax) -
                            SUM(pos_details.quantity * products.cost)
                        )
                    / SUM(pos_details.sub_total + pos_details.tax) * 100
                    ,2)
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
                NOT IN ("Cancelled", "Processing")
            )
            AS margin_percentage,
            (
                SELECT
                    ROUND((
                        SUM(pos_details.sub_total + pos_details.tax) -
                        SUM(pos_details.quantity * products.cost))
                    , 2)
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
                NOT IN ("Cancelled", "Processing")

            )
            AS margin_sales

        ')
        ->first();

    }



    /**
     * Undocumented function
     *
     * @param string $year
     * @return array
     */
    public function topFiveSalesByItem(string $year = null, int $monthNumber = null): array
    {
        DB::statement("SET sql_mode = '' ");

        return DB::table('pos')
            ->selectRaw('
                products.name as product_description,
                SUM(pos_details.sub_total + pos_details.tax) as sales
            ')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->join('products', 'products.id', '=', 'pos_details.product_id')
            ->whereNotIn('pos.status', ['Cancelled', 'Processing'])
            ->when($year, function ($q, $year) {
                return $q->whereYear('pos_details.created_at', '=', $year);
            })
            ->when($monthNumber, function ($q, $monthNumber) {
                return $q->whereMonth('pos_details.created_at', '=', $monthNumber);
            })
            ->groupBy('products.id')
            ->orderByDesc('sales')
            ->limit(5)
            ->get()
            ->toArray();
    }


    /**
     * Undocumented function
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function salesByItemReports(string $startDate = null, string $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->toDateString();

        DB::statement("SET sql_mode = '' ");

        return DB::table('pos')
            ->selectRaw('
                products.name AS product_description,
                SUM(pos_details.quantity) AS items_sold,
                SUM(pos_details.sub_total + pos_details.tax) AS sales,
                SUM(pos_details.quantity * products.cost) AS cost_of_goods_sold,
                (SUM(pos_details.sub_total + pos_details.tax) -
                SUM(pos_details.quantity * products.cost)) AS gross_profit,
                SUM(pos_details.total) -
                CASE
                    WHEN pos.id = sales_returns.pos_id AND pos_details.product_id = sales_return_details.product_id
                    THEN
                        (
                            SELECT
                                SUM(sales_return_details.total)
                            FROM
                                sales_return_details
                            WHERE
                                sales_return_details.sales_return_id = sales_returns.id
                        )
                    ELSE 0
                END AS net_sales
            ')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->leftJoin('sales_returns', 'sales_returns.pos_id', '=', 'pos.id')
            ->leftJoin('sales_return_details', 'sales_return_details.product_id', '=', 'pos_details.product_id')
            ->join('products', 'products.id', '=', 'pos_details.product_id')
            ->whereDate('pos_details.created_at', '>=', $startDate)
            ->when($endDate, function ($q, $endDate) {
                return $q->whereDate('pos_details.created_at', '<=', $endDate);
            })
            ->whereNotIn('pos.status', ['Cancelled', 'Processing'])
            ->groupBy('products.id')
            ->orderByDesc('net_sales')
            ->get()
            ->toArray();
    }



    /**
     * Undocumented function
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function salesByCategory(string $startDate = null, string $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->toDateString();

        DB::statement("SET sql_mode = '' ");

        return DB::table('pos')
            ->selectRaw('
                categories.id as id,
                products.category as category,
                SUM(pos_details.quantity) as items_sold,
                SUM(pos_details.sub_total + pos_details.tax) as sales,
                SUM(pos_details.quantity * products.cost) as cost_of_goods_sold,
                SUM(pos_details.sub_total + pos_details.tax) -
                SUM(pos_details.quantity * products.cost) as gross_profit,
                SUM(pos_details.total) -
                CASE
                    WHEN pos.id = sales_returns.pos_id AND pos_details.product_id = sales_return_details.product_id
                    THEN
                        (
                            SELECT
                                SUM(sales_return_details.total)
                            FROM
                                sales_return_details
                            WHERE
                                sales_return_details.sales_return_id = sales_returns.id
                        )
                    ELSE 0
                END as net_sales
            ')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->leftJoin('sales_returns', 'sales_returns.pos_id', '=', 'pos.id')
            ->leftJoin('sales_return_details', 'sales_return_details.product_id', '=', 'pos_details.product_id')
            ->join('products', 'products.id', '=', 'pos_details.product_id')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->whereDate('pos_details.created_at', '>=', $startDate)
            ->when($endDate, function ($q, $endDate) {
                return $q->whereDate('pos_details.created_at', '<=', $endDate);
            })
            ->whereNotIn('pos.status', ['Cancelled', 'Processing'])
            ->groupBy('categories.id')
            ->orderByDesc('net_sales')
            ->get()
            ->toArray();
    }



    /**
     * Undocumented function
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function salesByPamentType(string $startDate = null, string $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->toDateString();

        DB::statement("SET sql_mode = '' ");

        return DB::table('pos')
            ->selectRaw('
                pos.status as payment_type,
                SUM(pos_details.discount) as discount,
                SUM(pos_details.sub_total + pos_details.tax) as gross_sales,
                CASE
                    WHEN pos.id = sales_returns.pos_id
                    THEN
                        (
                            SELECT
                                SUM(sales_return_details.total)
                            FROM
                                sales_return_details
                            WHERE
                                sales_return_details.sales_return_id = sales_returns.id
                        )
                    ELSE 0
                END as sales_return,
                SUM(pos_details.total) -
                CASE
                    WHEN pos.id = sales_returns.pos_id
                    THEN
                        (
                            SELECT
                                SUM(sales_return_details.total)
                            FROM
                                sales_return_details
                            WHERE
                                sales_return_details.sales_return_id = sales_returns.id
                        )
                    ELSE 0
                END as net_sales
            ')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->leftJoin('sales_returns', 'sales_returns.pos_id', '=', 'pos.id')
            ->leftJoin('sales_return_details', 'sales_return_details.product_id', '=', 'pos_details.product_id')
            ->whereDate('pos_details.created_at', '>=', $startDate)
            ->when($endDate, function ($q, $endDate) {
                return $q->whereDate('pos_details.created_at', '<=', $endDate);
            })
            ->whereNotIn('pos.status', ['Cancelled', 'Processing'])
            ->groupBy('pos.status')
            ->orderByDesc('net_sales')
            ->get()
            ->toArray();
    }



    /**
     * Undocumented function
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function salesByEmployee(string $startDate = null, string $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->toDateString();

        DB::statement("SET sql_mode = '' ");

        return DB::table('pos')
            ->selectRaw('
                pos.cashier,
                SUM(pos_details.discount) as discount,
                SUM(pos_details.sub_total + pos_details.tax) as gross_sales,
                CASE
                    WHEN pos.id = sales_returns.pos_id
                    THEN
                        (
                            SELECT
                                SUM(sales_return_details.total)
                            FROM
                                sales_return_details
                            WHERE
                                sales_return_details.sales_return_id = sales_returns.id
                        )
                    ELSE 0
                END as sales_return,
                SUM(pos_details.total) -
                CASE
                    WHEN pos.id = sales_returns.pos_id
                    THEN
                        (
                            SELECT
                                SUM(sales_return_details.total)
                            FROM
                                sales_return_details
                            WHERE
                                sales_return_details.sales_return_id = sales_returns.id
                        )
                    ELSE 0
                END as net_sales
            ')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->leftJoin('sales_returns', 'sales_returns.pos_id', '=', 'pos.id')
            ->leftJoin('sales_return_details', 'sales_return_details.product_id', '=', 'pos_details.product_id')
            ->whereDate('pos_details.created_at', '>=', $startDate)
            ->when($endDate, function ($q, $endDate) {
                return $q->whereDate('pos_details.created_at', '<=', $endDate);
            })
            ->whereNotIn('pos.status', ['Cancelled', 'Processing'])
            ->groupBy('pos.cashier')
            ->orderByDesc('net_sales')
            ->get()
            ->toArray();
    }


}
