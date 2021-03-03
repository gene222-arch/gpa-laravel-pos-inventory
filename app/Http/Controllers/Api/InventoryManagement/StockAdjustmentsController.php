<?php

namespace App\Http\Controllers\Api\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryManagement\StockAdjustment\ShowRequest;
use App\Http\Requests\InventoryManagement\StockAdjustment\ShowStockToAdjust;
use App\Http\Requests\InventoryManagement\StockAdjustment\StoreRequest;
use App\Models\StockAdjustment;
use App\Traits\ApiResponser;


class StockAdjustmentsController extends Controller
{
    use ApiResponser;

    protected $stockAdjustment;

    public function __construct(StockAdjustment $stockAdjustment)
    {
        $this->stockAdjustment = $stockAdjustment;
        $this->middleware(['auth:api', 'Manage Stock Adjustments']);
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
     *
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $result = $this->stockAdjustment->adjustStocks(
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
