<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $table = 'stock_adjustments';

    protected $fillable = [
        'adjusted_by',
        'reason'
    ];


    /**
     * Define a belongs-to-many relationship with `Products`
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stockAdjustmentDetails()
    {
        return $this->belongsToMany(Product::class,
                        'stock_adjustment_details',
                        'stock_adjustment_id',
                        'product_id'
                    );
    }

}
