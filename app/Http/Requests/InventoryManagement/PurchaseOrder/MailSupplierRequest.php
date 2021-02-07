<?php

namespace App\Http\Requests\InventoryManagement\PurchaseOrder;

use App\Http\Requests\BaseRequest;

class MailSupplierRequest extends BaseRequest
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
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'subject' => ['required', 'string'],
            'note' => ['required', 'string']
        ];
    }
}
