<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;
use App\Models\User;

class UpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = User::where('email', '=', $this->email)->first();

        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:employees,email,' . $this->employee_id, 'unique:users,email,' . $user->id, 'unique:suppliers,email'],
            'phone' => ['required', 'string', 'min:11', 'max:15', 'unique:employees,phone,' . $this->employee_id, 'unique:suppliers,phone'],
            'role' => ['required', 'string', 'exists:roles,name']
        ];
    }
}
