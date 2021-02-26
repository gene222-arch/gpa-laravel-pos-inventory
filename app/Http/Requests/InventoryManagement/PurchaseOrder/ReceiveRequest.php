<?php

namespace App\Http\Requests\InventoryManagement\PurchaseOrder;

use App\Http\Requests\BaseRequest;

class ReceiveRequest extends BaseRequest
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
            'items_received_quantity.*.purchase_order_details_id' => ['required', 'integer', 'distinct', 'exists:purchase_order_details,id'],
            'items_received_quantities.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items_received_quantities.*.received_quantity' => ['required', 'integer'],
        ];
    }
}
