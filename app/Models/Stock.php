<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\InventoryManagement\Stocks\StockServices;

class Stock extends Model
{
    use HasFactory, StockServices;

    protected $guarded = [];

    public $timestamps = true;

    /**
     * Define Stock has an inverse relationship with Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo {Product}
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }



    /**
     * Undocumented function
     *
     * @return array
     */
    public function stockFields()
    {
        return [
            'product_id',
            'supplier_id',
            'in_stock',
            'stock_in',
            'stock_out',
            'minimum_reorder_level',
            'primary_supplier',
            'default_purchase_costs'
        ];
    }

}
