<?php

namespace App\Models;

use App\Traits\SalesReturn\SalesReturnServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory, SalesReturnServices;


    protected $guarded = [];

    public function salesReturnDetails()
    {
        return $this->belongsToMany(\App\Models\Invoice::class,
            'sales_return_details',
            'sales_return_id',
            'invoice_details_id')
            ->withPivot([
                'product_id',
                'defect',
                'quantity',
                'price',
                'amount',
                'unit_of_measurement',
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
            'invoice_details_id',
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
            'invoice_details_id',
            'product_id',
            'defect',
            'quantity',
            'price',
            'amount',
            'unit_of_measurement',
        ];
    }

}
