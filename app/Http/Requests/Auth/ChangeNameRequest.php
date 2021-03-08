<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Rules\Auth\MatchOldPassword;

class ChangeNameRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_name' => ['required', 'string']
        ];
    }


    public function attributes()
    {
        return [
            'new_name' => 'name'
        ];
    }
}
