<?php

namespace App\Http\Requests\Employee;

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
            'employee_ids.*' => ['required', 'integer', 'distinct', 'exists:employees,id', 'not_in:1']
        ];
    }

    public function messages()
    {
        return [
            'employee_ids.*.not_in' => ['Super admin employee can not be deleted.']
        ];
    }
}
