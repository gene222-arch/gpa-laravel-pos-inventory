<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
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
            'customer_data.name' => ['required', 'string'],
            'customer_data.email' => ['required', 'email', 'unique:customers,email,' . $this->customer_id, 'unique:users,email'],
            'customer_data.phone' => ['required', 'string', 'min:11', 'max:15', 'unique:customers,phone,' . $this->customer_id],
            'customer_data.address' => ['required', 'string'],
            'customer_data.city' => ['required', 'string'],
            'customer_data.province' => ['required', 'string'],
            'customer_data.postal_code' => ['required', 'string', 'min:4', 'max:5'],
            'customer_data.country' => ['required', 'string'],
        ];
    }
}
