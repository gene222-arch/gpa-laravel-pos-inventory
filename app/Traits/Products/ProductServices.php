<?php

namespace App\Traits\Products;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

trait ProductServices
{

    /**
     * Undocumented function
     *
     * @return array
     */
    public function loadProductsWithStocks(): array
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
                categories.name as category,
                products.price,
                products.sold_by,
                products.cost,
                (products.price / products.cost) * 100 as margin,
                stocks.in_stock,
                stocks.minimum_reorder_level as minimum_reorder_level
            '
            )
            ->orderByDesc('products.created_at')
            ->get()
            ->toArray();
    }


    /**
     * Eager load stocks
     *
     * @return Collection
     */
    public function loadStocks()
    {
        return $this->load('stock');
    }


    /**
     * Undocumented function
     *
     * @param integer $productId
     * @param string $productBarcode
     * @return Object|null
     */
    public function getProduct(int $productId = NULL, string $productBarcode = NULL): Object|NULL
    {
        $product = Product::query();

        $product->when($productId, fn($q) => $q->where('id', '=', $productId));
        $product->when($productBarcode, fn($q) => $q->where('barcode', '=', $productBarcode));

        return $product = $product->first();
    }


    /**
     * Insert a record in `products` table
     * return ['product_id']
     *
     * @param array $data
     * @return integer
     */
    public function createProduct(array $data): int
    {
        return Product::create($data)->id;
    }


    /**
     * Update a record in `products` table
     *
     * @param integer $productId
     * @param array $data
     * @return boolean
     */
    public function updateProduct(int $productId, array $data): bool
    {
        return \boolval(Product::where('id', '=', $productId)
                                    ->update($data)
        );
    }

    /**
     * Delete multiple records in the products table
     *
     * @param array $productIds
     * @return boolean
     */
    public function deleteMany(array $productIds): bool
    {
        return Product::whereIn('id', $productIds)->delete();
    }

}
