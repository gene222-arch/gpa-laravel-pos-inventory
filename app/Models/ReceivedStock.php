<?php

namespace App\Models;

use App\Traits\InventoryManagement\StockReceived\StockReceivedServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedStock extends Model
{
    use HasFactory, StockReceivedServices;

    protected $table = 'received_stocks';

    protected $fillable = [
        'purchase_order_id',
        'supplier_id'
    ];


    public function receiveStockDetails()
    {
        return $this->belongsToMany(\App\Models\PurchaseOrder::class, 'received_stock_details',
        'received_stock_id',
        'purchase_order_details_id')
                    ->withPivot([
                        'product_id',
                        'received_quantity'
                    ])
                    ->withTimestamps();
    }

}
