<?php

namespace App\Http\Requests\InventoryManagement\PurchaseOrder;

use App\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purchase_order_id' => ['required', 'integer', 'exists:purchase_order,id'],
            'do_filter' => ['nullable'],
            'table_to_filter' => ['nullable', 'string'],
            'filter_by' => ['nullable', 'string'],
            'operator' => ['nullable', 'string'],
            'filter' => ['nullable', 'alpha_num']
        ];
    }
}
