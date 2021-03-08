<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Rules\Auth\MatchOldPassword;

class ChangePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_password' => ['required', 'string'],
            'new_password_confirmation' => ['same:new_password']
        ];
    }

    public function attributes()
    {
        return [
            'new_password' => 'password',
            'new_password_confirmation' => 'password confirmation'
        ];
    }
}
