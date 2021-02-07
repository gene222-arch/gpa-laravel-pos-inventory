<?php

namespace App\Http\Requests\Pos;

use App\Http\Requests\BaseRequest;

class AssignDiscountToAllRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'discount_id' => ['required', 'integer', 'exists:discounts,id']
        ];
    }
}
