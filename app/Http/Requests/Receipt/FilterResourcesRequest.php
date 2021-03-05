<?php

namespace App\Http\Requests\Receipt;

use App\Http\Requests\BaseRequest;

class FilterResourcesRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => ['nullable', 'date']
        ];
    }
}
