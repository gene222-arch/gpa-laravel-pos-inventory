<?php

namespace App\Models;

use App\Traits\Payment\PaymentServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosPayment extends Model
{
    use HasFactory, PaymentServices;

    protected $table = 'pos_payments';

    protected $fillable = [
        'pos_id',
        'cashier',
        'payment_method',
        'no_of_items_bought',
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
