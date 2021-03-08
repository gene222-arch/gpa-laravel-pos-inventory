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
            'access_right_ids.*' => ['required', 'integer', 'distinct', 'exists:access_rights,id', 'not_in:1']
        ];
    }

    public function messages()
    {
        return [
            'access_right_ids.*.not_in' => ['Super admin access right can not be deleted.']
        ];
    }

}
