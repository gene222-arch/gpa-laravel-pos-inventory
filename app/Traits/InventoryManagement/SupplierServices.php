<?php

namespace App\Traits\InventoryManagement;

use Illuminate\Support\Facades\DB;

trait SupplierServices
{

    /**
     * Delete multiple records in suppliers table
     *
     * @param array $suppliersIds
     * @return boolean
     */
    public function deleteMany(array $suppliersIds): bool
    {
        return \boolval(DB::table('suppliers')
                            ->whereIn('id', $suppliersIds)
                            ->delete()
        );
    }

}
