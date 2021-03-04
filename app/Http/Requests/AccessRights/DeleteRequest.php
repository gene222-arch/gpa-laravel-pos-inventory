<?php

namespace App\Http\Requests\AccessRights;

use App\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'access_right_ids.*' => ['required', 'integer', 'distinct', 'exists:access_rights,id']
        ];
    }
}
