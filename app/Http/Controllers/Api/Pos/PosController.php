<?php

namespace App\Http\Controllers\Api\Pos;

use App\Models\Pos;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\ShowRequest;
use App\Http\Requests\Pos\StoreRequest;
use App\Http\Requests\Pos\UpdateRequest;
use App\Http\Requests\Pos\RemoveItemRequest;
use App\Http\Requests\Pos\CancelOrdersRequest;
use App\Http\Requests\Pos\ProcessPaymentRequest;
use App\Http\Requests\Pos\DecrementItemQtyRequest;
use App\Http\Requests\Pos\IncrementItemQtyRequest;

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
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request)
    {
        $this->authorize('view', $this->pos);

        $customerPosDetails = $this->pos->findCustomerPosDetails($request->customer_id);

        return (!$customerPosDetails)
                ? $this->serverError()
                : $this->success($customerPosDetails,
                'Success');
    }



    /**
     * Undocumented function
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\Response
     */
    public function showAmountToPay(ShowRequest $request)
    {
        $this->authorize('view', $this->pos);

        $amountToPay = $this->pos->getCustomerAmountToPay($request->customer_id);

        return $amountToPay;

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
     * @param ProcessPaymentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function processPayment(ProcessPaymentRequest $request)
    {
        $this->authorize('processPayment', $this->pos);

        $isPaymentProcessed = $this->pos->processPayment(
            $request->customer_id,
            $request->payment_method,
            $request->cash,
            $request->shipping_fee,
            $request->numberOfDays
        );

        return (!$isPaymentProcessed)
                ? $this->error('Cannot Process a payment, customer has yet to order')
                : $this->success([],
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
        $this->authorize('create', $this->pos);

        $isOrderAddedToCart = $this->pos->addToCart(
            $request->customer_id,
            $request->product_id,
            $request->product_barcode
        );

        return (!$isOrderAddedToCart)
            ? $this->serverError()
            : $this->success([],
                'Success',
                201);
    }


   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $this->authorize('update', $this->pos);

        $isOrderQtyUpdated = $this->pos->updateOrderQty(
            $request->customer_id,
            $request->product_id,
            $request->quantity
        );

        return (!$isOrderQtyUpdated)
            ? $this->serverError()
            : $this->success([],
                'Success',
                201);
    }



    /**
     * Undocumented function
     *
     * @param IncrementItemQtyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function incrementQuantity(IncrementItemQtyRequest $request)
    {
        $this->authorize('update', $this->pos);

        $isOrderQtyUpdated = $this->pos->incrementItemQuantity(
            $request->customer_id,
            $request->product_id
        );

        return (!$isOrderQtyUpdated)
            ? $this->error('Reached maximum stock level')
            : $this->success([],
                'Success',
                201);
    }


    /**
     * Undocumented function
     *
     * @param IncrementItemQtyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function decrementQuantity(DecrementItemQtyRequest $request)
    {
        $this->authorize('update', $this->pos);

        $isOrderQtyUpdated = $this->pos->decrementItemQuantity(
            $request->customer_id,
            $request->product_id
        );

        return (!$isOrderQtyUpdated)
            ? $this->error('Order quantity must be at least 1')
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
    public function cancelOrders(CancelOrdersRequest $request)
    {
        $this->authorize('cancelOrders', $this->pos);

        $isOrderCancelled = $this->pos->cancelOrders(
            $request->customer_id,
        );

        return (!$isOrderCancelled)
            ? $this->error('Cannot Process a cancellation of order, customer has yet to order')
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
    public function removeItems(RemoveItemRequest $request)
    {
        $this->authorize('removeItems', $this->pos);

        $isOrderedItemRemoved = $this->pos->removeItem(
            $request->customer_id,
            $request->product_id
        );

        return (!$isOrderedItemRemoved)
            ? $this->error('The customer has yet to order')
            : $this->success([],
                'Success',
                200);
    }
}
