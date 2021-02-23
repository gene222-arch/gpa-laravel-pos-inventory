<?php

namespace App\Models;

use App\Traits\InventoryManagement\BadOrders\BadOrderServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadOrder extends Model
{
    use HasFactory, BadOrderServices;

    protected $fillable = [
        'user_id',
        'purchase_order_id',
        'status',
    ];


    protected $table = 'bad_orders';

    public function orderDetails()
    {
        return $this->belongsToMany(\App\Models\PurchaseOrder::class,
                    'bad_order_details',
                    'bad_order_id',
                    'purchase_order_details_id')
                    ->withPivot([
                        'supplier_id',
                        'product_id',
                        'defect',
                        'quantity',
                        'unit_of_measurement'
                    ])
                    ->withTimestamps();
    }


    /**
     * Undocumented function
     *
     * @return array
     */
    public function uniqueKeys(): array
    {
        return [
            'purchase_order_details_id',
            'product_id'
        ];
    }


    /**
     * Undocumented function
     *
     * @return array
     */
    public function massAssignableKeys(): array
    {
        return [
            'purchase_order_details_id',
            'product_id',
            'defect',
            'quantity',
            'price',
            'unit_of_measurement',
            'amount'
        ];
    }

}
