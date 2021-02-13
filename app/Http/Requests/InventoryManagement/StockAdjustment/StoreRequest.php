<?php

namespace App\Http\Requests\InventoryManagement\StockAdjustment;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => ['required', 'string', 'in:Received items, Inventory count, Loss, Damage'],
            'stockAdjustmentDetails.*.stock_id' => ['required', 'integer', 'distinct', 'exists:stocks,id'],
            'stockAdjustmentDetails.*.added_stock' => ['integer', 'min:0'],
            'stockAdjustmentDetails.*.removed_stock' => ['integer', 'min:0'],
            'stockAdjustmentDetails.*.counted_stock' => ['integer', 'min:0'],
        ];
    }
}
