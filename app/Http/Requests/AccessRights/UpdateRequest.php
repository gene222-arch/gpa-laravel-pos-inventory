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
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'role_name' => ['required', 'string'],
            'back_office' => ['required', 'boolean'],
            'pos' => ['required', 'boolean'],
            'permissions.*' => ['required', 'string', 'exists:permissions,id']
        ];
    }
}
