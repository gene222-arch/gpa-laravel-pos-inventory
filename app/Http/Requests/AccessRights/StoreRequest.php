<?php

namespace App\Http\Requests\AccessRights;

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
            'role' => ['required', 'string', 'unique:roles,name'],
            'back_office' => ['required', 'boolean'],
            'pos' => ['required', 'boolean'],
            'permissions.*' => ['string', 'exists:permissions,name']
        ];
    }
}
