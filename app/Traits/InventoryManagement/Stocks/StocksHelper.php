<?php

namespace App\Traits\InventoryManagement\Stocks;

use Illuminate\Support\Facades\DB;

trait StocksHelper
{
    /**
     * Get a product total incoming stocks via ['product_id']
     * Todo: update to single query
     *
     * @param array $productIds
     * @return array
     */
    public function prepareGetProductTotalIncomingStocks(array $productIds): array
    {
       $incomingStocks = DB::table('purchase_order_details')
                            ->whereIn('product_id', $productIds)
                            ->get(['product_id', 'remaining_ordered_quantity'])
                            ->toArray();

        foreach ($incomingStocks as $incomingStock)
        {
            $data[] = [
                'product_id' => $incomingStock->product_id,
                'incoming' => $incomingStock->remaining_ordered_quantity
            ];
        }

        return $data;
    }
}
