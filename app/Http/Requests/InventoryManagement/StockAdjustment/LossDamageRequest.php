<?php

namespace App\Http\Requests\InventoryManagement\StockAdjustment;

use App\Http\Requests\BaseRequest;

class LossDamageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => ['required', 'string', 'in:Loss,Damage'],
            'stockAdjustmentDetails' => ['required', 'array', 'min:1'],
            'stockAdjustmentDetails.*.stock_id' => ['required', 'integer', 'distinct', 'exists:stocks,id'],
            'stockAdjustmentDetails.*.removed_stock' => ['required', 'integer', 'min:1'],
            'stockAdjustmentDetails.*.stock_after' => ['required', 'integer', 'min:0'],
            'stockAdjustmentDetails' => ['required', 'array', 'min:1']
        ];
    }

    public function attributes()
    {
        return [
            'stockAdjustmentDetails' => 'items'
        ];
    }

    public function messages()
    {
        return [
            'stockAdjustmentDetails.required' => 'Please add at least one item.'
        ];
    }
}
