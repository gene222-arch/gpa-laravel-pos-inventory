<?php

namespace App\Http\Requests\Reports;

use App\Http\Requests\BaseRequest;

class SalesByPaymentTypeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'startDate' => ['nullable', 'date'],
            'endDate' => ['nullable', 'date'],
        ];
    }
}
