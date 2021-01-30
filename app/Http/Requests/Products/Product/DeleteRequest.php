<?php

namespace App\Http\Requests\Products\Product;

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
            'id.*' => ['required', 'integer', 'distinct', 'exists:products,id']
        ];
    }
}
