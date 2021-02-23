<?php

namespace App\Http\Requests\Pos;

use App\Http\Requests\BaseRequest;

class IndexFilterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filters.*' => ['required', 'string'],
        ];
    }
}
