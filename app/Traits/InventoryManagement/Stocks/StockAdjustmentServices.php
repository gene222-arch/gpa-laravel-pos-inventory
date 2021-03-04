<?php

namespace App\Traits\InventoryManagement\Stocks;

use App\Models\StockAdjustment;
use Illuminate\Support\Facades\DB;

trait StockAdjustmentServices
{

    use StockServices;

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getStockAdjustments()
    {
        DB::statement('SET sql_mode="" ');

        return DB::table('stock_adjustments')
            ->selectRaw("
                stock_adjustments.id as id,
                DATE_FORMAT(stock_adjustments.created_at, '%M %d %Y') AS adjusted_at,
                stock_adjustments.reason as reason,
                SUM(
                    stock_adjustment_details.added_stock +
                    stock_adjustment_details.counted_stock +
                    stock_adjustment_details.removed_stock
                ) AS quantity
            ")
            ->join('stock_adjustment_details', 'stock_adjustment_details.stock_adjustment_id', '=', 'stock_adjustments.id')
            ->groupBy('stock_adjustments.id')
            ->orderBy('stock_adjustments.created_at', 'desc')
            ->get()
            ->toArray();
    }



    public function getStockToAdjust(int $productId)
    {
        $result = DB::table('stocks')
            ->selectRaw('
                stocks.id as id,
                stocks.id as stock_id,
                stocks.product_id as product_id,
                products.name as product_description,
                stocks.in_stock
            ')
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->where('products.id', '=', $productId)
            ->first();

        $result->added_stock = 0;
        $result->removed_stock = 0;
        $result->counted_stock = 0;
        $result->stock_after = 0;

        return $result;
    }


    /**
     * Undocumented function
     *
     * @param integer $stockAdjustmentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStockAdjustment(int $stockAdjustmentId)
    {
        $stockAdjustment = DB::table('stock_adjustments')
            ->selectRaw('
                id,
                reason,
                adjusted_by,
                DATE_FORMAT(created_at, "%M %d, %Y") as adjusted_at
            ')
            ->where('id', '=', $stockAdjustmentId)
            ->first();

        DB::statement('SET sql_mode="" ');

        $stockAdjustmentDetails = DB::table('stock_adjustment_details')
            ->selectRaw('
                stock_adjustment_details.id as id,
                products.name as product_description,
                SUM(stock_adjustment_details.added_stock + stock_adjustment_details.counted_stock + stock_adjustment_details.removed_stock) as quantity,
                stock_adjustment_details.added_stock as added_stock,
                stock_adjustment_details.counted_stock as counted_stock,
                stock_adjustment_details.removed_stock as removed_stock
            ')
            ->join('stocks', 'stocks.id', '=', 'stock_adjustment_details.stock_id')
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->where('stock_adjustment_details.stock_adjustment_id', '=', $stockAdjustmentId)
            ->groupBy('stock_adjustment_details.id')
            ->get()
            ->toArray();

        return [
            'stockAdjustment' => $stockAdjustment,
            'stockAdjustmentDetails' => $stockAdjustmentDetails
        ];
    }


    /**
     * Undocumented function
     *
     * @param array $stockAdjustmentDetails
     * @return mixed
     */
    public function receiveItems(string $reason, array $stockAdjustmentDetails): mixed
    {
        try {
            DB::transaction(function () use($reason, $stockAdjustmentDetails)
            {
                $stockAdjustment = StockAdjustment::create([
                    'adjusted_by' => auth()->user()->name,
                    'reason' => $reason
                ]);

                $stockAdjustment->stockAdjustmentDetails()->attach($stockAdjustmentDetails);

                $data = [];

                foreach ($stockAdjustmentDetails as $stockAdjustmentDetail)
                {
                    $data[] = [
                        'id' => $stockAdjustmentDetail['stock_id'],
                        'in_stock' => $stockAdjustmentDetail['stock_after'],
                        'stock_in' => $stockAdjustmentDetail['added_stock'],
                    ];
                }

                $uniqueBy = 'id';

                $update = [
                    'product_id' => DB::raw('stocks.product_id'),
                    'in_stock',
                    'stock_in' => DB::raw('stocks.stock_in + values(stock_in)')
                ];

                DB::table('stocks')->upsert($data, $uniqueBy, $update);

                $stockIds = \prepareGetKeyInMultiArray('stock_id', $stockAdjustmentDetails);

                $this->mailOnLowStock(NULL, $stockIds);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }



    /**
     * Undocumented function
     *
     * @param array $stockAdjustmentDetails
     * @return mixed
     */
    public function inventoryCount(string $reason, array $stockAdjustmentDetails): mixed
    {
        try {
            DB::transaction(function () use($reason, $stockAdjustmentDetails)
            {
                $stockAdjustment = StockAdjustment::create([
                    'adjusted_by' => auth()->user()->name,
                    'reason' => $reason
                ]);

                $stockAdjustment->stockAdjustmentDetails()->attach($stockAdjustmentDetails);

                $data = [];

                foreach ($stockAdjustmentDetails as $stockAdjustmentDetail)
                {
                    $data[] = [
                        'id' => $stockAdjustmentDetail['stock_id'],
                        'in_stock' => $stockAdjustmentDetail['counted_stock']
                    ];
                }

                $uniqueBy = 'id';

                $update = [
                    'product_id' => DB::raw('stocks.product_id'),
                    'in_stock',
                ];

                DB::table('stocks')->upsert($data, $uniqueBy, $update);

                $stockIds = \prepareGetKeyInMultiArray('stock_id', $stockAdjustmentDetails);

                $this->mailOnLowStock(NULL, $stockIds);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }

        /**
     * Undocumented function
     *
     * @param array $stockAdjustmentDetails
     * @return mixed
     */
    public function lossOrDamage(string $reason, array $stockAdjustmentDetails): mixed
    {
        try {
            DB::transaction(function () use($reason, $stockAdjustmentDetails)
            {
                $stockAdjustment = StockAdjustment::create([
                    'adjusted_by' => auth()->user()->name,
                    'reason' => $reason
                ]);

                $stockAdjustment->stockAdjustmentDetails()->attach($stockAdjustmentDetails);

                $data = [];

                foreach ($stockAdjustmentDetails as $stockAdjustmentDetail)
                {
                    $data[] = [
                        'id' => $stockAdjustmentDetail['stock_id'],
                        'in_stock' =>  $stockAdjustmentDetail['stock_after'],
                        'stock_out' => $stockAdjustmentDetail['removed_stock']
                    ];
                }

                $uniqueBy = 'id';

                $update = [
                    'product_id' => DB::raw('stocks.product_id'),
                    'in_stock',
                    'stock_out' => DB::raw('stocks.stock_out + values(stock_out)')
                ];

                DB::table('stocks')->upsert($data, $uniqueBy, $update);

                $stockIds = \prepareGetKeyInMultiArray('stock_id', $stockAdjustmentDetails);

                $this->mailOnLowStock(NULL, $stockIds);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }
}