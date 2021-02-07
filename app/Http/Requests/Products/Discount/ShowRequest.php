<?php

namespace App\Http\Requests\Products\Discount;

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
            'discount_id' => ['required', 'integer', 'exists:discounts,id'],
        ];
    }
}
