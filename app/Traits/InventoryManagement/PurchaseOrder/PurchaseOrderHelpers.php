<?php

namespace App\Traits\InventoryManagement\PurchaseOrder;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

trait PurchaseOrderHelpers
{
    /**
     * Get for purchase order status
     *
     * @param [type] $receivedQuantity
     * @param [type] $remainingOrderedQuantity
     * @return string
     */
    public function preparePurchaseOrderStatus($receivedQuantity, $remainingOrderedQuantity): string
    {
        $status = 'Pending';

        if ($receivedQuantity > 0)
        {
            $status = 'Partially Receive';
        }

        if ($remainingOrderedQuantity === 0)
        {
            $status = 'Closed';
        }

        return $status;
    }



    /**
     * Get total sum of each quantity fields in purchase order details
     *
     * @return array
     */
    public function prepareGetPODQtyFieldsTotal(int $purchaseOrderId): array
    {
        $purchaseOrderDetails = DB::table('purchase_order_details')
                                    ->where('purchase_order_id', '=', $purchaseOrderId)
                                    ->get([
                                        'ordered_quantity',
                                        'received_quantity',
                                        'remaining_ordered_quantity'
                                    ]);

        $totalRemainingOrderedQuantity = $purchaseOrderDetails->map->remaining_ordered_quantity->sum();
        $totalReceivedQuantity = $purchaseOrderDetails->map->received_quantity->sum();
        $totalOrderedQuantity = $purchaseOrderDetails->map->ordered_quantity->sum();

        return [
            'totalReceivedQuantity' => $totalReceivedQuantity,
            'totalOrderedQuantity' => $totalOrderedQuantity,
            'totalRemainingOrderedQuantity' => $totalRemainingOrderedQuantity,
        ];
    }



    /**
     * Destructure a products record of received quantity, ordered quantity and remaining ordered quantity
     *
     * @param integer $purchaseOrderId
     * @param array $productIds
     * @return array
     */
    public function prepareGetPODRemainingQty(int $purchaseOrderId, array $productIds): array
    {
        $qtyFields = DB::table('purchase_order_details')
                            ->where('purchase_order_id', '=', $purchaseOrderId)
                            ->whereIn('product_id', $productIds)
                            ->get([
                                'product_id',
                                'remaining_ordered_quantity'
                            ]);

        $data = [];
        
        foreach ($qtyFields as $qtyField)
        {
            $data[] = [
                'product_id' => $qtyField->product_id,
                'stock_in' => $qtyField->remaining_ordered_quantity
            ];
        }

        return $data;
    }



    /**
     * Get count of `purchase_order_details` via ['purchase_order_id']
     *
     * @param integer $purchaseOrderId
     * @return integer
     */
    public function prepareGetTotalPurchaseOrderDetails(int $purchaseOrderId): int
    {
        return PurchaseOrder::find($purchaseOrderId)
                            ->purchaseOrderDetails
                            ->count();
    }



    /**
     * Get total count of ['received_quantity'] field via ['purchase_order_id']
     *
     * @param integer $purchaseOrderId
     * @return integer
     */
    public function prepareGetTotalItemsReceivedOf(int $purchaseOrderId): int
    {
        return PurchaseOrder::find($purchaseOrderId)
                            ->purchaseOrderDetails
                            ->map
                            ->pivot
                            ->map
                            ->received_quantity
                            ->sum();
    }

}
