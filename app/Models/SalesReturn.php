<?php

namespace App\Models;

use App\Traits\SalesReturn\SalesReturnServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory, SalesReturnServices;


    protected $fillable = [
        'pos_id',
    ];

    public function posSalesReturnDetails()
    {
        return $this->belongsToMany(\App\Models\Pos::class,
            'sales_return_details',
            'sales_return_id',
            'pos_details_id')
            ->withPivot([
                'product_id',
                'defect',
                'quantity',
                'price',
                'unit_of_measurement',
                'sub_total',
                'discount',
                'tax',
                'total'
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
            'pos_details_id',
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
            'pos_details_id',
            'product_id',
            'defect',
            'quantity',
            'price',
            'unit_of_measurement',
            'sub_total',
            'discount',
            'tax',
            'total',
        ];
    }

}
