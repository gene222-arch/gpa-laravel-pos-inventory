<?php

namespace App\Traits\Products;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait ProductServices
{

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getAll(): array
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
                stocks.in_stock,
                stocks.minimum_reorder_level as minimum_reorder_level,
                categories.id as category_id,
                categories.name as category,
                products.id,
                products.sku,
                products.barcode,
                products.name,
                products.image,
                products.price,
                products.sold_by,
                products.cost,
                (products.price - products.cost) * .100 as margin
            '
            )
            ->orderByDesc('products.created_at')
            ->get()
            ->toArray();
    }




    /**
     * Undocumented function
     *
     * @return array
     */
    public function getAllForPos($categoryId = 0, $productName = null): array
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


        /**
     * Undocumented function
     *
     */
    public function getProductForPurchaseOrder(int $productId)
    {
        $result = DB::table('products')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            ->selectRaw('
                products.id as id,
                products.id as product_id,
                products.name as product_description,
                stocks.in_stock as in_stock,
                stocks.incoming as incoming
            ')
            ->where('products.id', '=', $productId)
            ->first();

        $result->ordered_quantity = 1;
        $result->purchase_cost = 0.00;
        $result->amount = 0.00;

        return $result;
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
        return \boolval(Product::where('id', '=', $productId)->update($data));
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



    public function uploadImage($request): string
    {
        $path = '';

        if ($request->hasFile('product_image'))
        {
            $file = $request->product_image;

            $fileNameWithExt = $file->getClientOriginalName();
            $fileExt = $file->getClientOriginalExtension();

            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $fileNameToStore = $fileName . '_' . time() . '.' . $fileExt;

            $path = $file->storeAs('images/Products', $fileNameToStore, 'public');
        }
        else
        {
            $path = 'no_image.svg';
        }

        return Storage::disk('public')->url($path);
    }


}
