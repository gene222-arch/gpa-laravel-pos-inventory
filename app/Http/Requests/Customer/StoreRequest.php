<?php

namespace App\Http\Requests\Customer;

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
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:customers,email', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:11', 'max:15', 'unique:customers,phone'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'province' => ['required', 'string'],
            'postal_code' => ['required', 'string', 'min:4', 'max:5'],
            'country' => ['required', 'string'],
        ];
    }
}
