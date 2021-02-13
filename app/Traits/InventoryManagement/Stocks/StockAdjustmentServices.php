<?php

namespace App\Traits\InventoryManagement\Stocks;

use App\Models\StockAdjustment;
use Illuminate\Support\Facades\DB;

trait StockAdjustmentServices
{


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
                stock_adjustments.id,
                DATE_FORMAT(stock_adjustments.created_at, '%M %d %Y') AS adjusted_at,
                stock_adjustments.reason,
                SUM(
                    stock_adjustment_details.added_stock +
                    stock_adjustment_details.counted_stock +
                    stock_adjustment_details.removed_stock
                ) AS quantity
            ")
            ->join('stock_adjustment_details', 'stock_adjustment_details.stock_adjustment_id', '=', 'stock_adjustments.id')
            ->groupBy('stock_adjustments.id')
            ->get()
            ->toArray();
    }


    /**
     * Undocumented function
     *
     * @param integer $stockAdjustmentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStockAdjustment(int $stockAdjustmentId)
    {
        return (new StockAdjustment())->with('stockAdjustmentDetails')
            ->whereHas('stockAdjustmentDetails', function ($q) use($stockAdjustmentId)
            {
                return $q->where('stock_adjustment_id', '=', $stockAdjustmentId);
            })
            ->get();
    }


    /**
     * Undocumented function
     *
     * @param array $stockAdjustmentDetails
     * @return mixed
     */
    public function adjustStocks(string $reason, array $stockAdjustmentDetails): mixed
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
                        'stock_out' => $stockAdjustmentDetail['removed_stock']
                    ];
                }

                $uniqueBy = 'id';

                $update = [
                    'in_stock',
                    'product_id' => DB::raw('stocks.product_id'),
                    'stock_in' => DB::raw('stocks.stock_in + values(stock_in)'),
                    'stock_out' => DB::raw('stocks.stock_out + values(stock_out)')
                ];

                DB::table('stocks')->upsert($data, $uniqueBy, $update);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }
}
