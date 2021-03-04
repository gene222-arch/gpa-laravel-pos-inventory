<?php

namespace App\Http\Controllers\Api\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryManagement\StockAdjustment\InventoryCountRequest;
use App\Http\Requests\InventoryManagement\StockAdjustment\LossDamageRequest;
use App\Http\Requests\InventoryManagement\StockAdjustment\ReceiveItemsRequest;
use App\Http\Requests\InventoryManagement\StockAdjustment\ShowRequest;
use App\Http\Requests\InventoryManagement\StockAdjustment\ShowStockToAdjust;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Traits\ApiResponser;


class StockAdjustmentsController extends Controller
{
    use ApiResponser;

    protected $stockAdjustment;

    public function __construct(StockAdjustment $stockAdjustment)
    {
        $this->stockAdjustment = $stockAdjustment;
        $this->middleware(['auth:api', 'permission:Manage Stock Adjustments']);
    }


    /**
     * Get resource list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->stockAdjustment->getStockAdjustments();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($this->stockAdjustment->getStockAdjustments(), 'Success');
    }


    /**
     * * Get resources from products and stocks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexProducts()
    {
        $result = (new Product())->getAll();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Fetched Successfully');
    }

    /**
     * 
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $result = $this->stockAdjustment->getStockAdjustment(
            $request->stock_adjustment_id
        );

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }



    /**
     *
     * @param ShowStockToAdjust $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStockToAdjust(ShowStockToAdjust $request)
    {
        $result = $this->stockAdjustment->getStockToAdjust(
            $request->product_id
        );

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ReceiveItemsRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeReceivedItems(ReceiveItemsRequest $request)
    {
        $result = $this->stockAdjustment->receiveItems(
            $request->reason,
            $request->stockAdjustmentDetails
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Stock adjusted successfully.',
                201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\InventoryCountRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeInventoryCount(InventoryCountRequest $request)
    {
        $result = $this->stockAdjustment->inventoryCount(
            $request->reason,
            $request->stockAdjustmentDetails
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Stock adjusted successfully.',
                201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\LossDamageRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLossDamage(LossDamageRequest $request)
    {
        $result = $this->stockAdjustment->lossOrDamage(
            $request->reason,
            $request->stockAdjustmentDetails
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Stock adjusted successfully.',
                201);
    }


}
