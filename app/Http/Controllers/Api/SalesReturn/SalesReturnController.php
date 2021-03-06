<?php

namespace App\Http\Controllers\Api\SalesReturn;

use App\Models\SalesReturn;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalesReturn\ShowRequest;
use App\Http\Requests\SalesReturn\StoreRequest;
use App\Http\Requests\SalesReturn\ForSalesReturnRequest;
use App\Http\Requests\SalesReturn\DeleteRequest;
use App\Http\Requests\SalesReturn\UpdateRequest;
use App\Http\Requests\SalesReturn\RemoveItemsRequest;
use App\Models\Customer;

class SalesReturnController extends Controller
{
    use ApiResponser;

    protected $salesReturn;

    public function __construct(SalesReturn $salesReturn)
    {
        $this->salesReturn = $salesReturn;
        $this->middleware(['auth:api', 'permission:Manage Sales Returns']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success($this->salesReturn->loadSalesReturns());
    }


    /**
     * Display a listing of the resource for Sales return.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexWithOrders()
    {
        $result = $this->salesReturn->getCustomersWithOrders();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }


        /**
     * Undocumented function
     *
     * @param ForSalesReturnRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showForSalesReturn(ForSalesReturnRequest $request)
    {
        $result = $this->salesReturn
            ->findCustomerOrderForSalesReturn($request->pos_id);

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $result = $this->salesReturn->createRequestForm(
            $request->pos_id,
            $request->posSalesReturnDetails
        );

        return ($result !== true)
                ? $this->error($result)
                : $this->success([],
                    'Sales return form created successfully.',
                    201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request)
    {
        return $this->success($this->salesReturn->getSalesReturnWithDetails(
            $request->sales_return_id
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $result = $this->salesReturn->updateRequestForm(
            $request->pos_sales_return_id,
            $request->pos_id,
            $request->posSalesReturnDetails
        );

        return ($result !== true)
                ? $this->error($result)
                : $this->success([],
                    'Success',
                    201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request)
    {
        $result = $this->salesReturn->deleteMany($request->validated());

        return (!$result)
                ? $this->serverError()
                : $this->success([],
                    'Sales return form deleted successfully.',
                    200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeItems(RemoveItemsRequest $request)
    {
        $result = $this->salesReturn->removeItems(
            $request->pos_sales_return_id,
            $request->product_ids
        );

        return (!$result)
                ? $this->serverError()
                : $this->success([],
                    'Items removed successfully.',
                    200);
    }
}
