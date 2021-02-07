<?php

namespace App\Http\Requests\SalesReturn;

use App\Http\Requests\BaseRequest;

class RemoveItemsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pos_sales_return_id' => ['required', 'integer', 'exists:sales_returns,id'],
            'product_ids.*' => ['required', 'integer', 'distinct', 'exists:products,id'],
        ];
    }
}
