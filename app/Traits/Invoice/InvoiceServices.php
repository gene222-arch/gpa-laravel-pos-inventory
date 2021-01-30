<?php

namespace App\Traits\Invoice;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use App\Traits\Invoice\InvoiceHelperServices;

trait InvoiceServices
{

    use InvoiceHelperServices;

    /**
     * Undocumented function
     *
     * @param integer $invoiceId
     * @return void
     */
    public function loadInvoiceWithDetails(int $invoiceId)
    {
        return Invoice::find($invoiceId)->with('invoiceDetails')->get();
    }


    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param array $invoiceDetails
     * @return boolean
     */
    public function generateSalesInvoice(int $customerId, array $invoiceDetails, int $numberOfDays): bool
    {
        try {
            DB::transaction(function () use($customerId, $invoiceDetails, $numberOfDays)
            {
                $invoice = Invoice::create([
                    'customer_id' => $customerId,
                    'payment_date' => $this->prepareInvoicePaymentDate($numberOfDays)
                ]);

                $invoice->invoiceDetails()->attach($invoiceDetails);
            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }



    /**
     * Undocumented function
     *
     * @param array $invoiceIds
     * @return boolean
     */
    public function paid(array $invoiceIds): bool
    {
        return \boolval(Invoice::whereIn('id', $invoiceIds)
                                ->update([
                                    'status' => 'Paid',
                                    'paid_at' => now()
                                ])
        );
    }


    /**
     * Undocumented function
     *
     * @param array $invoiceIds
     * @return boolean
     */
    public function deleteSalesInvoices(array $invoiceIds): bool
    {
        return \boolval(Invoice::whereIn('id', $invoiceIds)
                            ->delete()
        );
    }


}
