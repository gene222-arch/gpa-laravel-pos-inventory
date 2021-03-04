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
            'pos_id' => ['required', 'integer', 'exists:pos,id'],
            'posSalesReturnDetails' => ['required', 'array', 'min:1'],
            'posSalesReturnDetails.*.pos_details_id' => ['required', 'integer', 'exists:pos_details,id'],
            'posSalesReturnDetails.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'posSalesReturnDetails.*.defect' => ['required', 'string'],
            'posSalesReturnDetails.*.quantity' => ['required', 'integer', 'min:1'],
            'posSalesReturnDetails.*.price' => ['required', 'numeric', 'min:1'],
            'posSalesReturnDetails.*.unit_of_measurement' => ['required', 'string'],
            'posSalesReturnDetails.*.sub_total' => ['required', 'numeric', 'min:1'],
            'posSalesReturnDetails.*.discount' => ['required', 'numeric', 'min:0'],
            'posSalesReturnDetails.*.tax' => ['required', 'numeric', 'min:1'],
            'posSalesReturnDetails.*.total' => ['required', 'numeric', 'min:1'],

        ];
    }

    public function messages()
    {
        return [
            'posSalesReturnDetails.required' => ['Please add at least one item.']
        ];
    }

}



