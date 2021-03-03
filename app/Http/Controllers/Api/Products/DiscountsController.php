<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Discount\DeleteRequest;
use App\Http\Requests\Products\Discount\ShowRequest;
use App\Http\Requests\Products\Discount\StoreRequest;
use App\Http\Requests\Products\Discount\UpdateRequest;
use App\Models\Discount;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{
    use ApiResponser;
    protected $discount;

    public function __construct(Discount $discount)
    {
        $this->discount = $discount;
        $this->middleware(['auth:api', 'permission:Manage Discounts']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->discount->latest()->get();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Fetched successfully', 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $isDiscountCreated = $this->discount
            ->createDiscount(
                $request->name,
                $request->percentage
            );

        return (!$isDiscountCreated)
            ? $this->serverError()
            : $this->success([], 'Discount created successfully.', 201);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request)
    {
        $result = $this->discount->find($request->discount_id);

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Fetched successfully', 200);
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
        $isDiscountUpdated = $this->discount
            ->updateDiscount(
                $request->discount_id,
                $request->name,
                $request->percentage
            );

        return (!$isDiscountUpdated)
            ? $this->serverError()
            : $this->success([], 'Discount updated successfully.', 201);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request)
    {
        $isDiscountsDeleted = $this->discount
            ->deleteDiscounts($request->discount_ids);

        return (!$isDiscountsDeleted)
            ? $this->serverError()
            : $this->success([], 'Discount deleted successfully.', 200);
    }
}
