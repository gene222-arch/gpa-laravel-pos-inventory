<?php

namespace App\Models;

use App\Models\Stock;
use App\Traits\InventoryManagement\Stocks\StockAdjustmentServices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockAdjustment extends Model
{
    use HasFactory, StockAdjustmentServices;

    protected $table = 'stock_adjustments';

    protected $fillable = [
        'adjusted_by',
        'reason'
    ];


    /**
     * Define a belongs-to-many relationship with `Stock`
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stockAdjustmentDetails()
    {
        return $this->belongsToMany(Stock::class,
                        'stock_adjustment_details',
                        'stock_adjustment_id',
                        'stock_id'
                    )
                    ->withPivot([
                        'in_stock',
                        'added_stock',
                        'removed_stock',
                        'counted_stock',
                        'stock_after'
                    ])
                    ->withTimestamps();
    }

}
