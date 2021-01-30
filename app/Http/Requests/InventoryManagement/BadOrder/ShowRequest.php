<?php

namespace App\Http\Requests\InventoryManagement\BadOrder;

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
            'bad_order_id' => ['required', 'integer', 'exists:bad_orders,id'],
        ];
    }
}
