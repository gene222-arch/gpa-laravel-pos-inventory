<?php

namespace App\Http\Controllers\Api\InventoryManagement;

use App\Models\BadOrder;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryManagement\BadOrder\ShowRequest;
use App\Http\Requests\InventoryManagement\BadOrder\StoreRequest;
use App\Http\Requests\InventoryManagement\BadOrder\DeleteRequest;
use App\Http\Requests\InventoryManagement\BadOrder\UpdateRequest;

class BadOrdersController extends Controller
{

    use ApiResponser;

    protected $badOrder;

    public function __construct(BadOrder $badOrder)
    {
        $this->badOrder = $badOrder;
        $this->middleware(['auth:api, role:admin|manager']);
    }


    /**
     * Undocumented function
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', $this->badOrder);

        return $this->success($this->badOrder->getBadOrders(),
        'Success'
        );
    }


    /**
     * Undocumented function
     *\Illuminate\Http\
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $this->authorize('view', $this->badOrder);

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
        $this->authorize('create', $this->badOrder);

        $isRequestFormCreated = $this->badOrder->createRequestForm(
            $request->purchase_order_id,
            $request->badOrderDetails
        );

        return ($isRequestFormCreated !== true)
            ? $this->error($isRequestFormCreated)
            : $this->success([],
            '',
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
        $this->authorize('update', $this->badOrder);

        $isRequestFormUpdated = $this->badOrder->updateRequestForm(
            $request->bad_order_id,
            $request->purchase_order_id,
            $request->badOrderDetails
        );

        return (!$isRequestFormUpdated)
            ? $this->serverError()
            : $this->success([],
            '',
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
        $this->authorize('delete', $this->badOrder);

        $isRequestFormDeleted = $this->badOrder->deleteRequestForm($request->bad_order_ids);

        return (!$isRequestFormDeleted)
            ? $this->serverError()
            : $this->success([],
            '',
            200
            );
    }

}
