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
            'product.data.sku' => ['required', 'alpha_num', 'min:8', 'max:13', 'unique:products,sku,' . $this->product['product_id']],
            'product.data.barcode' => ['required', 'alpha_num', 'min:8', 'max:13', 'unique:products,barcode,' . $this->product['product_id']],
            'product.data.name' => ['required', 'string'],
            'product.data.image' => ['nullable', 'string'],
            'product.data.category' => ['required', 'integer', 'exists:categories,id'],
            'product.data.sold_by' => ['required', 'in:each,weight/volume'],
            'product.data.price' => ['nullable', 'numeric',],
            'product.data.cost' => ['required', 'numeric'],
            'product.data.is_for_sale' => ['required', 'boolean']
        ];
    }


    public function stockRules()
    {
        return [
            'stock.data.supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'stock.data.in_stock' => ['nullable', 'integer', 'min:0'],
            'stock.data.minimum_reorder_level' => ['required', 'integer', 'min:1'],
            'stock.data.default_purchase_costs' => ['nullable', 'numeric', 'min:0'],
        ];
    }


    public function attributes()
    {
        return [
            'product.product_id' => 'product id',
            'product.data.sku' => 'sku',
            'product.data.barcode' => 'barcode',
            'product.data.name' => 'name',
            'product.data.image' => 'image',
            'product.data.category' => 'category',
            'product.data.sold_by' => 'sold by',
            'product.data.price'  => 'price',
            'product.data.cost' => 'cost',
            'stock.data.supplier_id' => 'supplier id',
            'stock.data.in_stock' => 'in stock',
            'stock.data.stock_in' => 'stock in',
            'stock.data.stock_out' => 'stock out',
            'stock.data.minimum_reorder_level' => 'minimum reorder level',
            'stock.data.default_purchase_costs' => 'default purchase costs',
        ];
    }
}
