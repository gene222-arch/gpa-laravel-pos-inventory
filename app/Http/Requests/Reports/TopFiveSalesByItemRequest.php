<?php

namespace App\Http\Requests\Reports;

use App\Http\Requests\BaseRequest;

class TopFiveSalesByItemRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'year' => ['nullable', 'integer'],
            'monthNumber' => ['nullable', 'integer', 'min:1', 'max:12'],
        ];
    }
}
