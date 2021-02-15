<?php

namespace App\Traits\Invoice;

use App\Jobs\QueueInvoiceNotification;
use App\Models\Customer;
use App\Models\Invoice;
use App\Notifications\InvoiceNotification;
use Illuminate\Support\Facades\DB;
use App\Traits\Invoice\InvoiceHelperServices;
use App\Traits\PDF\PDFGeneratorServices;

trait InvoiceServices
{

    use InvoiceHelperServices, PDFGeneratorServices;


    /**
     * Undocumented function
     *
     * @param integer $invoiceId
     * @return array
     */
    public function invoiceDetailstoPDF(int $invoiceId): array
    {
        return DB::table('invoices')
                    ->where('invoices.id', '=', $invoiceId)
                    ->join('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
                    ->join('products', 'products.id', '=', 'invoice_details.product_id')
                    ->selectRaw('
                        products.name as product_description,
                        invoice_details.quantity as quantity,
                        invoice_details.sub_total as sub_total,
                        invoice_details.discount as discount,
                        invoice_details.tax as tax,
                        invoice_details.total as total
                    ')
                    ->get()
                    ->toArray();
    }



    /**
     * Undocumented function
     *
     * @param integer $invoiceId
     */
    public function showInvoiceWithDetails(int $invoiceId)
    {
        return Invoice::find($invoiceId)->with('invoiceDetails')->first();
    }


    /**
     * Undocumented function
     *
     * @param Customer $customer
     * @param array $invoiceDetails
     * @param integer $numberOfDays
     * @param [type] $customerEmail
     * @return mixed
     */
    public function generateSalesInvoice(
        Customer $customer,
        array $invoiceDetails,
        $numberOfDays = 30,
        $customerEmail = null): mixed
    {
        try {
            DB::transaction(function () use($customer, $invoiceDetails, $numberOfDays, $customerEmail)
            {
                $invoice = Invoice::create([
                    'cashier' => auth()->user()->name,
                    'customer_id' => $customer->id,
                    'payment_date' => $this->prepareInvoicePaymentDate($numberOfDays)
                ]);

                $invoice->invoiceDetails()->attach($invoiceDetails);

                if ($invoice)
                {
                    $fileName = 'invoice-' . now()->toDateString() . '-' . time() . '-' . $invoice->id . '.pdf';

                    $this->generateInvoicePDF($invoice->id, $fileName);

                    dispatch(new QueueInvoiceNotification(
                        $customer,
                        $invoice->id,
                        $invoice->payment_date,
                        $fileName
                    ))
                    ->delay(now()->addSecond(10));
                }

            });
        } catch (\Throwable $th) {
            return $th->getMessage();
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
