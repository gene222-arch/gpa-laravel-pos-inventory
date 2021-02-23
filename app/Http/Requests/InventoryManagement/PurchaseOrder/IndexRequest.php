<?php

namespace App\Http\Requests\InventoryManagement\PurchaseOrder;

use App\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filterBy' => ['nullable', 'string'],
            'operator' => ['nullable', 'string'],
            'filters.*' => ['nullable', 'string'],
        ];
    }
}
