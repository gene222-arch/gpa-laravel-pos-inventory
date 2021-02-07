<?php

namespace App\Http\Requests\Products\Discount;

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
            'discount_ids.*' => ['required', 'integer', 'distinct', 'exists:discounts,id'],
        ];
    }
}
