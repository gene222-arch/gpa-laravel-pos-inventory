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
     * @param array $salesReturnDetails
     * @return boolean
     */
    public function createRequestForm(int $invoiceId, array $salesReturnDetails): bool
    {
        try {
            DB::transaction(function () use($invoiceId, $salesReturnDetails)
            {
                # create `sales_return`
                $salesReturn = $this->createSalesReturn($invoiceId);

                # create `sales_return_details`
                $salesReturn->salesReturnDetails()->attach($salesReturnDetails);

                #update stocks
                (new Stock())->updateBadOrderQtyOf($salesReturnDetails);
            });
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $invoiceId
     * @return \App\Models\SalesReturn
     */
    private function createSalesReturn(int $invoiceId): SalesReturn
    {
        return SalesReturn::create([
            'invoice_id' => $invoiceId
        ]);
    }


    /**
     * Undocumented function
     *
     * @param array $salesReturnDetails
     * @return boolean
     */
    public function updateRequestForm(int $salesReturnId, int $invoiceId, array $salesReturnDetails): bool
    {
        try {
            DB::transaction(function () use($salesReturnId, $invoiceId, $salesReturnDetails)
            {
                # update `sales_return`
                $this->updateSalesReturn($salesReturnId, $invoiceId);

                # update `sales_return_details`
                $salesReturnDetails = $this->updateSalesReturnDetails(
                    $salesReturnId,
                    $invoiceId,
                    $salesReturnDetails
                );

                # update `stocks`
                (new Stock())->updateBadOrderQtyOf($salesReturnDetails);
            });
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }



    /**
     * Undocumented function
     *
     * @param integer $salesReturnId
     * @param integer $invoiceId
     * @return boolean
     */
    public function updateSalesReturn(int $salesReturnId, int $invoiceId): bool
    {
        return \boolval(SalesReturn::where('id', '=', $salesReturnId)
                                        ->update([
                                            'invoice_id' => $invoiceId
                                        ])
        );
    }


    /**
     * Undocumented function
     *
     * @param integer $salesReturnId
     * @param integer $invoiceId
     * @param array $salesReturnDetails
     * @return mixed
     */
    public function updateSalesReturnDetails(int $salesReturnId, int $invoiceId, array $salesReturnDetails)
    {
        $salesReturn = new SalesReturn();

        $salesReturnDetails = preparePrepend([
            'sales_return_id' => $salesReturnId,
            'invoice_details_id' => $invoiceId
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
     * @param array $salesReturnIds
     * @return boolean
     */
    public function deleteMany(array $salesReturnIds): bool
    {
        return \boolval(SalesReturn::whereIn('id', $salesReturnIds)->delete());
    }


    /**
     * Undocumented function
     *
     * @param integer $salesReturnId
     * @param array $productIds
     * @return boolean
     */
    public function removeItems(int $salesReturnId, array $productIds): bool
    {
        return \boolval(SalesReturn::find($salesReturnId)
                                            ->salesReturnDetails()
                                            ->wherePivotIn('product_id', $productIds)
                                            ->detach()
        );
    }



}
