<?php

namespace App\Http\Requests\Products\Category;
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
            'id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'unique:categories,name,' . $this->id]
        ];
    }
}
