<?php

namespace App\Traits\SalesReturn;

use App\Models\Stock;
use App\Models\SalesReturn;
use Illuminate\Support\Facades\DB;

trait SalesReturnServices
{


    /**
     * Undocumented function
     *
     * @return array
     */
    public function loadSalesReturns(): array
    {
        DB::statement('SET sql_mode = "" ');

        return DB::table('sales_returns')
            ->join('pos', 'pos.id', '=', 'sales_returns.pos_id')
            ->join('sales_return_details', 'sales_return_details.sales_return_id', '=', 'sales_returns.id')
            ->join('customers', 'customers.id', '=', 'pos.customer_id')
            ->selectRaw('
                sales_returns.id as id,
                customers.name as customer_name,
                SUM(sales_return_details.total) as sales_return,
                SUM(sales_return_details.quantity) as no_of_items,
                sales_returns.created_at as returned_at
            ')
            ->groupBy('id')
            ->get()
            ->toArray();
    }


    /**
     * Undocumented function
     *
     * @param array $salesReturnDetails
     * @return mixed
     */
    public function createRequestForm(int $posId, array $salesReturnDetails): mixed
    {
        try {
            DB::transaction(function () use($posId, $salesReturnDetails)
            {
                # create `sales_return`
                $salesReturn = $this->createSalesReturnWithPos($posId);

                # create `sales_return_details`
                $salesReturn->posSalesReturnDetails()->attach($salesReturnDetails);

                #update stocks
                (new Stock())->updateBadOrderQtyOf($salesReturnDetails);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $posId
     * @return \App\Models\SalesReturn
     */
    private function createSalesReturnWithPos(int $posId): SalesReturn
    {
        return SalesReturn::create([
            'pos_id' => $posId
        ]);
    }


    /**
     * Undocumented function
     *
     * @param array $salesReturnDetails
     * @return mixed
     */
    public function updateRequestForm(int $posSalesReturnId, int $posId, array $salesReturnDetails): mixed
    {
        try {
            DB::transaction(function () use($posSalesReturnId, $posId, $salesReturnDetails)
            {
                # update `sales_return`
                $this->updateSalesReturn($posSalesReturnId, $posId);

                # update `sales_return_details`
                $salesReturnDetails = $this->updateSalesReturnDetails(
                    $posSalesReturnId,
                    $posId,
                    $salesReturnDetails
                );

                # update `stocks`
                (new Stock())->updateBadOrderQtyOf($salesReturnDetails);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }



    /**
     * Undocumented function
     *
     * @param integer $posSalesReturnId
     * @param integer $posId
     * @return boolean
     */
    public function updateSalesReturn(int $posSalesReturnId, int $posId): bool
    {
        return \boolval(SalesReturn::where('id', '=', $posSalesReturnId)
                                        ->update([
                                            'pos_id' => $posId
                                        ])
        );
    }


    /**
     * Undocumented function
     *
     * @param integer $posSalesReturnId
     * @param integer $posId
     * @param array $salesReturnDetails
     * @return mixed
     */
    public function updateSalesReturnDetails(int $posSalesReturnId, int $posId, array $salesReturnDetails)
    {
        $salesReturn = new SalesReturn();

        $salesReturnDetails = preparePrepend([
            'sales_return_id' => $posSalesReturnId,
            'pos_details_id' => $posId
        ], $salesReturnDetails);

        $uniqueBy = $salesReturn->uniqueKeys();

        $update = $salesReturn->massAssignableKeys();

        $isUpdated = DB::table('sales_return_details')
                        ->upsert($salesReturnDetails,
                        $uniqueBy,
                        $update);

        return (!$isUpdated)
                ? false
                : $salesReturnDetails;
    }



    /**
     * Undocumented function
     *
     * @param array $posSalesReturnIds
     * @return boolean
     */
    public function deleteMany(array $posSalesReturnIds): bool
    {
        return \boolval(SalesReturn::whereIn('id', $posSalesReturnIds)->delete());
    }


    /**
     * Undocumented function
     *
     * @param integer $posSalesReturnId
     * @param array $productIds
     * @return boolean
     */
    public function removeItems(int $posSalesReturnId, array $productIds): bool
    {
        return \boolval(SalesReturn::find($posSalesReturnId)
                                            ->posSalesReturnDetails()
                                            ->wherePivotIn('product_id', $productIds)
                                            ->detach()
        );
    }



}
