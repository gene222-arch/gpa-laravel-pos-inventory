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
use Illuminate\Http\Request;

class SalesReturnController extends Controller
{
    use ApiResponser;

    protected $salesReturn;

    public function __construct(SalesReturn $salesReturn)
    {
        $this->salesReturn = $salesReturn;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', $this->salesReturn);

        return (!true)
            ? $this->serverError()
            : $this->success('Success');
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

        $isSalesReturnCreated = $this->salesReturn->createRequestForm(
            $request->invoice_id,
            $request->salesReturnDetails
        );

        return (!$isSalesReturnCreated)
                ? $this->serverError()
                : $this->success([],
                    'Success',
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

        return (!true)
                ? $this->serverError()
                : $this->success([],
                    'Success',
                    201);
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

        $isSalesReturnUpdated = $this->salesReturn->updateRequestForm(
            $request->sales_return_id,
            $request->invoice_id,
            $request->salesReturnDetails
        );

        return (!$isSalesReturnUpdated)
                ? $this->serverError()
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

        $isSalesReturnDeleted = $this->salesReturn->deleteMany($request->validated());

        return (!$isSalesReturnDeleted)
                ? $this->serverError()
                : $this->success([],
                    'Success',
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

        $isItemsRemoved = $this->salesReturn->removeItems(
            $request->sales_return_id,
            $request->product_ids
        );

        return (!$isItemsRemoved)
                ? $this->serverError()
                : $this->success([],
                    'Success',
                    200);
    }
}
