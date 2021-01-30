<?php

namespace App\Models;

use App\Traits\Invoice\InvoiceServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, InvoiceServices;

    protected $fillable = [
        'customer_id',
        'status',
        'payment_date'
    ];

    public $timestamps = true;

    public function invoiceDetails()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'invoice_details');
    }

}
