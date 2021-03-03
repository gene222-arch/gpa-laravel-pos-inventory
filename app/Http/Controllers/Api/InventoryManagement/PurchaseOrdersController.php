<?php

namespace App\Http\Controllers\Api\InventoryManagement;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Supplier;
use App\Traits\ApiResponser;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryManagement\PurchaseOrder\MailRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\ShowRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\IndexRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\StoreRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\DeleteRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\UpsertRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\ReceiveRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\CancelOrderRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\MailSupplierRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\DeleteProductsRequest;
use App\Http\Requests\InventoryManagement\PurchaseOrder\MarkAllReceivedRequest;

class PurchaseOrdersController extends Controller
{
    use ApiResponser;

    private $purchaseOrder;

    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->middleware(['auth:api', 'permission:Manage Purchase Orders']);
    }



    /**
     * * Get resources of purchase order details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->purchaseOrder->getAllPurchaseOrders();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }


    /**
     * * Get resources of purchase order details for bad orders request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchaseOrdersToBadOrder()
    {
        $result = $this->purchaseOrder->getAllPurchaseOrdersToBadOrders();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }


        /**
     * * Get resources of purchase order details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function filteredIndex(IndexRequest $request)
    {
        $purchaseOrders = $this->purchaseOrder->getAllPurchaseOrders(
            true,
            $request->operator,
            $request->filterBy,
            $request->filters
        );

        return $this->success($purchaseOrders,
        'Success');
    }




    /**
     * * Show `purchase_order` resources via ['id']
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $purchaseOrder = $this->purchaseOrder
            ->getPurchaseOrder($request->purchase_order_id);

        $purchaseOrderDetails = $this->purchaseOrder
            ->findPurchaseOrderDetails(
                $request->purchase_order_id,
                $request->do_filter,
                $request->table_to_filter,
                $request->filter_by,
                $request->operator,
                $request->filter
        );

        return !$purchaseOrder || !$purchaseOrderDetails
            ? $this->success([
                'purchaseOrder' => [],
                'items' => []
            ],
            'Success')
            : $this->success([
                'purchaseOrder' => $purchaseOrder,
                'items' => $purchaseOrderDetails
            ], 'Success');
    }


    /**
     * * Show `purchase_order` resources via ['id']
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showReceivedStocks(ShowRequest $request)
    {
        $result = $this->purchaseOrder->findStockReceiveDetails(
            $request->purchase_order_id
        );

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }


    /**
     * * Show `purchase_order` resources via ['id']
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showForBadOrdersRequest(ShowRequest $request)
    {
        $result = $this->purchaseOrder->findPurchaseOrderForBadOrders(
            $request->purchase_order_id
        );

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }




    /**
     * * Show `purchase_order` resources via ['id']
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editToReceive(ShowRequest $request)
    {

        $purchaseOrder = $this->purchaseOrder
            ->getPurchaseOrderForReceive($request->purchase_order_id);

        $purchaseOrderDetails = $this->purchaseOrder
            ->findPurchaseOrderDetailToReceive($request->purchase_order_id);

        return $this->success([
            'purchaseOrder' => $purchaseOrder,
            'items' => $purchaseOrderDetails
        ],
        'Success');
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
        $purchaseOrderDates = [
            'purchaseOrderDate' => $request->purchase_order_date,
            'expectedDeliveryDate' => $request->expected_delivery_date
        ];

        $result = $this->purchaseOrder->purchaseOrder(
            $request->supplier_id,
            $purchaseOrderDates,
            $request->items
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success(
            [],
            'Purchase ordered successfully',
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
        $result = $this->purchaseOrder
            ->upsertPurchaseOrderDetails(
            $request->purchase_order_id,
            $request->supplier_id,
            $request->purchase_order_date,
            $request->expected_delivery_date,
            $request->items
            );

        return ($result !== true)
            ? $this->error($result, 422)
            : $this->success(
            [],
            'Purchase order updated successfully.',
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


    public function cancelOrder(CancelOrderRequest $request)
    {
        $result = $this->purchaseOrder->cancelRemainingProducts(
            $request->purchase_order_id,
            $request->product_ids
        );

        return $result !== true
            ? $this->error($result)
            : $this->success($result, 'Purchase order cancelled sucessfully.');
    }



    /**
     * Delete a `purchase_order_details` via ['purchase_order_id']
     *
     * @param DeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteRequest $request)
    {
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
        $result = $this->purchaseOrder
            ->deleteProducts(
                $request->purchase_order_id,
                $request->product_ids
            );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
            'Product deleted successfully');

    }


}
