<?php

namespace App\Http\Controllers\Api\InventoryManagement;

use App\Models\BadOrder;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryManagement\BadOrder\ShowRequest;
use App\Http\Requests\InventoryManagement\BadOrder\StoreRequest;
use App\Http\Requests\InventoryManagement\BadOrder\DeleteRequest;
use App\Http\Requests\InventoryManagement\BadOrder\ShowPurchaseOrder;
use App\Http\Requests\InventoryManagement\BadOrder\UpdateRequest;
use App\Models\PurchaseOrder;

class BadOrdersController extends Controller
{

    use ApiResponser;

    protected $badOrder;

    public function __construct(BadOrder $badOrder)
    {
        $this->badOrder = $badOrder;
        $this->middleware(['auth:api', 'permission:Manage Bad Orders']);
    }


    /**
     * Undocumented function
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->success($this->badOrder->getBadOrders(),
        'Success'
        );
    }


        /**
     * * Get resources of purchase order details for bad orders request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexPurchaseOrders()
    {
        $result = (new PurchaseOrder())->getAllPurchaseOrdersToBadOrders();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }


        /**
     * * Show `purchase_order` resources via ['id']
     *
     * @param ShowPurchaseOrder $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPurchaseOrder(ShowPurchaseOrder $request)
    {
        $result = (new PurchaseOrder())->findPurchaseOrderForBadOrders(
            $request->purchase_order_id
        );

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }
    



    /**
     * Undocumented function
     *\Illuminate\Http\
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $badOrderDetails = $this->badOrder
                                ->getBadOrderWithDetails($request->bad_order_id);

        return $this->success($badOrderDetails,
        'Success'
        );
    }


    /**
     * * Undocumented function
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $isRequestFormCreated = $this->badOrder->createRequestForm(
            $request->purchase_order_id,
            $request->badOrderDetails
        );

        return ($isRequestFormCreated !== true)
            ? $this->error($isRequestFormCreated)
            : $this->success([],
            'Bad order form created successfully.',
            201
            );
    }


    /**
     * Undocumented function
     * Todo to be continued
     * ? Is this needed for a bad order request form?
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $isRequestFormUpdated = $this->badOrder->updateRequestForm(
            $request->bad_order_id,
            $request->purchase_order_id,
            $request->badOrderDetails
        );

        return (!$isRequestFormUpdated)
            ? $this->serverError()
            : $this->success([],
            'Bad order form updated successfully.',
            201
            );
    }


    /**
     * Undocumented function
     *
     * @return JsonResponse
     */
    public function destroy(DeleteRequest $request)
    {
        $isRequestFormDeleted = $this->badOrder->deleteRequestForm($request->bad_order_ids);

        return (!$isRequestFormDeleted)
            ? $this->serverError()
            : $this->success([],
            '',
            200
            );
    }

}
