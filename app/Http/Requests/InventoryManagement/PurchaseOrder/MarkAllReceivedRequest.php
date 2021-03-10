<?php

namespace App\Http\Requests\InventoryManagement\PurchaseOrder;

use App\Http\Requests\BaseRequest;

class MarkAllReceivedRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'purchase_order_id' => ['required', 'integer', 'exists:purchase_order,id'],
            'items_received_quantities' => ['required']
        ];
    }
}
