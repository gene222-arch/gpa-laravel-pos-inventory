<?php

namespace App\Traits\InventoryManagement\BadOrders;

use App\Models\BadOrder;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

trait BadOrderServices
{


    /**
     * Undocumented function
     *
     * @param integer $badOrderId
     */
    public function getBadOrderWithDetails(int $badOrderId)
    {
        return $this->badOrder
                    ->find($badOrderId)
                    ->with('bad_order_details');
    }


    /**
     * Undocumented function
     *
     * @param integer $badOrderIds
     * @return boolean
     */
    public function createRequestForm(int $purchaseOrderId, array $badOrderDetails): bool
    {
        try {
            DB::transaction(function () use($purchaseOrderId, $badOrderDetails)
            {
                # insert and get id from `bad_orders`
                $badOrder = $this->createBadOrder($purchaseOrderId);

                # insert new `bad_order_details`
                $badOrder->orderDetails()->attach($badOrderDetails);

                # update stocks
                (new \App\Models\Stock())->updateBadOrderQtyOf($badOrderDetails);
            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }


    /**
     * Undocumented function
     * ? Must know if this is needed?
     *
     * @param integer $badOrderId
     * @param integer $purchaseOrderId
     * @param array $badOrderDetails
     * @return boolean
     */
    public function updateRequestForm(int $badOrderId, int $purchaseOrderId, array $badOrderDetails): bool
    {
        try {
            DB::transaction(function () use($badOrderId, $purchaseOrderId, $badOrderDetails)
            {
                # `bad_orders`
                $this->updateBadOrder(
                    $badOrderId,
                    $purchaseOrderId
                );

                $badOrderDetailLists = $this->updateBadOrderDetails(
                    $badOrderId,
                    $purchaseOrderId,
                    $badOrderDetails
                );

                (new Stock())->updateBadOrderQtyOf($badOrderDetailLists);
            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }


    /**
     * Undocumented function
     *
     * @param array $badOrderIds
     * @return boolean
     */
    public function deleteRequestForm(array $badOrderIds): bool
    {
        return \boolval(DB::table('bad_orders')
                            ->whereIn('id', $badOrderIds)
                            ->delete());
    }



    /**
     * Undocumented function
     *
     * @param integer $purchaseOrderId
     * @return BadOrder
     */
    public function createBadOrder(int $purchaseOrderId): BadOrder
    {
        return BadOrder::create([
            'purchase_order_id' => $purchaseOrderId
        ]);
    }

    /**
     * Undocumented function
     *
     * @param integer $badOrderId
     * @param integer $purchaseOrderId
     * @return void
     */
    public function updateBadOrder(int $badOrderId, int $purchaseOrderId): void
    {
        BadOrder::where('id', '=', $badOrderId)
                ->update([
                    'purchase_order_id' => $purchaseOrderId,
                    'status' => 'Pending'
                ]);
    }


    /**
     * Undocumented function
     *
     * @param integer $badOrderId
     * @param integer $purchaseOrderId
     * @param array $badOrderDetails
     * @return mixed
     */
    public function updateBadOrderDetails(int $badOrderId, int $purchaseOrderId, array $badOrderDetails)
    {
        $badOrder = new BadOrder();

        $badOrderDetails = preparePrepend([
            'bad_order_id' => $badOrderId,
            'purchase_order_details_id' => $purchaseOrderId
        ], $badOrderDetails);

        $uniqueBy = $badOrder->uniqueKeys();

        $update = $badOrder->massAssignableKeys();

        # `bad_order_details`
        $isUpdated = DB::table('bad_order_details')
                        ->upsert($badOrderDetails,
                        $uniqueBy,
                        $update);

        return (!$isUpdated)
            ? false
            : $badOrderDetails;
    }

}

