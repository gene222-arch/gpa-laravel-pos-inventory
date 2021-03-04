<?php

namespace App\Traits\SalesReturn;

use App\Models\Pos;
use App\Models\Stock;
use App\Models\SalesReturn;
use Illuminate\Support\Facades\DB;

trait SalesReturnServices
{

    use SalesReturnSubModelServices;

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
                customers.name as customer,
                SUM(sales_return_details.total) as sales_return,
                SUM(sales_return_details.quantity) as no_of_items,
                DATE_FORMAT(sales_returns.created_at, "%M %d, %Y") as returned_at,
                DATE_FORMAT(pos.created_at, "%M %d, %Y") as purchased_at
            ')
            ->groupBy('id')
            ->orderByDesc('sales_returns.created_at')
            ->get()
            ->toArray();
    }



    public function getSalesReturnWithDetails(int $salesReturnId)
    {
        $salesReturn = DB::table('sales_returns')
            ->selectRaw('
            sales_returns.id as id,
            users.name as created_by,
            DATE_FORMAT(pos.created_at, "%M %d, %Y") as purchased_at,
            DATE_FORMAT(sales_returns.created_at, "%M %d, %Y") as returned_at
            ')
            ->join('pos', 'pos.id', '=', 'sales_returns.pos_id')
            ->join('users', 'users.id', '=', 'sales_returns.user_id')
            ->where('sales_returns.id', '=', $salesReturnId)
            ->first();

        $salesReturnDetails = DB::table('sales_return_details')
            ->selectRaw('
                sales_return_details.id as id,
                products.name as product_description,
                sales_return_details.defect as defect,
                sales_return_details.quantity as quantity,
                sales_return_details.price as price,
                sales_return_details.total as total
            ')
            ->join('products', 'products.id', '=', 'sales_return_details.product_id')
            ->where('sales_return_details.sales_return_id', '=', $salesReturnId)
            ->get()
            ->toArray();

        return [
            'salesReturn' => $salesReturn,
            'items' => $salesReturnDetails
        ];
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
            'user_id' => auth()->user()->id,
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
