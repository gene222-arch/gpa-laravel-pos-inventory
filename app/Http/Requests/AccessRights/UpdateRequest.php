<?php

namespace App\Http\Requests\AccessRights;

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
            'access_right_id' => ['required', 'integer', 'exists:access_rights,id'],
            'role' => ['required', 'string'],
            'back_office' => ['required', 'boolean'],
            'pos' => ['required', 'boolean'],
            'permissions.*' => ['required', 'string', 'exists:permissions,name']
        ];
    }
}
