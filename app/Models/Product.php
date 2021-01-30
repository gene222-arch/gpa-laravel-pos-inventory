<?php

namespace App\Models;

use App\Traits\Products\ProductServices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, ProductServices;

    protected $guarded = [];

    public $timestamps = true;

    /**
     * Define has many-to-many relationship with Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany {Category}
     */
    public function productCategories()
    {
        return $this->belongsToMany(\App\Models\Category::class,'product_category')
                    ->withPivot('id')
                    ->withTimestamps();
    }


    /**
     * Define has one-to-one relationship with Stock
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne {Stock}
     */
    public function stock()
    {
        return $this->hasOne(\App\Models\Stock::class);
    }


    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany {PurchaseORder}
     */
    public function purchaseOrderDetails()
    {
        return $this->belongsToMany(\App\Models\PurchaseOrder::class, 'purchase_order_details');
    }

}
