<?php

namespace App\Http\Requests\Products\Product;

use App\Http\Requests\BaseRequest;

class FilterProductsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => ['nullable'],
            'productName' => ['nullable', 'string']
        ];
    }
}
