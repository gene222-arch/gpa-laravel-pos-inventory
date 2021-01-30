<?php

namespace App\Models;

use App\Traits\Products\CategoryServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, CategoryServices;

    protected $guarded = [];

    public $timestamps = true;

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany {Product}
     */
    public function productCategories()
    {
        return $this->belongsToMany(\App\Models\Product::class,'product_category')
                    ->withPivot('id')
                    ->withTimestamps();
    }

}
