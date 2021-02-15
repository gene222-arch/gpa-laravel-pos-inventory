<?php

namespace App\Http\Requests\AccessRights;

use App\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id' => ['required', 'integer', 'exists:roles,id']
        ];
    }
}
