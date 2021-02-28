<?php

namespace App\Http\Requests\Products\Product;

use App\Http\Requests\BaseRequest;
use App\Traits\ApiResponser;


class StoreRequest extends BaseRequest
{
    use ApiResponser;

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
            'product.image' => ['nullable', 'string'],
            'product.category' => ['required', 'integer', 'exists:categories,id'],
            'product.sold_by' => ['required', 'in:each,weight/volume'],
            'product.price' => [ 'nullable', 'numeric', 'min:1'],
            'product.cost' => ['required', 'numeric', 'min:1'],
            'product.is_for_sale' => ['required', 'boolean']
        ];
    }


    public function stockRules()
    {
        return [
            'stock.supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'stock.in_stock' => ['nullable', 'integer', 'min:0'],
            'stock.minimum_reorder_level' => ['required', 'integer', 'min:1'],
            'stock.default_purchase_costs' => ['nullable', 'numeric', 'min:0'],
        ];
    }


    public function attributes()
    {
        return [
            'product.sku' => 'sku',
            'product.barcode' => 'barcode',
            'product.name' => 'name',
            'product.image' => 'image',
            'product.category' => 'category',
            'product.sold_by' => 'sold by',
            'product.price' => 'price',
            'product.cost' => 'cost',
            'stock.supplier_id' => 'supplier id',
            'stock.in_stock' => 'in stock',
            'stock.minimum_reorder_level' => 'minimum reorder level',
            'stock.default_purchase_costs' => 'default purchase costs',
            'product.is_for_sale' => 'is for sale'
        ];
    }


}
