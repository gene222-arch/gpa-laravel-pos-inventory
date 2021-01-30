<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosPayment extends Model
{
    use HasFactory;

    protected $table = 'pos_payments';

    protected $fillable = [
        'pos_id',
        'cashier',
        'payment_method',
        'sub_total',
        'discount',
        'tax',
        'shipping_fee',
        'total',
        'cash',
        'change'
    ];


    public function pos()
    {
        return $this->belongsTo(\App\Models\Pos::class);
    }

}
