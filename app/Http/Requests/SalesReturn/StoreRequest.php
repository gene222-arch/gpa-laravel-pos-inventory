<?php

namespace App\Http\Requests\SalesReturn;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'],

            'salesReturnDetails.*.invoice_details_id' => ['required', 'integer', 'exists:invoice_details,id'],
            'salesReturnDetails.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'salesReturnDetails.*.defect' => ['required', 'string'],
            'salesReturnDetails.*.quantity' => ['required', 'integer', 'min:1'],
            'salesReturnDetails.*.price' => ['required', 'integer', 'min:1'],
            'salesReturnDetails.*.amount' => ['required', 'integer', 'min:1'],
            'salesReturnDetails.*.unit_of_measurement' => ['required', 'string'],
        ];
    }
}
