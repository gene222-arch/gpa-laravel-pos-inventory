<?php

namespace App\Traits\InventoryManagement\StockReceived;

use App\Models\ReceivedStock;
use Illuminate\Support\Facades\DB;

trait StockReceivedServices
{

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
