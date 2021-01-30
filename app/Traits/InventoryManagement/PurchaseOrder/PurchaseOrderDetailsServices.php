<?php

namespace App\Traits\InventoryManagement\PurchaseOrder;

use Illuminate\Support\Facades\DB;

trait PurchaseOrderDetailsServices
{

    /**
     * Insert new record in `purchase_order_details` table
     *
     * @param integer $purchaseOrderId
     * @param array $purchaseOrderDetails
     * @param integer $receivedQuantity
     * @return void
     */
    public function createPurchaseOrderDetails(int $purchaseOrderId, array $purchaseOrderDetails): void
    {
        $purchaseOrderDetails = preparePrepend([
            'purchase_order_id' => $purchaseOrderId
        ], $purchaseOrderDetails);

        $uniqueBy = 'product_id';

        $update = [
            'received_quantity' => DB::raw('purchase_order_details.received_quantity + values(received_quantity)'),
            'remaining_ordered_quantity' => DB::raw('purchase_order_details.remaining_ordered_quantity - values(received_quantity)'),
            'purchase_cost' => DB::raw('purchase_order_details.purchase_cost')
        ];


        DB::table('purchase_order_details')
            ->where('purchase_order_id', '=', $purchaseOrderId)
            ->upsert($purchaseOrderDetails,
            $uniqueBy,
            $update);
    }

}
