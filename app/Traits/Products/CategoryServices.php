<?php

namespace App\Traits\Products;

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
        return \boolval(DB::table('categories')
                            ->whereIn('id', $categoryIds)
                            ->delete()
        );
    }

}
