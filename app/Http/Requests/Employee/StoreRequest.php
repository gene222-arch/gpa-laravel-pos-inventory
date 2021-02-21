<?php

namespace App\Http\Requests\Employee;

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
            'email' => ['required', 'email', 'unique:employees,email', 'unique:users,email', 'unique:suppliers,email'],
            'phone' => ['required', 'string', 'min:11', 'max:15','unique:employees,phone',  'unique:suppliers,phone'],
            'role' => ['required', 'string', 'exists:roles,name']
        ];
    }
}
