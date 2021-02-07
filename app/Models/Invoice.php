<?php

namespace App\Models;

use App\Models\Product;
use App\Notifications\InvoiceNotification;
use App\Traits\Invoice\InvoiceServices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Invoice extends Model
{

    use HasFactory, Notifiable, InvoiceServices;

    protected $fillable = [
        'cashier',
        'customer_id',
        'status',
        'payment_date'
    ];

    public $timestamps = true;

    public function invoiceDetails()
    {
        return $this->belongsToMany(Product::class, 'invoice_details')
            ->withPivot([
                'invoice_id',
                'quantity',
                'price',
                'unit_of_measurement',
                'sub_total',
                'discount',
                'tax',
                'total'
            ]);
    }

}
