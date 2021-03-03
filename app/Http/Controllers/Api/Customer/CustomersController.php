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
        $this->middleware(['auth:api', 'permission:Manage Customers']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->customer->loadCustomers();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }


        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexForPos()
    {
        $result = $this->customer->loadCustomerForPos();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }




        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexWithOrders()
    {
        $result = $this->customer->getCustomersWithOrders();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $isCustomerCreated = $this->customer->insertTs($request->validated());

        return (!$isCustomerCreated)
            ? $this->serverError()
            : $this->success([],
            'Customer created successfully.',
            201);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $customer = $this->customer->find($request->customer_id);

        return !$customer
            ? $this->success([], 'No Content', 204)
            : $this->success($customer, 'Success');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $isCustomerUpdated = $this->customer
                                    ->where('id', '=', $request->customer_id)
                                    ->update($request->customer_data);

        return (!$isCustomerUpdated)
            ? $this->serverError()
            : $this->success([],
            'Customer updated successfully.',
            201);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteRequest $request)
    {
        $isCustomerDeleted = $this->customer
                                    ->whereIn('id', $request->customer_ids)
                                    ->delete();

        return (!$isCustomerDeleted)
            ? $this->serverError()
            : $this->success([],
            'Customer deleted successfully.',
            201);
    }

}
