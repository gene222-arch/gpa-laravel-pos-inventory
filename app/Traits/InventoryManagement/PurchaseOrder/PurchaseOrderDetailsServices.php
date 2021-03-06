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

        foreach ($purchaseOrderDetails as $purchaseOrderDetail)
        {
            $data[] = [
                'purchase_order_id' => $purchaseOrderId,
                'id' => $purchaseOrderDetail['purchase_order_details_id'],
                'product_id' => $purchaseOrderDetail['product_id'],
                'received_quantity' => $purchaseOrderDetail['received_quantity'],
            ];
        }

        $uniqueBy = 'product_id';

        $update = [
            'purchase_order_id' => DB::raw('purchase_order_details.purchase_order_id'),
            'received_quantity' => DB::raw('purchase_order_details.received_quantity + values(received_quantity)'),
            'remaining_ordered_quantity' => DB::raw('purchase_order_details.remaining_ordered_quantity - values(received_quantity)'),
            'purchase_cost' => DB::raw('purchase_order_details.purchase_cost')
        ];

        DB::table('purchase_order_details')
            ->where('purchase_order_id', '=', $purchaseOrderId)
            ->upsert($data, $uniqueBy, $update);
    }

}
