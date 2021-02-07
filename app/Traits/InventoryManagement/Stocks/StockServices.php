<?php

namespace App\Traits\InventoryManagement\Stocks;

use App\Models\User;
use App\Models\Stock;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Jobs\QueueLowStockNotification;
use App\Traits\InventoryManagement\Stocks\StocksHelper;
use App\Traits\PDF\PDFGeneratorServices;

trait StockServices
{

    use StocksHelper, PDFGeneratorServices;


    /**
     * Undocumented function
     *
     * @param integer $productId
     * @return mixed
     */
    public function getRemainingStockOf(int $productId): mixed
    {
        $remainingStock = Stock::where('product_id', '=', $productId)
                        ->first()
                        ->in_stock;

        return (!$remainingStock)
            ? throw new \Exception("Product does not exist")
            : $remainingStock;
    }


    /**
     * Insert a record in `stocks` table
     *
     * @param integer $productId
     * @param array $data
     * @return boolean
     */
    public function createStock(int $productId, array $data): bool
    {
        $data = array_merge([
            'product_id' => $productId
        ], $data);

        return \boolval(Stock::create($data)->id);
    }


    /**
     * Update a record in `stocks` table
     *
     * @param \App\Models\Product $product
     * @param array $data
     * @return boolean
     */
    public function updateStock(int $productId, array $data): bool
    {
        $product = Product::find($productId);

        return \boolval($product->stock()->update($data));
    }



    /**
     * Undocumented function
     *
     * @param integer $productId
     * @param integer $incomingQuantity
     * @return boolean
     */
    public function incrementInStock(int $productId, int $incomingQuantity): bool
    {
        return \boolval(Stock::where('product_id', '=', $productId)
                                    ->update([
                                        'in_stock' => DB::raw('in_stock + ' . $incomingQuantity),
                                    ])
        );
    }


    /**
     * Update record of ['stock_in'] from stocks table
     * after purchase of order
     *
     * @param array $stockDetails
     * @return boolean
     */
    public function stockIn(array $stockDetails): bool
    {
        $data = [];

        foreach ($stockDetails as $stockDetail)
        {
            $data[] = [
                'product_id' => $stockDetail['product_id'],
                'stock_in' => $stockDetail['received_quantity']
            ];
        }

        $uniqueBy = 'product_id';

        $update = [
            'in_stock' => DB::raw('stocks.in_stock + stocks.stock_in + values(stock_in)'),
            'stock_in' => DB::raw('stocks.stock_in + values(stock_in)'),
            'incoming' => DB::raw('stocks.incoming - values(stock_in)')
        ];

        return DB::table('stocks')
                    ->upsert($data,
                        $uniqueBy,
                        $update);
    }


    /**
     * Undocumented function
     *
     * @param array $badOrderDetails
     * @return boolean
     */
    public function updateBadOrderQtyOf(array $badOrderDetails): bool
    {
        $data = [];

        foreach ($badOrderDetails as $badOrderDetail)
        {
            $data[] = [
                'product_id' => $badOrderDetail['product_id'],
                'bad_order_stock' => $badOrderDetail['quantity']
            ];
        }

        $uniqueBy = [
            'product_id'
        ];

        $update = [
            'bad_order_stock' => DB::raw('stocks.bad_order_stock + values(bad_order_stock)'),
            'in_stock' => DB::raw('stocks.in_stock - values(bad_order_stock)'),
            'stock_in' => DB::raw('stocks.stock_in - values(bad_order_stock)'),
            'stock_out' => DB::raw('stocks.stock_out + values(bad_order_stock)')
        ];

        return \boolval(DB::table('stocks')->upsert($data, $uniqueBy, $update));
    }


    /**
     * Undocumented function
     *
     * @param integer $productId
     * @param integer $outgoingQuantity
     * @return boolean
     */
    public function decrementInStock(int $productId, int $outgoingQuantity): bool
    {
        return \boolval(Stock::where('product_id', '=', $productId)
                                    ->update([
                                        'in_stock' => DB::raw('in_stock - ' . $outgoingQuantity),
                                    ])
        );
    }



