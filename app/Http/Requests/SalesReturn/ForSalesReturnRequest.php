<?php

namespace App\Http\Requests\SalesReturn;

use App\Http\Requests\BaseRequest;

class ForSalesReturnRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pos_id' => ['required', 'integer', 'exists:pos,id']
        ];
    }
}
