<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\DeleteRequest;
use App\Http\Requests\Customer\ShowRequest;
use App\Http\Requests\Customer\StoreRequest;
use App\Http\Requests\Customer\UpdateRequest;
use App\Traits\ApiResponser;

class CustomersController extends Controller
{

    use ApiResponser;

    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', $this->customer);

        return $this->success($this->customer->loadCustomers(),
        'Success');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', $this->customer);

        $isCustomerCreated = $this->customer->insertTs($request->validated());

        return (!$isCustomerCreated)
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
        $this->authorize('view', $this->customer);

        return $this->success($this->customer->find($request->customer_id),
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
        $this->authorize('update', $this->customer);

        $isCustomerUpdated = $this->customer
                                    ->where('id', '=', $request->customer_id)
                                    ->update($request->customer_data);

        return (!$isCustomerUpdated)
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
        $this->authorize('delete', $this->customer);

        $isCustomerDeleted = $this->customer
                                    ->whereIn('id', $request->customer_ids)
                                    ->delete();

        return (!$isCustomerDeleted)
            ? $this->serverError()
            : $this->success([],
            'Success',
            201);
    }

}
