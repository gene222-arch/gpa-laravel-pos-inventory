<?php

namespace App\Traits\Products;

use App\Models\Discount;
use Illuminate\Support\Facades\DB;


trait DiscountServices
{
    /**
     * Undocumented function
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getDiscountList()
    {
        return Discount::all();
    }


     /**
      * Undocumented function
      *
      * @return Discount
      */
    public function getDiscount(int $discountId)
    {
        return Discount::find($discountId);
    }


    /**
     * Undocumented function
     *
     * @param string $name
     * @param float $percentage
     * @return Discount
     */
    public function createDiscount(string $name, float $percentage)
    {
        return Discount::create([
            'name' => $name,
            'percentage' => $percentage,
            'updated_at' => NULL
        ]);
    }


    /**
     * Undocumented function
     *
     * @param integer $discountId
     * @param string $name
     * @param float $percentage
     * @return boolean
     */
    public function updateDiscount(int $discountId, string $name, float $percentage): bool
    {
        return Discount::where('id', '=', $discountId)
                        ->update([
                            'name' => $name,
                            'percentage' => $percentage,
                            'created_at' => DB::raw('created_at')
                        ]);
    }


    /**
     * Undocumented function
     *
     * @param array $discountIds
     * @return boolean
     */
    public function deleteDiscounts(array $discountIds): bool
    {
        return Discount::whereIn('id', $discountIds)->delete();
    }


}
