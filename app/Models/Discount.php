<?php

namespace App\Models;

use App\Traits\Products\DiscountServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory, DiscountServices;

    protected $fillable = [
        'name',
        'percentage'
    ];


    public function setUpdatedAt($value)
    {
       // some stuff
    }

}
