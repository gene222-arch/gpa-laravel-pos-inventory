<?php

namespace App\Http\Controllers\Api\SalesReturn;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesReturn\DeleteRequest;
use App\Http\Requests\SalesReturn\RemoveItemsRequest;
use App\Http\Requests\SalesReturn\ShowRequest;
use App\Http\Requests\SalesReturn\StoreRequest;
use App\Http\Requests\SalesReturn\UpdateRequest;
use App\Models\SalesReturn;
use App\Traits\ApiResponser;


class SalesReturnController extends Controller
{
    use ApiResponser;

    protected $salesReturn;

    public function __construct(SalesReturn $salesReturn)
    {
        $this->salesReturn = $salesReturn;
        $this->middleware(['auth:api', 'role:admin|manager']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', $this->salesReturn);

        return $this->success($this->salesReturn->loadSalesReturns());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', $this->salesReturn);

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
        $this->authorize('view', $this->salesReturn);

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
        $this->authorize('update', $this->salesReturn);

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
        $this->authorize('delete', $this->salesReturn);

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
        $this->authorize('removeItems', $this->salesReturn);

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
