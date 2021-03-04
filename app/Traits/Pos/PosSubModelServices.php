<?php

namespace App\Traits\Pos;

use Illuminate\Support\Facades\DB;

trait PosSubModelServices
{
    /**
     *
     * @return array
     */
    public function getCustomers(): array
    {
        DB::statement('SET sql_mode = "" ');

        return DB::table('customers')
            ->selectRaw('
                customers.id as id,
                customers.name as customer
            ')
            ->get()
            ->toArray();
    }



    /**
     * Undocumented function
     *
     * @return array
     */
    public function getProducts($categoryId = 0, $productName = null): array
    {
        return DB::table('products')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            ->join('categories', 'categories.id', '=', 'products.category')
            ->selectRaw('
                stocks.supplier_id as supplier_id,
                stocks.bad_order_stock as bad_order_stock,
                stocks.stock_in as stock_in,
                stocks.stock_out as stock_out,
                stocks.incoming as incoming,
                stocks.default_purchase_costs,
                products.id,
                products.sku,
                products.barcode,
                products.name,
                products.image,
                categories.id as category_id,
                categories.name as category,
                products.price,
                products.sold_by,
                products.cost,
                (products.price - products.cost) * .100 as margin,
                stocks.in_stock,
                stocks.minimum_reorder_level as minimum_reorder_level
            '
            )
            ->when($categoryId, function ($q, $categoryId) {
                return $q->where('products.category', '=', $categoryId);
            })
            ->when($productName, function ($q, $productName) {
                return $q->where('products.name', 'like', "%$productName%");
            })
            ->where('is_for_sale', '=', true)
            ->where('stocks.in_stock', '>', 0)
            ->orderByDesc('products.created_at')
            ->get()
            ->toArray();
    }

    
}
