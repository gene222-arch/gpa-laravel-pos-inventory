<?php

namespace App\Http\Requests\Products\Discount;

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
            'discount_id' => ['required', 'integer', 'exists:discounts,id'],
            'name' => ['required', 'string', 'min:3', 'unique:discounts,name,' . $this->discount_id],
            'percentage' => ['required', 'numeric', 'min:1']
        ];
    }
}
