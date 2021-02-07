<?php

namespace App\Http\Requests\InventoryManagement\BadOrder;

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
            'purchase_order_id' => ['required', 'integer', 'exists:purchase_order,id', 'unique:bad_orders,purchase_order_id'],
            'badOrderDetails.*.purchase_order_details_id' => ['required', 'integer', 'distinct', 'exists:purchase_order_details,id', 'unique:bad_order_details,purchase_order_details_id'],
            'badOrderDetails.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id', 'unique:bad_order_details,product_id'],
            'badOrderDetails.*.defect' => ['required', 'string'],
            'badOrderDetails.*.quantity' => ['required', 'integer'],
            'badOrderDetails.*.price' => ['required', 'integer', 'min:1'],
            'badOrderDetails.*.unit_of_measurement' => ['required', 'string'],
            'badOrderDetails.*.amount' => ['required', 'integer', 'min:1'],
        ];
    }

}
