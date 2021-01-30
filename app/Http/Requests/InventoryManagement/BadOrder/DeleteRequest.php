<?php

namespace App\Http\Requests\InventoryManagement\BadOrder;

use App\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bad_order_ids.*' => ['required', 'integer', 'distinct', 'exists:bad_orders,id'],
        ];
    }
}
