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
            'employee_ids.*' => ['required', 'integer', 'distinct', 'exists:employees,id']
        ];
    }
}
