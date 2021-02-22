<?php

namespace App\Http\Requests\InventoryManagement\StockAdjustment;

use App\Http\Requests\BaseRequest;

class ShowStockToAdjust extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id']
        ];
    }
}
