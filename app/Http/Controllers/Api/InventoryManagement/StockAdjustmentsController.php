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
        $this->middleware(['auth:api', 'role:admin|manager']);
    }


    /**
     * Get resource list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', $this->stockAdjustment);

        return $this->success($this->stockAdjustment->getStockAdjustments(), 'Success');
    }


    /**
     *
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $this->authorize('view', $this->stockAdjustment);

        $data = $this->stockAdjustment->getStockAdjustment(
            $request->stock_adjustment_id
        );

        return $this->success($data, 'Success');
    }


    public function showStockToAdjust(ShowStockToAdjust $request)
    {
        $this->authorize('view', $this->stockAdjustment);

        return $this->success($this->stockAdjustment->getStockToAdjust(
            $request->product_id
        ));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', $this->stockAdjustment);

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
