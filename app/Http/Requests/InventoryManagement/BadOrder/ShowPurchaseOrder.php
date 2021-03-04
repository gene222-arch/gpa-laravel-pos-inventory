<?php

namespace App\Http\Requests\InventoryManagement\BadOrder;

use App\Http\Requests\BaseRequest;

class ShowPurchaseOrder extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purchase_order_id' => ['required', 'integer', 'exists:purchase_order,id'],
        ];
    }
}
