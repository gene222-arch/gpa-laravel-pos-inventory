<?php

namespace App\Http\Requests\Dashboard;

use App\Http\Requests\BaseRequest;

class DashboardDataRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'year' => ['nullable', 'integer', 'min:1970']
        ];
    }
}
