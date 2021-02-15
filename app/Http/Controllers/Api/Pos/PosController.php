<?php

namespace App\Http\Controllers\Api\Pos;

use App\Models\Pos;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\AddDiscountQuantity;
use App\Http\Requests\Pos\ShowRequest;
use App\Http\Requests\Pos\StoreRequest;
use App\Http\Requests\Pos\UpdateRequest;
use App\Http\Requests\Pos\RemoveItemRequest;
use App\Http\Requests\Pos\CancelOrdersRequest;
use App\Http\Requests\Pos\AssignDiscountRequest;
use App\Http\Requests\Pos\ProcessPaymentRequest;
use App\Http\Requests\Pos\RemoveDiscountRequest;
use App\Http\Requests\Pos\DecrementItemQtyRequest;
use App\Http\Requests\Pos\IncrementItemQtyRequest;
use App\Http\Requests\Pos\AssignDiscountToAllRequest;
use App\Http\Requests\Pos\RemoveDiscountToAllRequest;

class PosController extends Controller
{
    use ApiResponser;


    protected $pos;

    public function __construct(Pos $pos)
    {
        $this->pos = $pos;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', $this->pos);

        $orderLists = $this->pos->loadOrders();

        return (!$orderLists)
            ? $this->serverError()
            : $this->success($orderLists,
            'Success');
    }


    /**
     * Undocumented function
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAmountToPay(ShowRequest $request)
    {
        $this->authorize('view', $this->pos);

        $amountToPay = $this->pos->getCustomerAmountToPay($request->customer_id);

        return (!$amountToPay)
                ? $this->serverError()
                : $this->success([
                    'amount' => $amountToPay
                ],
                'Success');
    }


    /**
     * Undocumented function
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function showCartDetails(ShowRequest $request)
   {
       $this->authorize('view', $this->pos);

       $result = $this->pos->getCustomerCartDetails($request->customer_id);

       return (!$result)
               ? $this->serverError()
               : $this->success($result,
               'Success');
   }


    /**
     * Undocumented function
     *
     * @param ProcessPaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(ProcessPaymentRequest $request)
    {
        $this->authorize('processPayment', $this->pos);

        $result = $this->pos->processPayment(
            $request->customer_id,
            $request->payment_method,
            $request->cash,
            $request->should_mail,
            $request->number_of_days,
            $request->customer_email,
            $request->customer_name
        );

        return ($result !== true)
                ? $this->error($result)
                : $this->success([],
                'Success',
                201);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', $this->pos);

        $result = $this->pos->addToCart(
            $request->customer_id,
            $request->product_id,
            $request->product_barcode
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success',
                201);
    }


   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProductQty(UpdateRequest $request)
    {
        $this->authorize('update', $this->pos);

        $result = $this->pos->updateOrderQty(
            $request->customer_id,
            $request->product_id,
            $request->quantity
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success',
                201);
    }



    /**
     * Undocumented function
     *
     * @param IncrementItemQtyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function incrementQuantity(IncrementItemQtyRequest $request)
    {
        $this->authorize('update', $this->pos);

        $result = $this->pos->incrementItemQuantity(
            $request->customer_id,
            $request->product_id
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success',
                201);
    }


    /**
     * Undocumented function
     *
     * @param IncrementItemQtyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function decrementQuantity(DecrementItemQtyRequest $request)
    {
        $this->authorize('update', $this->pos);

        $result = $this->pos->decrementItemQuantity(
            $request->customer_id,
            $request->product_id
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success',
                201);
    }


    /**
     * Undocumented function
     *
     * @param IncrementItemQtyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignDiscount(AssignDiscountRequest $request)
    {
        $this->authorize('assignDiscount', $this->pos);

        $result = $this->pos->assignDiscountTo(
            $request->customer_id,
            $request->product_id,
            $request->discount_id
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success',
                201);
    }



    /**
     * Undocumented function
     *
     * @param IncrementItemQtyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignDiscountToAll(AssignDiscountToAllRequest $request)
    {
        $this->authorize('assignDiscount', $this->pos);

        $result = $this->pos->assignDiscountToAll(
            $request->customer_id,
            $request->discount_id
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success',
                201);
    }


    /**
     * Undocumented function
     *
     * @param AddDiscountQuantity $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyDiscountAddQuantity(AddDiscountQuantity $request)
    {
        $this->authorize('applyDiscountAddQuantity', $this->pos);

        $result = $this->pos->applyDiscountWithQuantity(
                $request->customer_id,
                $request->product_id,
                $request->discount_id,
                $request->quantity
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelOrders(CancelOrdersRequest $request)
    {
        $this->authorize('cancelOrders', $this->pos);

        $result = $this->pos->cancelOrders(
            $request->customer_id,
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeDiscount(RemoveDiscountRequest $request)
    {
        $this->authorize('removeDiscount', $this->pos);

        $result = $this->pos->removeDiscountTo(
            $request->customer_id,
            $request->product_id
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success',
        );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeDiscountToAll(RemoveDiscountToAllRequest $request)
    {
        $this->authorize('removeDiscount', $this->pos);

        $result = $this->pos->removeDiscountToAll(
            $request->customer_id
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeItems(RemoveItemRequest $request)
    {
        $this->authorize('removeItems', $this->pos);

        $result = $this->pos->removeItem(
            $request->customer_id,
            $request->product_id
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success');
    }
}
