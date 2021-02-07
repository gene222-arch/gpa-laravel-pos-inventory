<?php

namespace App\Http\Requests\Imports;

use App\Http\Requests\BaseRequest;

class ImportProductRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file.*' => ['required', 'file', 'mimes:csv,xlsx']
        ];
    }
}
