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
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function salesByItemReports(string $startDate = null, string $endDate = null): array
    {
        DB::statement("SET sql_mode = '' ");

        $monthlySales = DB::table('pos')
            ->selectRaw('
                products.id as id,
                categories.name as category,
                MONTH(pos.created_at) as month_num,
                products.name AS product_description,
                SUM(pos_details.quantity) AS items_sold,
                SUM(pos_details.sub_total + pos_details.tax) AS sales,
                SUM(pos_details.quantity * products.cost) AS cost_of_goods_sold,
                (SUM(pos_details.sub_total + pos_details.tax) - SUM(pos_details.quantity * products.cost)) AS gross_profit,
                SUM(pos_details.total) - 
                CASE
                    WHEN 
                        SUM(sales_return_details.total) IS NULL 
                    THEN 
                        0
                    ELSE 
                        SUM(sales_return_details.total)
                END as net_sales 
            ')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->leftJoin(
                'sales_return_details', 
                'sales_return_details.pos_details_id', '=', 'pos_details.id')
            ->join('products', 'products.id', '=', 'pos_details.product_id')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->when($startDate, function ($q, $startDate) {
                return $q->whereDate('pos_details.created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q, $endDate) {
                return $q->whereDate('pos_details.created_at', '<=', $endDate);
            })
            ->whereNotIn('pos.status', ['Cancelled', 'Pending'])
            ->groupBy('products.id')
            ->orderByDesc('net_sales');

            $monthlySales_ = $monthlySales->get()->toArray();
            $chartSales = $monthlySales->limit(5)->get()->toArray();
            $topFiveItems = $monthlySales->limit(5)->get()->toArray();

            $chartData = [0, 0, 0, 0, 0];
            $index = 0;

            foreach ($chartSales as $value)
            {
                $chartData[$index] = $value->net_sales;
                $index++;
            }

            return $monthlySales
            ? [
                'tableData' => $monthlySales_,
                'topFiveItems' => $topFiveItems,
                'chartData' => $chartData
            ]
            : [
                'tableData' => [],
                'topFiveItems' => [],
                'chartData' => []
            ];

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
        DB::statement("SET sql_mode = '' ");

        $result = DB::table('pos')
            ->selectRaw('
                categories.id as id,
                categories.name as category,
                SUM(pos_details.quantity) as items_sold,
                FORMAT(SUM(pos_details.sub_total + pos_details.tax), 2) as sales,
                FORMAT(SUM(pos_details.quantity * products.cost), 2) as cost_of_goods_sold,
                FORMAT(
                    SUM(pos_details.sub_total + pos_details.tax) -
                    SUM(pos_details.quantity * products.cost), 
                2) as gross_profit,
                FORMAT(SUM(pos_details.total) -
                CASE
                    WHEN 
                        pos.id = sales_returns.pos_id AND pos_details.product_id = sales_return_details.product_id
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
                END, 2) as net_sales
            ')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->leftJoin('sales_returns', 'sales_returns.pos_id', '=', 'pos.id')
            ->leftJoin('sales_return_details', 'sales_return_details.sales_return_id', '=', 'sales_returns.id')
            ->join('products', 'products.id', '=', 'pos_details.product_id')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->when($startDate, function($q, $startDate) {
                return $q->whereDate('pos_details.created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q, $endDate) {
                return $q->whereDate('pos_details.created_at', '<=', $endDate);
            })
            ->whereNotIn('pos.status', ['Cancelled', 'Pending'])
            ->groupBy('categories.id')
            ->orderByDesc('net_sales')
            ->get()
            ->toArray();

        return $result ? $result : [];
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
        DB::statement("SET sql_mode = '' ");

        $result = DB::table('pos')
            ->selectRaw('
                pos.id as id,
                pos.status as payment_type,
                FORMAT(SUM(pos_details.sub_total + pos_details.tax), 2) as gross_sales, 
                CASE
                    WHEN 
                        SUM(sales_return_details.discount) IS NULL 
                    THEN 
                        0
                    ELSE 
                        FORMAT(SUM(sales_return_details.discount), 2)
                END as sales_return,
                FORMAT(SUM(pos_details.discount) - 
                CASE
                    WHEN 
                        SUM(sales_return_details.discount) IS NULL 
                    THEN 
                        0
                    ELSE 
                        SUM(sales_return_details.discount)
                END, 2) as discounts,
                CASE
                    WHEN 
                        SUM(sales_return_details.total) IS NULL 
                    THEN 
                        0
                    ELSE 
                        FORMAT(SUM(sales_return_details.total), 2)
                END as sales_return,
                FORMAT(SUM(pos_details.total) - 
                CASE
                    WHEN 
                        SUM(sales_return_details.total) IS NULL 
                    THEN 
                        0
                    ELSE 
                        SUM(sales_return_details.total)
                END, 2) as net_sales 
            ')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->leftJoin('sales_returns', 'sales_returns.pos_id', '=', 'pos.id')
            ->leftJoin('sales_return_details', 'sales_return_details.sales_return_id', '=', 'sales_returns.id')
            ->when($startDate, function($q, $startDate) {
                return $q->whereDate('pos_details.created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q, $endDate) {
                return $q->whereDate('pos_details.created_at', '<=', $endDate);
            })
            ->whereNotIn('pos.status', ['Cancelled', 'Pending'])
            ->groupBy('pos.status')
            ->orderByDesc('net_sales')
            ->get()
            ->toArray();

            return $result ? $result : [];
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
        DB::statement("SET sql_mode = '' ");

        $result = DB::table('pos')
            ->selectRaw('
                users.id as id,
                pos.cashier as cashier,
                FORMAT(SUM(pos_details.sub_total + pos_details.tax), 2) as gross_sales, 
                FORMAT(SUM(pos_details.discount) - 
                CASE
                    WHEN 
                        SUM(sales_return_details.discount) IS NULL 
                    THEN 
                        0
                    ELSE 
                        SUM(sales_return_details.discount)
                END, 2) as discounts,
                CASE
                    WHEN 
                        SUM(sales_return_details.total) IS NULL 
                    THEN 
                        0
                    ELSE 
                        FORMAT(SUM(sales_return_details.total), 2)
                END as sales_return,
                FORMAT(SUM(pos_details.total) - 
                CASE
                    WHEN 
                        SUM(sales_return_details.total) IS NULL 
                    THEN 
                        0
                    ELSE 
                        SUM(sales_return_details.total)
                END, 2) as net_sales 
            ')
            ->join('users', 'users.name', '=', 'pos.cashier')
            ->join('pos_details', 'pos_details.pos_id', '=', 'pos.id')
            ->leftJoin('sales_returns', 'sales_returns.pos_id', '=', 'pos.id')
            ->leftJoin('sales_return_details', 'sales_return_details.sales_return_id', '=', 'sales_returns.id')
            ->when($startDate, function($q, $startDate) {
                return $q->whereDate('pos_details.created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q, $endDate) {
                return $q->whereDate('pos_details.created_at', '<=', $endDate);
            })
            ->whereNotIn('pos.status', ['Cancelled', 'Pending'])
            ->groupBy('pos.cashier')
            ->orderByDesc('net_sales')
            ->get()
            ->toArray();

        return $result ? $result : [];
    }


}
