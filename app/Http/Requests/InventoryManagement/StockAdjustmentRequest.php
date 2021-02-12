<?php

namespace App\Http\Requests\InventoryManagement;

use App\Http\Requests\BaseRequest;

class StockAdjustmentRequest extends BaseRequest
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
            'stockAdjustmentDetails.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'stockAdjustmentDetails.*.added_stock' => ['required', 'integer', 'min:1'],
            'stockAdjustmentDetails.*.updated_cost' => ['required', 'numeric', 'min:1'],
        ];
    }
}
