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
        return $this->should_mail && $this->customer_id === 1
            ? $this->walkinCustomerRules()
            : $this->customerRules();
    }


    public function walkinCustomerRules()
    {
        return  [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'customer_email' => ['required', 'email'],
            'customer_name' => ['required', 'string'],
            'payment_method' => ['required', 'string', 'in:cash,credit,invoice'],
            'should_mail' => ['required', 'boolean'],
            'cash' => ['nullable', 'numeric'],
            'number_of_days' => ['nullable', 'integer'],
        ];
    }

    public function customerRules()
    {
        return  [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'customer_email' => ['nullable', 'email'],
            'customer_name' => ['nullable', 'string'],
            'payment_method' => ['required', 'string', 'in:cash,credit,invoice'],
            'should_mail' => ['required', 'boolean'],
            'cash' => ['nullable', 'numeric'],
            'number_of_days' => ['nullable', 'integer'],
        ];
    }

}
