<?php

namespace App\Traits\InventoryManagement\PurchaseOrder;

use App\Jobs\QueuePurchaseOrderNotification;
use App\Models\Stock;
use App\Models\PurchaseOrder;
use App\Models\ReceivedStock;
use App\Models\Supplier;
use App\Traits\PDF\PDFGeneratorServices;
use Illuminate\Support\Facades\DB;

trait PurchaseOrderServices
{
    use PurchaseOrderDetailsServices, PurchaseOrderHelpers, PDFGeneratorServices;

    public function getAllPurchaseOrders(bool $doFilter = false, string $operator = null, string $filterBy = null, array $filters = NULL): array
    {
        DB::statement('SET sql_mode = "" ');

        return DB::table('purchase_order')
            ->selectRaw("
                purchase_order.id as id,
                DATE_FORMAT(purchase_order.purchase_order_date, '%M %d, %Y') as purchase_order_date,
                purchase_order.status as status,
                suppliers.name as supplier,
                purchase_order.total_received_quantity as received,
                DATE_FORMAT(purchase_order.expected_delivery_date, '%M %d, %Y') as expected_on,
                purchase_order.total_ordered_quantity total_ordered_quantity
            ")
            ->join('purchase_order_details', 'purchase_order_details.purchase_order_id', '=', 'purchase_order.id')
            ->join('suppliers', 'suppliers.id', '=', 'purchase_order.supplier_id')
            ->when(($doFilter === true && $operator === '!='), function($q) use ($filterBy, $filters) {
                return $q->whereNotIn('purchase_order.' . $filterBy, $filters);
            })
            ->when(($doFilter === true && $operator === '='), function($q) use ($filterBy, $filters) {
                return $q->where('purchase_order.' . $filterBy, $filters);
            })
            ->groupBy('purchase_order.id')
            ->orderBy('purchase_order.created_at', 'desc')
            ->get()
            ->toArray();
    }


    public function getAllPurchaseOrdersToBadOrders(): array
    {
        DB::statement('SET sql_mode = "" ');

        return DB::table('purchase_order')
            ->selectRaw('
                purchase_order.id as id,
                SUM(purchase_order_details.received_quantity) as received_quantity
            ')
            ->join('purchase_order_details', 'purchase_order_details.purchase_order_id', '=', 'purchase_order.id')
            ->where('received_quantity', '>', 0)
            ->groupBy('id')
            ->orderBy('purchase_order.created_at', 'desc')
            ->get()
            ->toArray();
    }


    /**
     * Undocumented function
     *
     * @param integer $purchaseOrderId
     * @return Illuminate\Support\Collection
     */
    public function loadPurchaseOrderDetails(int $purchaseOrderId)
    {
        DB::statement('SET sql_mode = "" ');

        return DB::table('purchase_order')
            ->selectRaw("
                products.name,
                purchase_order_details.remaining_ordered_quantity,
                purchase_order_details.purchase_cost,
                purchase_order_details.amount
            ")
            ->join('purchase_order_details', 'purchase_order_details.purchase_order_id', '=', 'purchase_order.id')
            ->join('suppliers', 'suppliers.id', '=', 'purchase_order.supplier_id')
            ->join('products', 'products.id', '=', 'purchase_order_details.product_id')
            ->where('purchase_order_id', '=', $purchaseOrderId)
            ->groupBy('purchase_order_details.id')
            ->get();
    }


    public function getPurchaseOrder(int $purchaseOrderId)
    {
        $result = DB::table('purchase_order')
            ->selectRaw('
                purchase_order.id,
                purchase_order.status,
                purchase_order.ordered_by,
                suppliers.name as supplier,
                suppliers.id as supplier_id,
                DATE_FORMAT(purchase_order.purchase_order_date, "%M %d, %Y") as purchase_order_date,
                DATE_FORMAT(purchase_order.expected_delivery_date, "%M %d, %Y") as expected_delivery_date,
                purchase_order.total_received_quantity,
                purchase_order.total_ordered_quantity,
                purchase_order.total_remaining_ordered_quantity
            ')
            ->join('suppliers', 'suppliers.id', '=', 'purchase_order.supplier_id')
            ->where('purchase_order.id', '=', $purchaseOrderId)
            ->first();

        return $result ? $result : [];
    }


    public function getPurchaseOrderForReceive(int $purchaseOrderId)
    {
        return DB::table('purchase_order')
            ->selectRaw('
                purchase_order.id as id,
                purchase_order.id as purchase_order_id,
                suppliers.id as supplier_id
            ')
            ->join('suppliers', 'suppliers.id', '=', 'purchase_order.supplier_id')
            ->where('purchase_order.id', '=', $purchaseOrderId)
            ->first();
    }


    /**
     * * Get record from `purchase_order_details`  via ['purchase_order_id']
     *
     * @param integer $purchaseOrderId
     * @return \App\Modles\Product
     */
    public function findPurchaseOrderDetails(int $purchaseOrderId)
    {
        DB::statement('SET sql_mode= "" ');

        $result = DB::table('purchase_order')
            ->selectRaw('
                purchase_order_details.id as id,
                products.id as product_id,
                products.name as product_description,
                stocks.in_stock as in_stock,
                purchase_order_details.ordered_quantity as ordered_quantity,
                purchase_order.status as status,
                purchase_order_details.purchase_cost as purchase_cost,
                stocks.incoming as incoming,
                purchase_order_details.amount
            ')
            ->join('purchase_order_details', 'purchase_order_details.purchase_order_id', '=', 'purchase_order.id')
            ->join('suppliers', 'suppliers.id', '=', 'purchase_order.supplier_id')
            ->join('products', 'products.id', '=', 'purchase_order_details.product_id')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            ->where('purchase_order.id', '=', $purchaseOrderId)
            ->groupBy('purchase_order_details.id')
            ->get()
            ->toArray();

        return $result ? $result : [];
    }



       /**
     * * Get record from `purchase_order_details`  via ['purchase_order_id']
     *
     * @param integer $purchaseOrderId
     * @return \App\Modles\Product
     */
    public function findPurchaseOrderForBadOrders(int $purchaseOrderId)
    {
        DB::statement('SET sql_mode= "" ');

        $purchaseOrder = $this->getPurchaseOrder(
            $purchaseOrderId
        );

        $items = DB::table('purchase_order_details')
            ->selectRaw('
                purchase_order_details.id as id,
                products.id as product_id,
                products.name as product_description,
                stocks.in_stock as in_stock,
                products.sold_by as unit_of_measurement,
                purchase_order_details.received_quantity as received_quantity,
                purchase_order_details.purchase_cost as purchase_cost
            ')
            ->join('products', 'products.id', '=', 'purchase_order_details.product_id')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            ->leftJoin('bad_order_details', 'bad_order_details.purchase_order_details_id', '=', 'purchase_order_details.id')
            ->where('purchase_order_details.purchase_order_id', '=', $purchaseOrderId)
            ->whereRaw('bad_order_details.purchase_order_details_id IS NULL')
            ->whereRaw('bad_order_details.product_id IS NULL')
            ->where('purchase_order_details.received_quantity', '>', 0)
            ->get()
            ->toArray();

        if ($purchaseOrder && count($items))
        {
            foreach ($items as $item)
            {
                $item->quantity = 0;
                $item->amount = 0;
                $item->defect = '';
            }

            return [
                'purchaseOrder' => $purchaseOrder,
                'items' => $items
            ];
        }

        return [];
    }



        /**
     * * Get record from `purchase_order_details`  via ['purchase_order_id']
     *
     * @param integer $purchaseOrderId
     * @return \App\Modles\Product
     */
    public function findStockReceiveDetails(int $purchaseOrderId)
    {
        DB::statement('SET sql_mode= "" ');

        $result = DB::table('received_stocks')
            ->selectRaw('
                received_stock_details.id as id,
                products.name as product_description,
                stocks.in_stock as in_stock,
                received_stock_details.received_quantity,
                purchase_order_details.purchase_cost,
                purchase_order_details.amount,
                stocks.incoming as incoming,
                DATE_FORMAT(received_stocks.created_at, "%M, %d, %Y") as received_at
            ')
            ->join('received_stock_details', 'received_stock_details.received_stock_id', '=', 'received_stocks.id')
            ->join('stocks', 'stocks.product_id', '=', 'received_stock_details.product_id')
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->join('purchase_order_details', 'purchase_order_details.id', '=', 'received_stock_details.purchase_order_details_id')
            ->where('purchase_order_details.purchase_order_id', '=', $purchaseOrderId)
            ->get()
            ->toArray();

        return $result ? $result : [];
    }





    /**
     * * Get record from `purchase_order_details`  via ['purchase_order_id']
     *
     * @param integer $purchaseOrderId
     * @return \App\Modles\Product
     */
    public function findPurchaseOrderDetailToReceive(int $purchaseOrderId)
    {
        DB::statement('SET sql_mode= "" ');

        $result =  DB::table('purchase_order')
            ->selectRaw('
                purchase_order_details.id as id,
                purchase_order_details.id as purchase_order_details_id,
                products.id as product_id,
                products.name as product_description,
                purchase_order_details.ordered_quantity as total_ordered_quantity,
                purchase_order_details.received_quantity as total_received_quantity
            ')
            ->join('purchase_order_details', 'purchase_order_details.purchase_order_id', '=', 'purchase_order.id')
            ->join('suppliers', 'suppliers.id', '=', 'purchase_order.supplier_id')
            ->join('products', 'products.id', '=', 'purchase_order_details.product_id')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            ->where('purchase_order.id', '=', $purchaseOrderId)
            ->where('purchase_order_details.remaining_ordered_quantity', '!=', 0)
            ->groupBy('purchase_order_details.id')
            ->get()
            ->toArray();

        foreach ($result as $object)
        {
            $object->received_quantity = 0;
        }

        return $result;
    }


    /**
     * * Create a record in the `purchase_order` table
     *
     * @param integer $supplierId
     * @param array $purchaseOrderDates
     * @param array $purchaseOrderDetails
     * @return mixed
     */
    public function purchaseOrder(int $supplierId, array $purchaseOrderDates, array $purchaseOrderDetails): mixed
    {
        try {
            DB::transaction(function () use($supplierId, $purchaseOrderDates, $purchaseOrderDetails)
            {
                # Get the sum of all ordered_quantity in the request
                $totalOrderedQuantity = prepareMultiArraySum('ordered_quantity',
                $purchaseOrderDetails
                );

                $poData = [
                    'ordered_by' => auth()->user()->name,
                    'supplier_id' => $supplierId,
                    'total_ordered_quantity' => $totalOrderedQuantity,
                    'total_remaining_ordered_quantity' => $totalOrderedQuantity,
                    'purchase_order_date' => $purchaseOrderDates['purchaseOrderDate'],
                    'expected_delivery_date' => $purchaseOrderDates['expectedDeliveryDate'],
                ];

                # Insert new data in `purchase_order` table
                $purchaseOrder = PurchaseOrder::create($poData);

                # Insert new data in `purchase_order_details` table
                $purchaseOrder->purchaseOrderDetails()->attach($purchaseOrderDetails);

                $productIds = array_map(fn($ar) => $ar['product_id'], $purchaseOrderDetails);

                # Update `stocks` table ['incoming'] field
                (new Stock())->updateIncomingStocksOf($productIds);

            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }



    /**
     * Update or create new record/s from `purchase_order_details` table
     *
     * @param integer $purchaseOrderId
     * @param string $expectedDeliveryDate
     * @param array $purchaseOrderDetails
     * @return mixed
     */
    public function upsertPurchaseOrderDetails(
        int $purchaseOrderId,
        int $supplierId,
        string $purchaseOrderDate,
        string $expectedDeliveryDate,
        array $purchaseOrderDetails): mixed
    {
        try {
            DB::transaction(function () use($purchaseOrderId, $supplierId, $purchaseOrderDetails, $purchaseOrderDate, $expectedDeliveryDate)
            {
                $totalOrderedQuantity = prepareMultiArraySum('ordered_quantity',
                $purchaseOrderDetails
                );

                $poData = [
                    'supplier_id' => $supplierId,
                    'total_ordered_quantity' => $totalOrderedQuantity,
                    'total_remaining_ordered_quantity' => $totalOrderedQuantity,
                    'purchase_order_date' => $purchaseOrderDate,
                    'expected_delivery_date' => $expectedDeliveryDate,
                ];

                # Insert new data in `purchase_order` table
                 PurchaseOrder::where('id', '=', $purchaseOrderId)
                    ->update($poData);

                # Insert new data in `purchase_order_details` table

                $data = [];

                foreach ($purchaseOrderDetails as $purchaseOrder)
                {
                   $data[] = [
                        'purchase_order_id' => $purchaseOrderId,
                        'product_id' => $purchaseOrder['product_id'],
                        'ordered_quantity' => $purchaseOrder['ordered_quantity'],
                        'remaining_ordered_quantity' => $purchaseOrder['remaining_ordered_quantity'],
                        'purchase_cost' => $purchaseOrder['purchase_cost'],
                        'amount' => $purchaseOrder['amount'],
                   ];
                }

                DB::table('purchase_order_details')
                    ->upsert(
                        $data,
                        'product_id',
                        [
                            'purchase_order_id',

                            'ordered_quantity',
                            'remaining_ordered_quantity',
                            'purchase_cost',
                            'amount'
                    ]);

                $productIds = array_map(fn($ar) => $ar['product_id'], $purchaseOrderDetails);

                # Update `stocks` table ['incoming'] field
                (new Stock())->updateIncomingStocksOf($productIds);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
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
    public function updatePurchaseOrder(int $purchaseOrderId, string $expectedDeliveryDate = NULL, string $purchaseOrderDate = NULL): bool
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
                    'total_ordered_quantity' => $totalOrderedQuantity,
                    'total_remaining_ordered_quantity' => $totalRemainingOrderedQuantity,
                    'purchase_order_date' => $purchaseOrderDate ?? DB::raw('purchase_order_date'),
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
     * Undocumented function
     *
     * @param integer $purchaseOrderId
     * @param integer $supplierId
     * @param string $subject
     * @param string $note
     * @param string $fileName
     * @return void
     */
    public function toMailSupplier(int $purchaseOrderId, int $supplierId, string $subject, string $note, string $fileName)
    {
        $this->generatePurchaseOrderPDF($purchaseOrderId, $fileName);

        dispatch(new QueuePurchaseOrderNotification(
            Supplier::find($supplierId),
            $subject,
            $note,
            $fileName
        ));
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
     * Receive quantity/ties of product/s
     *
     * @param integer $supplierId
     * @param integer $purchaseOrderId
     * @param array $purchaseOrderDetails
     * @return mixed
     */
    public function toReceive(int $supplierId, int $purchaseOrderId, array $purchaseOrderDetails): mixed
    {
        try {
            DB::transaction(function () use($supplierId, $purchaseOrderId, $purchaseOrderDetails)
            {

                $receivedQuantity = array_reduce($purchaseOrderDetails, function($total, $current) {
                    return $total + $current['received_quantity'];
                }, 0);

                if ($receivedQuantity)
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

                    # update stocks
                    (new Stock())->stockIn($purchaseOrderDetails);

                    # Update purchase order table
                    $this->updatePurchaseOrder($purchaseOrderId);
                }
            });

        } catch (\Throwable $th) {
            return $th->getMessage();
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
     * @return mixed
     */
    public function deleteProducts(int $purchaseOrderId, array $productIds): mixed
    {
       try {
           DB::transaction(function () use($purchaseOrderId, $productIds)
           {
                DB::table('purchase_order_details')
                    ->where('purchase_order_id', '=', $purchaseOrderId)
                    ->whereIn('product_id', $productIds)
                    ->delete();

                $this->updatePurchaseOrder($purchaseOrderId);

                (new Stock())->updateIncomingStocksOf($productIds);
           });
       } catch (\Throwable $th) {
           return $th->getMessage();
       }

       return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $purchaseOrderId
     * @param array $productIds
     * @return mixed
     */
    public function cancelRemainingProducts (int $purchaseOrderId, array $productIds):mixed
    {
        try {
            DB::transaction(function () use($purchaseOrderId, $productIds)
            {
                PurchaseOrder::find($purchaseOrderId)
                    ->where('status', '!=', 'Closed')
                    ->update([
                        'status' => 'Closed',
                        'total_remaining_ordered_quantity' => 0
                ]);

                (new Stock())->outGoingStocks($purchaseOrderId, $productIds);

                DB::table('purchase_order_details')
                    ->where('purchase_order_id', '=', $purchaseOrderId)
                    ->updateTs([
                        'remaining_ordered_quantity' => 0
                    ]);


            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


}
