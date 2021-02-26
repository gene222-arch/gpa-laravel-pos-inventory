<?php

namespace App\Http\Requests\Products\Discount;

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
            'name' => ['required', 'string', 'min:3', 'unique:discounts,name'],
            'percentage' => ['required', 'numeric', 'min:1', 'max:100']
        ];
    }
}
