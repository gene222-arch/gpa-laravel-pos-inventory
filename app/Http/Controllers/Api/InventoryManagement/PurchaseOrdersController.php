<?php

namespace App\Http\Controllers\Api\InventoryManagement;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Supplier;
use App\Traits\ApiResponser;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryManagement\PurchaseOrder\ShowRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\StoreRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\DeleteRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\UpsertRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\ReceiveRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\DeleteProductsRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\MailRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\MailSupplierRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\MarkAllReceivedRequest;

class PurchaseOrdersController extends Controller
{
    use ApiResponser;

    private $purchaseOrder;
    private $supplier;
    private $product;
    private $stock;

    public function __construct(PurchaseOrder $purchaseOrder, Supplier $supplier, Product $product, Stock $stock)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->supplier = $supplier;
        $this->product = $product;
        $this->stock = $stock;
    }



    /**
     * * Get resources of purchase order details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', $this->purchaseOrder);

        return $this->success($this->purchaseOrder->loadPurchaseOrders(),
        'Purchase orders fetched successfully');
    }



    /**
     * * Show `purchase_order` resources via ['id']
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $this->authorize('view', $this->purchaseOrder);

        $purchaseOrder = $this->purchaseOrder
            ->findPurchaseOrderDetails($request->purchase_order_id);

        return $this->success($purchaseOrder,
        'Purchase Order fetched successfully');
    }



    /**
     * * Create new resource to `purchase_order`, `purchase_order_details` table
     * * Update stocks table field ['incoming']
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', $this->purchaseOrder);

        $purchaseOrderDates = [
            'purchaseOrderDate' => $request->purchase_order_date,
            'expectedDeliveryDate' => $request->expected_delivery_date
        ];

        $isOrderPurchased = $this->purchaseOrder->purchaseOrder(
            $request->supplier_id,
            $purchaseOrderDates,
            $request->items
        );

        return (!$isOrderPurchased)
            ? $this->serverError()
            : $this->success(
            'Purchase ordered successfully',
            'Success',
            201
        );
    }



    /**
     * Update or create new resource/s
     *
     * @param UpsertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upsert(UpsertRequest $request)
    {
        $this->authorize('update', $this->purchaseOrder);

        $isPurchaseOrderUpserted = $this->purchaseOrder
                                        ->upsertPurchaseOrderDetails(
                                        $request->purchase_order_id,
                                        $request->expected_delivery_date,
                                        $request->items
                                        );

        return (!$isPurchaseOrderUpserted)
            ? $this->serverError()
            : $this->success(
            'Purchase Order updated successfully',
            'Success',
            201
        );
    }


    /**
     * Undocumented function
     *
     * @param MailSupplierRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMailToSupplier(MailSupplierRequest $request)
    {
        $this->authorize('mailSupplier', $this->purchaseOrder);

        $fileName = 'PO-' . now()->toDateString() . '-' . time() . '.pdf';

        $this->purchaseOrder->toMailSupplier(
            $request->purchase_order_id,
            $request->supplier_id,
            $request->subject,
            $request->note,
            $fileName
        );

        return $this->success([], 'Success');
    }


    /**
     * * Mark all ordered supplies resource as received
     *
     * @param MarkAllReceivedRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllPurchasedOrderAsReceived(MarkAllReceivedRequest $request)
    {
        $this->authorize('markAllPurchaseOrderAsReceived', $this->purchaseOrder);

        $allPurchaseOrderIsReceived = $this->purchaseOrder
            ->markAllAsReceived($request->purchase_order_id,
            $request->product_ids
        );

        return  (!$allPurchaseOrderIsReceived)
            ? $this->serverError()
            : $this->success(
            [],
            'Purchase order received',
            201
        );
    }



    /**
     * * Update resource/s in purchase order details, purchase order, stocks
     * * when user receive a quantity of order
     *
     * @param ReceiveRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toReceivePurchaseOrder(ReceiveRequest $request)
    {
        $this->authorize('receivePurchaseOrder', $this->purchaseOrder);

        $result = $this->purchaseOrder
                        ->toReceive(
                        $request->supplier_id,
                        $request->purchase_order_id,
                        $request->items_received_quantities
                        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
            'Purchase order received successfully',
            201
        );
    }



    /**
     * Delete a `purchase_order_details` via ['purchase_order_id']
     *
     * @param DeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteRequest $request)
    {
        $this->authorize('delete', $this->purchaseOrder);

        $isPurchaseOrderDeleted = $this->purchaseOrder->deleteMany(
            $request->purchase_order_id
        );

        return (!$isPurchaseOrderDeleted)
            ? $this->serverError()
            : $this->success([],
            'Purchase Order Details deleted successfully');
    }



    /**
     * Delete a resource in purchase_order_details
     *
     * @param DeleteProductsRequest $request
     * @return void
     */
    public function deleteProducts(DeleteProductsRequest $request)
    {
        $this->authorize('deletePurchaseOrderPerProduct', $this->purchaseOrder);

        try {
            DB::transaction(function () use($request)
            {
                $purchaseOrderDetailsCount = $this->purchaseOrder
                                ->prepareGetTotalPurchaseOrderDetails($request->purchase_order_id);
                $purchaseOrderTotalReceivedItems = $this->purchaseOrder
                                ->prepareGetTotalItemsReceivedOf($request->purchase_order_id);

                if (
                    ($purchaseOrderDetailsCount > 1) &&
                    ($purchaseOrderTotalReceivedItems > 0))
                {
                    $isProductDeleted = $this->purchaseOrder
                                             ->deleteProducts(
                                            $request->purchase_order_id,
                                            $request->product_ids
                                             );

                    return (!$isProductDeleted)
                        ? $this->serverError()
                        : $this->success([],
                        'Product deleted successfully');
                }
                else
                {
                    throw new \ErrorException('Error found');
                }
            });
        } catch (\Throwable $th) {
            return $this->error('Please add at least one item to the purchase order.');
        }

    }


}
