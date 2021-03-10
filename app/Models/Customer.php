<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\Customer\CustomerServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, Notifiable, CustomerServices;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'country'
    ];



}
