<?php

namespace App\Http\Requests\InventoryManagement\BadOrder;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bad_order_id' => ['required', 'integer', 'exists:bad_orders,id'],
            'purchase_order_id' => ['required', 'integer', 'exists:purchase_order,id'],

            'badOrderDetails.*.purchase_order_details_id' => ['required', 'integer', 'distinct', 'exists:purchase_order_details,id'],
            'badOrderDetails.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'badOrderDetails.*.defect' => ['required', 'string'],
            'badOrderDetails.*.quantity' => ['required', 'integer'],
            'badOrderDetails.*.price' => ['required', 'integer', 'min:1'],
            'badOrderDetails.*.unit_of_measurement' => ['required', 'string'],
            'badOrderDetails.*.amount' => ['required', 'integer', 'min:1'],
        ];
    }
}
