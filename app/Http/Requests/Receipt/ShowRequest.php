<?php

namespace App\Http\Requests\Receipt;

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
            'receipt_id' => ['required', 'integer', 'exists:sales,id']
        ];
    }
}
