<?php

namespace App\Http\Requests\InventoryManagement\PurchaseOrder;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
        $this->purchaseOrderRules(),
        $this->purchaseOrderDetailsRules()
        );
    }


    /**
     * `purchase_order` table field list validation
     *
     * @return array
     */
    private function purchaseOrderRules(): array
    {
        return [
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'purchase_order_date' => ['required', 'date'],
            'expected_delivery_date' => ['required', 'date'],
        ];
    }


    /**
     * `purchase_order_details` table field list validation
     *
     * @return array
     */
    private function purchaseOrderDetailsRules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.ordered_quantity' => ['required', 'integer', 'min:1'],
            'items.*.purchase_cost' => ['required', 'numeric', 'min:1'],
        ];
    }


    public function messages()
    {
        return [
            'items.required' => ['Please add at least one item to the purchase order']
        ];
    }


    public function attributes(): array
    {
        return [
            'items.*.ordered_quantity' => 'quantity',
            'items.*.purchase_cost' => 'cost',
        ];
    }

}
