<?php

namespace App\Http\Requests\PDFRequest;

use App\Http\Requests\BaseRequest;

class GeneratePaymentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_id' => ['required', 'integer', 'exists:payments,id']
        ];
    }
}
