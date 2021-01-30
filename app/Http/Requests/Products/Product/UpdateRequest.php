<?php

namespace App\Http\Requests\Products\Product;

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
        return array_merge($this->productRules(), $this->stockRules());
    }


    public function productRules()
    {
        return [
            'product.product_id' => ['required', 'integer', 'exists:products,id'],
            'product.data.sku' => ['required', 'alpha_num', 'min:8', 'max:13', 'unique:products,sku,' . $this->id],
            'product.data.barcode' => ['required', 'alpha_num', 'min:8', 'max:13', 'unique:products,barcode,' . $this->id],
            'product.data.name' => ['required', 'string'],
            'product.data.image' => ['image', 'mimes:jpeg,png', 'max:2048', 'nullable'],
            'product.data.category' => ['required', 'integer', 'exists:categories,id'],
            'product.data.sold_by' => ['required', 'in:each,weight/volume'],
            'product.data.price' => ['numeric', 'nullable'],
            'product.data.cost' => ['required', 'numeric'],
        ];
    }


    public function stockRules()
    {
        return [
            'stock.data.supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'stock.data.in_stock' => ['required', 'integer', 'min:0'],
            'stock.data.stock_in' => ['required', 'integer', 'min:0'],
            'stock.data.stock_out' => ['required', 'integer', 'min:0'],
            'stock.data.minimum_reorder_level' => ['required', 'integer', 'min:1'],
            'stock.data.default_purchase_costs' => ['required', 'numeric', 'min:0'],
        ];
    }
}
