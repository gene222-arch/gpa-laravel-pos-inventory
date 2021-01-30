<?php

namespace App\Http\Requests\Pos;

use App\Http\Requests\BaseRequest;

class RemoveItemRequest extends BaseRequest
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
            'product_id' => ['required', 'integer', 'exists:products,id']
        ];
    }
}
