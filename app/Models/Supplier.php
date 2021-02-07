<?php

namespace App\Models;

use App\Traits\InventoryManagement\SupplierServices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Supplier extends Model
{
    use HasFactory, Notifiable, SupplierServices;

    protected $guarded = [];

    public $timestamps = true;


    /**
     * Define belongsTo relationship with \App\Models\PurchaseOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo {PurchaseOrder}
     */
    public function productRequest()
    {
        return $this->belongsTo(\App\Models\PurchaseOrder::class);
    }



    /**
     * Define many-to-many relationship with \App\Models\Supplier
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany {Supplier}
     */
    public function sentStocks()
    {
        return $this->belongsToMany(\App\Models\PurchaseOrder::class, 'received_stocks');
    }


}
