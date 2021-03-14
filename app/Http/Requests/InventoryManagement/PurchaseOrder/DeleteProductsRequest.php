<?php

namespace App\Http\Requests\InventoryManagement\PurchaseOrder;

use App\Http\Requests\BaseRequest;
use App\Models\PurchaseOrder;

class DeleteProductsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'remaining_items' => ['numeric', 'min:1'],
            'purchase_order_id' => ['required', 'integer', 'exists:purchase_order,id'],
            'product_ids.*' => ['required', 'integer', 'exists:products,id']
        ];
    }


    public function messages()
    {
        return [
            'remaining_items.min' => 'At least one item is needed in a purchase'
        ];  
    }
}
