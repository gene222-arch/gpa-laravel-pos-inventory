<?php

namespace App\Traits\InventoryManagement\StockReceived;

use App\Models\ReceivedStock;
use Illuminate\Support\Facades\DB;

trait StockReceivedServices
{


    public function getAllReceivedStocks()
    {
        DB::statement('SET sql_mode="" ');

        return DB::table('received_stocks')
            ->selectRaw("
                SELECT
                    received_stocks.id,
                    DATE_FORMAT(received_stocks.created_at, '%M %d, %Y') as received_at,
                    received_stocks.purchase_order_id,
                    suppliers.name as supplier_name,
                    SUM(received_stock_details.received_quantity) as received_quantity
            ")
            ->join('receive_stock_details', 'receive_stock_details.received_stock_id', '=', 'received_stocks.id')
            ->join('suppliers', 'suppliers.id', '=', 'received_stocks.supplier_id')
            ->groupBy('received_stocks.id')
            ->get()
            ->toArray();
    }


    /**
     * Insert new record in `stock_received` table
     *
     * @param integer $purchaseOrderId
     * @param integer $supplierId
     * @return ReceivedStock
     */
    public function receiveStocks(int $purchaseOrderId, int $supplierId): ReceivedStock
    {
        return ReceivedStock::create([
            'purchase_order_id' => $purchaseOrderId,
            'supplier_id' => $supplierId,
        ]);
    }

}
