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
     * @return array
     */
    public function loadBadOrdersWithDetails(): array
    {
        return DB::table('bad_orders')
            ->join('bad_order_details', 'bad_order_details.bad_order_id', '=', 'bad_orders.id')
            ->join('purchase_order_details', 'purchase_order_details.id', '=', 'bad_order_details.purchase_order_details_id')
            ->join('purchase_order', 'purchase_order.id', '=', 'purchase_order_details.purchase_order_id')
            ->join('suppliers', 'suppliers.id', '=', 'purchase_order.supplier_id')
            ->selectRaw('
                bad_orders.id as id,
                suppliers.name as supplier_name,
                SUM(bad_order_details.amount) as purchase_return,
                SUM(bad_order_details.quantity) as no_of_items,
                purchase_order.purchase_order_date as purchase_order_date
            ')
            ->groupBy(
                'id',
                'supplier_name',
                'purchase_order_date'
            )
            ->get()
            ->toArray();
    }


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
     * @return mixed
     */
    public function createRequestForm(int $purchaseOrderId, array $badOrderDetails): mixed
    {
        try {
            DB::transaction(function () use($purchaseOrderId, $badOrderDetails)
            {
                # insert and get id from `bad_orders`
                $badOrder = $this->createBadOrder($purchaseOrderId);

                # insert new `bad_order_details`
                $badOrder->orderDetails()->attach($badOrderDetails);

                # update stocks
                (new Stock())->updateBadOrderQtyOf($badOrderDetails);

            });
        } catch (\Throwable $th) {
            return $th->getMessage();
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

