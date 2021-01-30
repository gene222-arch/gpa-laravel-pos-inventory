<?php

namespace App\Models;

use App\Models\Stock;
use App\Traits\InventoryManagement\PurchaseOrder\PurchaseOrderServices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PurchaseOrder extends Model
{
    use HasFactory, PurchaseOrderServices;


    protected $table = 'purchase_order';

    protected $fillable = [
        'supplier_id',
        'status',
        'total_ordered_quantity',
        'total_remaining_ordered_quantity',
        'purchase_order_data',
        'expected_delivery_date'
    ];


    /**
     * Define belongsTo relationship with \App\Models\PurchaseOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo {PurchaseOrder}
     */
    public function supplier()
    {
        return $this->belongsTo(\App\Models\PurchaseOrder::class);
    }


    /**
     * Define many-to-many relationship with \App\Models\Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany {Product}
     */
    public function purchaseOrderDetails()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'purchase_order_details')
                    ->withPivot($this->purchaseOrderDetailFields())
                    ->withTimestamps();
    }


    /**
     * Define many-to-many relationship with \App\Models\Supplier
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany {Supplier}
     */
    public function receivedStocks()
    {
        return $this->belongsToMany(\App\Models\Supplier::class, 'received_stocks');
    }


    /**
     * `purchase_order_details` table field list
     *
     * @return array
     */
    private function purchaseOrderDetailFields(): array
    {
        return [
            'id',
            'purchase_order_id',
            'product_id',
            'received_quantity',
            'ordered_quantity',
            'remaining_ordered_quantity',
            'purchase_cost',
            'amount',
            'created_at',
            'updated_at'
        ];
    }


    /**
     * `purchase_order_details` table unique field list
     *
     * @return array
     */
    private function purchaseOrderDetailUniqueFields()
    {
        return [
            'purchase_order_id',
            'product_id'
        ];
    }


    /**
     * `purchase_order_details` table mass assignable field list
     *
     * @return array
     */
    private function purchaseOrderDetailAssignableFields(): array
    {
        return [
            'product_id',
            'received_quantity',
            'ordered_quantity',
            'remaining_ordered_quantity',
            'purchase_cost',
            'amount',
            'created_at',
            'updated_at'
        ];
    }

}
