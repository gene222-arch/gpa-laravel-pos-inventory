<?php

namespace App\Models;

use App\Traits\Pos\PosServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    use HasFactory, PosServices;

    protected $fillable = [
        'cashier',
        'customer_id',
        'status'
    ];

    protected $table = 'pos';

    public $timestamps = true;

    public function posDetails()
    {
        return $this->belongsToMany(\App\Models\Product::class,
                    'pos_details',
                    'pos_id',
                    'product_id')
                    ->withPivot([
                        'id',
                        'pos_id',
                        'discount_id',
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

    public function latestPosDetails()
    {
        return $this->belongsToMany(\App\Models\Product::class,
                    'pos_details',
                    'pos_id',
                    'product_id')
                    ->withPivot([
                        'pos_id',
                        'discount_id',
                        'quantity',
                        'price',
                        'unit_of_measurement',
                        'sub_total',
                        'discount',
                        'tax',
                        'total'
                    ])
                    ->orderBy('created_at', 'asc')
                    ->withTimestamps();
    }



    public function posPayment()
    {
        return $this->hasOne(\App\Models\PosPayment::class, 'pos_id');
    }
}
