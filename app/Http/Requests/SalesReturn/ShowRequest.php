<?php

namespace App\Http\Requests\SalesReturn;

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
            'sales_return_id' => ['required', 'integer', 'exists:sales_returns,id'],
        ];
    }
}
