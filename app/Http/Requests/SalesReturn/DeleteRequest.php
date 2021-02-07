<?php

namespace App\Http\Requests\SalesReturn;

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
            'pos_sales_return_ids.*' => ['required', 'integer', 'distinct', 'exists:sales_returns,id'],
        ];
    }
}
