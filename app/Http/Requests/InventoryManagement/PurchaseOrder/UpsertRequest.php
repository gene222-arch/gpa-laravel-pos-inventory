<?php

namespace App\Http\Requests\InventoryManagement\PurchaseOrder;

use App\Http\Requests\BaseRequest;

class UpsertRequest extends BaseRequest
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
     * `purchase_order` table rules
     *
     * @return array
     */
    private function purchaseOrderRules(): array
    {
        return [
            'purchase_order_id' => ['required', 'integer', 'exists:purchase_order,id'],
            'expected_delivery_date' => ['required', 'date'],
        ];
    }



    /**
     * `purchase_order_details` table rules
     *
     * @return array
     */

    private function purchaseOrderDetailsRules(): array
    {
        return [
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.ordered_quantity' => ['required', 'integer', 'min:1'],
            'items.*.remaining_ordered_quantity' => ['required', 'integer', 'min:1'],
            'items.*.purchase_cost' => ['required', 'min:0'],
            'items.*.amount' => ['required', 'integer', 'min:1'],
        ];
    }

}
