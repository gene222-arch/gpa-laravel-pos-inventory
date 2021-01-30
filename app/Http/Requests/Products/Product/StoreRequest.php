<?php

namespace App\Http\Requests\Products\Product;

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
        return array_merge($this->productRules(), $this->stockRules());
    }


    public function productRules()
    {
        return [
            'product.sku' => ['required', 'alpha_num', 'min:8', 'max:13', 'unique:products,sku'],
            'product.barcode' => ['required', 'alpha_num', 'min:8', 'max:13', 'unique:products,barcode'],
            'product.name' => ['required', 'string', 'unique:products,name'],
            'product.image' => ['image', 'mimes:jpeg,png', 'max:2048', 'nullable'],
            'product.category' => ['required', 'integer', 'exists:categories,id'],
            'product.sold_by' => ['required', 'in:each,weight/volume'],
            'product.price' => ['numeric', 'nullable'],
            'product.cost' => ['required', 'numeric'],
        ];
    }


    public function stockRules()
    {
        return [
            'stock.supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'stock.in_stock' => ['required', 'integer', 'min:0'],
            'stock.stock_in' => ['required', 'integer', 'min:0'],
            'stock.stock_out' => ['required', 'integer', 'min:0'],
            'stock.minimum_reorder_level' => ['required', 'integer', 'min:1'],
            'stock.default_purchase_costs' => ['required', 'numeric', 'min:0'],
        ];
    }

}
