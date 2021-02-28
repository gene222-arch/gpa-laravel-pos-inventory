<?php

namespace App\Http\Requests\Pos;

use App\Models\Stock;
use App\Http\Requests\BaseRequest;

class AddDiscountQuantity extends BaseRequest
{
    private int $inStock;


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'hasDiscount' => ['required', 'boolean'],
            'discount_id' => ['nullable', 'integer', 'exists:discounts,id'],
            'quantity' => $this->quantityRules()
        ];
    }


    public function quantityRules()
    {
        $productId = $this->product_id;

        $productInStock = Stock::where('product_id', '=', $productId)->first()->in_stock;

        $this->inStock = $productInStock;

        return ['numeric', 'min:1',  "max:$productInStock"];
    }


    public function messages()
    {
        return [
            'quantity.min' => 'Item quantity must be at least 1',
            'quantity.max' => 'Item stock is not enough, remaining: ' . $this->inStock,
        ];
    }
}
