<?php

namespace App\Traits\Products;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

trait CategoryServices
{


    /**
     * Delete multiple records in the categories table
     *
     * @param array $categoryIds
     * @return boolean
     */
    public function deleteMany(array $categoryIds): bool
    {
        return Category::whereIn('id', $categoryIds)->delete();
    }

}
