<?php

namespace App\Models;

use App\Traits\Pos\PosServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    use HasFactory, PosServices;

    protected $guarded = [];

    protected $table = 'pos';

    public $timestamps = true;

    public function posDetails()
    {
        return $this->belongsToMany(\App\Models\Product::class,
                    'pos_details',
                    'pos_id',
                    'product_id')
                    ->withPivot([
                        'pos_id',
                        'quantity',
                        'price',
                        'unit_of_measurement',
                        'amount'
                    ])
                    ->withTimestamps();
    }



    public function posPayment()
    {
        return $this->hasOne(\App\Models\PosPayment::class, 'pos_id');;
    }
}
