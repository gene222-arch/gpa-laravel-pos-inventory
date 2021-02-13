<?php

namespace App\Http\Requests\InventoryManagement\StockAdjustment;

use App\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stock_adjustment_id' => ['required', 'integer', 'exists:stock_adjustments,id']
        ];
    }
}
