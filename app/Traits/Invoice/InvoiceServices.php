<?php

namespace App\Traits\Invoice;

use App\Jobs\QueueCustomInvoiceNotification;
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
     * @return array
     */
    public function invoiceDetailstoExcel(): array
    {
        DB::statement('SET sql_mode=""');

        return DB::table('invoices')
                    ->join('invoice_details', 'invoice_details.invoice_id', '=', 'invoices.id')
                    ->join('customers', 'customers.id', '=', 'invoices.customer_id')
                    ->selectRaw('
                        invoices.id,
                        DATE_FORMAT(invoices.created_at, "%M %d, %Y") as invoice_date,
                        customers.name as customer_name,
                        SUM(invoice_details.quantity) as number_of_items,
                        SUM(invoice_details.sub_total) as sub_total,
                        SUM(invoice_details.tax) as tax,
                        SUM(invoice_details.total) as total,
                        DATE_FORMAT(invoices.payment_date, "%M %d, %Y") as payment_date
                    ')
                    ->groupBy('invoices.id')
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
     * 
     */
    public function generateSalesInvoice(
        Customer $customer,
        array $invoiceDetails,
        $numberOfDays = 30,
        string $customerEmail = null,
        string $customerName = null): mixed
    {
        try {
            DB::transaction(function () use($customer, $invoiceDetails, $numberOfDays, $customerEmail, $customerName)
            {
        
                dd($customerEmail);
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

                    if (!($customerName && $customerEmail))
                    {
                        dispatch(new QueueInvoiceNotification(
                            $customer,
                            $invoice->id,
                            $invoice->payment_date,
                            $fileName
                        ))
                        ->delay(now()->addSecond(10));
                    }
                    else 
                    {
                        dispatch(new QueueCustomInvoiceNotification(
                            $customerName,
                            $customerEmail,
                            $invoice->id,
                            $invoice->payment_date,
                            $fileName
                        ))
                        ->delay(now()->addSecond(10));
                    }
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
    public function updateStatus(array $invoiceIds): bool
    {
        return \boolval(Invoice::whereIn('id', $invoiceIds)
                                ->update([
                                    'status' => DB::raw('
                                        CASE
                                            WHEN
                                                status = "Paid"
                                            THEN
                                                "Payment in process"
                                            ELSE
                                                "Paid"
                                        END
                                    '),
                                    'paid_at' => DB::raw('
                                        CASE
                                            WHEN
                                                status = "Paid"
                                            THEN
                                                now()
                                            ELSE
                                                NULL
                                        END
                                    ')
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
