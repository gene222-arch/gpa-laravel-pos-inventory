<?php

namespace App\Traits\InventoryManagement\PurchaseOrder;

use App\Models\Stock;
use App\Models\PurchaseOrder;
use App\Models\ReceivedStock;
use Illuminate\Support\Facades\DB;

trait PurchaseOrderServices
{

    use PurchaseOrderDetailsServices, PurchaseOrderHelpers;

    /**
     * * Get record from `purchase_order_details`  via ['purchase_order_id']
     *
     * @param integer $purchaseOrderId
     * @return \App\Modles\Product
     */
    public function findPurchaseOrderDetails(int $purchaseOrderId)
    {
        return PurchaseOrder::find($purchaseOrderId)->purchaseOrderDetails;
    }



    /**
     * * Create a record in the `purchase_order` table
     *
     * @param integer $supplierId
     * @param array $purchaseOrderDates
     * @param array $purchaseOrderDetails
     * @return boolean
     */
    public function purchaseOrder(int $supplierId, array $purchaseOrderDates, array $purchaseOrderDetails): bool
    {
        try {
            DB::transaction(function () use($supplierId, $purchaseOrderDates, $purchaseOrderDetails)
            {
                # Get the sum of all ordered_quantity in the request
                $totalOrderedQuantity = prepareMultiArraySum('ordered_quantity',
                $purchaseOrderDetails
                );

                $poData = [
                    'supplier_id' => $supplierId,
                    'total_ordered_quantity' => $totalOrderedQuantity,
                    'total_remaining_ordered_quantity' => $totalOrderedQuantity,
                    'purchase_order_date' => $purchaseOrderDates['purchaseOrderDate'],
                    'expected_delivery_date' => $purchaseOrderDates['expectedDeliveryDate'],
                ];

                # Insert new data in `purchase_order` table
                $purchaseOrder = PurchaseOrder::create($poData);

                foreach ($purchaseOrderDetails as $purchaseOrderDetail)
                {
                    $orderedQuantity = $purchaseOrderDetail['ordered_quantity'];

                    $purchaseOrderDetails = preparePrepend([
                        'remaining_ordered_quantity' => $orderedQuantity
                    ], $purchaseOrderDetails);
                }

                # Insert new data in `purchase_order_details` table
                $purchaseOrder->purchaseOrderDetails()->attach($purchaseOrderDetails);

                # Update `stocks` table ['incoming'] field
                (new Stock())->updateIncomingStocksOf($purchaseOrderDetails);

            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }



    /**
     * Update or create new record/s from `purchase_order_details` table
     *
     * @param integer $purchaseOrderId
     * @param string $expectedDeliveryDate
     * @param array $purchaseOrderDetails
     * @return boolean
     */
    public function upsertPurchaseOrderDetails(
        int $purchaseOrderId,
        string $expectedDeliveryDate,
        array $purchaseOrderDetails): bool
    {
        try {
            DB::transaction(function () use($purchaseOrderId, $purchaseOrderDetails, $expectedDeliveryDate)
            {
                # append a purchase_order_id in the array
                $purchaseOrderDetails = preparePrepend([
                        'purchase_order_id' => $purchaseOrderId
                    ],
                    $purchaseOrderDetails
                );

                # update or insert new data in `purchase_order_details`
                DB::table('purchase_order_details')->upsert(
                    $purchaseOrderDetails,
                    $this->purchaseOrderDetailUniqueFields(),
                    $this->purchaseOrderDetailAssignableFields()
                );

                # update `purchase_order` table
                $this->updatePurchaseOrder(
                    $purchaseOrderId,
                $expectedDeliveryDate
                );

                # Update `stocks` table ['incoming'] field
                (new Stock())->updateIncomingStocksOf($purchaseOrderDetails);
            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }



    /**
     * Update a record in purchase order table
     * ['status', 'total_received_quantity' 'total_ordered_quantity' 'total_remaining_ordered_quantity']
     *
     * @param integer $purchaseOrderId
     * @param array $purchaseOrderDetails
     * @return boolean
     */
    public function updatePurchaseOrder(int $purchaseOrderId, string $expectedDeliveryDate = NULL): bool
    {
        try {

            DB::transaction(function () use($purchaseOrderId, $expectedDeliveryDate)
            {
                # get the sum of `purchase_order_details` fields
                # ['received_quantity', 'ordered_quantity', 'remaining_ordered_quantity']
                [
                    'totalReceivedQuantity' => $totalReceivedQuantity,
                    'totalOrderedQuantity' => $totalOrderedQuantity,
                    'totalRemainingOrderedQuantity' => $totalRemainingOrderedQuantity,

                ] = $this->prepareGetPODQtyFieldsTotal($purchaseOrderId);

                # Calculate new remaining ordered quantity
                $newRemainingOrderedQuantity = $totalOrderedQuantity - $totalReceivedQuantity;

                # Renew purchase order status
                $status = $this->preparePurchaseOrderStatus($totalReceivedQuantity,
                $newRemainingOrderedQuantity
                );

                $poData = [
                    'status' => $status,
                    'total_received_quantity' => $totalReceivedQuantity,
                    'total_ordered_quantity' => $totalRemainingOrderedQuantity + $totalReceivedQuantity,
                    'total_remaining_ordered_quantity' => $totalRemainingOrderedQuantity,
                    'expected_delivery_date' => $expectedDeliveryDate ?? DB::raw('expected_delivery_date')
                ];

                # update `purchase_order` table
                PurchaseOrder::where('id', '=', $purchaseOrderId)->updateTs($poData);

            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }



    /**
     * Receive quantity/ties of product/s
     *
     * @param integer $supplierId
     * @param integer $purchaseOrderId
     * @param array $purchaseOrderDetails
     * @return boolean
     */
    public function toReceive(int $supplierId, int $purchaseOrderId, array $purchaseOrderDetails): bool
    {
        try {
            DB::transaction(function () use($supplierId, $purchaseOrderId, $purchaseOrderDetails)
            {

                # Update purchase order details
                $this->createPurchaseOrderDetails($purchaseOrderId,
                    $purchaseOrderDetails
                );

                # insert new `received_stocks`
                $stockReceived = (new ReceivedStock())->receiveStocks($purchaseOrderId,
                    $supplierId
                );

                # attach `stock_received_details`
                $stockReceived->receiveStockDetails()->attach($purchaseOrderDetails);

                (new Stock())->stockIn($purchaseOrderDetails);

                # Update purchase order table
                $this->updatePurchaseOrder($purchaseOrderId);
            });

        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }



    /**
    * Mark all records of purchase orders as received
     *
     * @param integer $purchaseOrderId
     * @param array $productIds
     * @return boolean
     */
    public function markAllAsReceived(int $purchaseOrderId, array $productIds): bool
    {
        try {
            DB::transaction(function () use($purchaseOrderId, $productIds)
            {
                # Update stocks before updating purchase order details qty fields
                (new Stock())->receiveAllProductStocksOf(
                    $purchaseOrderId,
                    $productIds
                );

                PurchaseOrder::find($purchaseOrderId)->status = 'Closed';

                # Update purchase order details
                DB::table('purchase_order_details')
                    ->where('purchase_order_id', '=', $purchaseOrderId)
                    ->whereIn('product_id', $productIds)
                    ->updateTs([
                        'received_quantity' => DB::raw('ordered_quantity'),
                        'remaining_ordered_quantity' => 0
                    ]);


                # Update purchase order
                $this->updatePurchaseOrder($purchaseOrderId);
            });

        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }


    /**
     * Delete record/s from purchase order details via purchase order id
     *
     * @param array $purchaseOrderIds
     * @return boolean
     */
    public function deleteMany(array $purchaseOrderIds): bool
    {
        return \boolval(DB::table('purchase_order_details')
                                ->whereIn('purchase_order_id', $purchaseOrderIds)
                                ->delete()
        );
    }


    /**
     * Delete a record in purchase order details via ['purchase_order_id', 'product_id']
     *
     * @param integer $purchaseOrderIds
     * @param array $productIds
     * @return boolean
     */
    public function deleteProducts(int $purchaseOrderId, array $productIds): bool
    {
       try {
           DB::transaction(function () use($purchaseOrderId, $productIds)
           {
                DB::table('purchase_order_details')
                    ->where('purchase_order_id', '=', $purchaseOrderId)
                    ->whereIn('product_id', $productIds)
                    ->delete();

                $this->updatePurchaseOrder($purchaseOrderId);

                foreach ($productIds as $productId)
                {
                    $this->stock->updateIncomingStocksOf($productId);
                }
           });
       } catch (\Throwable $th) {
           return false;
       }

       return true;
    }


}
