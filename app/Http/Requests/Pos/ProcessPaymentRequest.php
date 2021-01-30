<?php

namespace App\Http\Requests\Pos;

use App\Http\Requests\BaseRequest;

class ProcessPaymentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'payment_method' => ['required', 'string', 'in:cash,credit,invoice'],
            'cash' => ['nullable', 'integer'],
            'shipping_fee' => ['nullable', 'integer'],
            'numberOfDays' => ['nullable', 'integer']
        ];
    }
}
