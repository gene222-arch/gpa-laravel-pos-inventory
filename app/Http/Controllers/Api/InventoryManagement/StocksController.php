<?php

namespace App\Http\Controllers\Api\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryManagement\StockAdjustmentRequest;
use App\Models\Stock;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class StocksController extends Controller
{
    use ApiResponser;

    protected $stock;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StockAdjustmentRequest $request)
    {
        $this->authorize('update', $this->stock);

        $result = $this->stock->adjustStocks(
            $request->reason,
            $request->stockAdjustmentDetails
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success',
                201);
    }

}
