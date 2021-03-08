<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Rules\Auth\MatchOldPassword;
use Illuminate\Support\Facades\Auth;

class ChangeEmailRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_email' => ['required', 'string', 'unique:users,email,' . Auth::user()->id, 'unique:suppliers,email', 'unique:customers,email'],
            'new_email_confirmation' => ['required', 'string', 'same:new_email']
        ];
    }


    public function attributes()
    {
        return [
            'new_email' => 'email',
            'new_email_confirmation' => 'email confirmation'
        ];
    }
}
