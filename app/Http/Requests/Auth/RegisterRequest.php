<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'exists:employees,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }


    public function messages()
    {
        return [
            'email.exists' => ['The selected email is invalid. Only registered employees can register.  ']
        ];
    }

}
