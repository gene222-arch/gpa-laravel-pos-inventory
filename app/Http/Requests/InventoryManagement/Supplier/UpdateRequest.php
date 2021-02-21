<?php

namespace App\Http\Requests\InventoryManagement\Supplier;

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
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'name' => ['required', 'string'],
            'contact' => ['required', 'string', 'min:10', 'max:15'],
            'email' => ['required', 'email', 'string', 'unique:suppliers,email,' . $this->id],
            'phone' => ['required', 'string', 'min:10', 'max:15'],
            'website' => ['required', 'url', 'string'],
            'main_address' => ['required', 'string'],
            'optional_address' => ['nullable', 'string'],
            'city' => ['required', 'string'],
            'zipcode' => ['required', 'string', 'min:4', 'max:5'],
            'country' => ['required', 'string'],
            'province' => ['required', 'string'],
        ];
    }
}