    /**
     * Update a record of ['in_stock', 'stock_out'] fields from stocks table
     * after purchase of order
     *
     * @param integer $productId
     * @param integer $outgoingQuantity
     * @return boolean
     */
    public function stockOut(int $productId, int $outgoingQuantity): bool
    {
        return \boolval(Stock::where('product_id', '=', $productId)
                                    ->update([
                                        'in_stock' => DB::raw('in_stock - ' . $outgoingQuantity),
                                        'stock_out' => DB::raw('stock_out + ' . $outgoingQuantity),
                                    ])
        );
    }



    /**
     * Update multiple records of ['in_stock', 'stock_out'] fields from stocks table
     *
     * @param Collection $stockDetails
     * @return boolean
     */
    public function stockOutMany($stockDetails): bool
    {
        $data = [];

        foreach ($stockDetails as $stockDetail)
        {
            $data[] = [
                'product_id' => $stockDetail['product_id'],
                'in_stock' => $stockDetail['quantity'],
                'stock_out' => $stockDetail['quantity'],
            ];
        }

        $uniqueBy = [
            'product_id'
        ];

        $update = [
            'in_stock' => DB::raw('stocks.in_stock - values(in_stock)'),
            'stock_out' => DB::raw('stocks.stock_out + values(stock_out)')
        ];

        $result = \boolval(DB::table('stocks')->upsert($data, $uniqueBy, $update));

        $productIds = \prepareGetKeyInMultiArray('product_id', $stockDetails);

        $this->mailOnLowStock($productIds);

        return $result;
    }



    /**
     * * Update record ['incoming'] field from stocks table
     * * after purchase of order
     *
     * @param array $productId
     * @return boolean
     */
    public function updateIncomingStocksOf(array $productIds): bool
    {
        try {
            DB::transaction(function () use($productIds)
            {
                $incomingStocks = $this->prepareGetProductTotalIncomingStocks($productIds);

                $uniqueBy = ['product_id'];

                $update = [
                    'incoming' => DB::raw('stocks.incoming + values(incoming)')
                ];

                #update stocks
                DB::table('stocks')
                    ->upsert($incomingStocks,
                    $uniqueBy,
                    $update);
            });

        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }



    /**
     * * Update a records in ['products'] by receiving all stocks per product via ['product_id']
     * * Update stocks table fields ['incoming', 'in_stock', 'stock_in']
     *
     * @param integer $purchaseOrderId
     * @param array $productIds
     * @return boolean
     */
    public function receiveAllProductStocksOf(int $purchaseOrderId, array $productIds): bool
    {
        try {
            DB::transaction(function () use($purchaseOrderId, $productIds)
            {
                # get the remaining quantity of a product via ['product_id']
                $data = (new PurchaseOrder)->prepareGetPODRemainingQty(
                    $purchaseOrderId,
                    $productIds
                );

                $uniqueBy = 'product_id';

                $update = [
                    'incoming' => DB::raw('stocks.incoming - values(stock_in)'),
                    'in_stock' => DB::raw('stocks.in_stock + values(stock_in)'),
                    'stock_in' => DB::raw('stocks.stock_in + values(stock_in)')
                ];

                DB::table('stocks')
                    ->upsert($data,
                        $uniqueBy,
                        $update);

            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }



    /**
     * Update record ['incoming'] field from stocks table
     * after cancellation of purchase of order
     *
     * @param integer $productId
     * @param integer $outgoingQuantity
     * @return boolean
     */
    public function outgoingStock(int $productId, int $outgoingQuantity): bool
    {
        return \boolval(Stock::where('product_id', '=', $productId)
                                ->update([
                                    'incoming' => DB::raw('incoming - ' . $outgoingQuantity)
                                ])
        );
    }



    /**
     * Undocumented function
     *
     * @param array $productId
     * @return void
     */
    public function mailOnLowStock(array $productIds)
    {
        $products = Product::with('stock')->whereHas('stock', fn ($q) => $q->whereIn('product_id', $productIds))->get();

        $products = $products->filter(fn ($p) => $p->stock->minimum_reorder_level >= $p->stock->in_stock);

        if ($products)
        {
            $fileName = 'low-stock-' . now()->toDateString() . '-' . time() . '.pdf';

            $this->generateLowStockPDF($products, $fileName);

            dispatch(new QueueLowStockNotification(
                    User::find(auth()->user()->id),
                    $fileName
            ))
            ->delay(now()->addSeconds(10));
        }
    }


}
