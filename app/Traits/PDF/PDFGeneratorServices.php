<?php

namespace App\Traits\PDF;
use PDF;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\PosPayment;
use App\Models\PurchaseOrder;
use App\Traits\PDF\StorePDFServices;

trait PDFGeneratorServices
{
    use StorePDFServices;

    /**
     * Undocumented function
     *
     * @param integer $invoiceId
     */
    public function generateInvoicePDF(int $invoiceId, string $fileName)
    {
        $invoice = Invoice::find($invoiceId);
        $customer = Customer::find($invoice->customer_id);
        $invoiceDetails = (new Invoice())->invoiceDetailstoPDF($invoiceId);
        
        $subTotal = 0;
        $tax = 0;
        $discount = 0;
        $total = 0;

        foreach ($invoiceDetails as $invoiceDetail)
        {
            $subTotal += $invoiceDetail->sub_total;
            $discount += $invoiceDetail->discount;
            $tax += $invoiceDetail->tax;
            $total += $invoiceDetail->total;
        }

        $subTotal = number_format($subTotal, 2);
        $tax = number_format($tax, 2);
        $discount = number_format($discount, 2);
        $total = number_format($total, 2);

        $pdf = PDF::loadView('exports.PDFs.invoice', [
            'invoice' => $invoice,
            'customer' => $customer,
            'invoiceDetails' => $invoiceDetails,
            'invoiceSalesTax' => [
                'subTotal' => $subTotal,
                'discount' => $discount,
                'taxRate' => '%12',
                'tax' => $tax,
                'total' => $total
            ]
        ])
        ->setOptions([
            'page-size' => 'A4',
            'header-left' => '[page]',
            'header-right' => '[date]'
        ]);

        $this->storeInvoicePDF($pdf, $fileName);

        return $pdf->download($fileName);
    }



    /**
     * Undocumented function
     *
     * @param integer $paymentId
     */
    public function generatePaymentsPDF(int $paymentId, string $fileName)
    {
        $payment = PosPayment::find($paymentId);
        $customer = (new PosPayment())->paymentCustomerInfo($paymentId);
        $items = (new PosPayment())->paymentPosDetailstoPDF($paymentId);

        $pdf = PDF::loadView('exports.PDFs.payment', [
            'payment' => $payment,
            'customer' => $customer,
            'paymentDetails' => $items,
            'taxRate' => '%12'
        ])
        ->setOptions([
            'page-width' => '82.55',
            'page-height' => '200'
        ]);

        $this->storePaymentsPDF($pdf, $fileName);

        return $pdf->download();
    }



    /**
     * Undocumented function
     *
     * @param integer $purchaseOrderId
     * @param string $fileName
     * @return void
     */
    public function generatePurchaseOrderPDF(int $purchaseOrderId, string $fileName)
    {
        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
        $purchaseOrderDetails = (new PurchaseOrder())->loadPurchaseOrderDetails($purchaseOrderId);

        $total =  $purchaseOrderDetails->map->amount->sum();

        $pdf = PDF::loadView('exports.PDFs.purchase-orders', [
            'purchaseOrder' => $purchaseOrder,
            'purchaseOrderDetails' => $purchaseOrderDetails,
            'total' => $total
        ])
        ->setOptions([
            'page-size' => 'A4',
            'header-left' => '[page]',
            'header-right' => '[date]'
        ]);

        $this->storePurchaseOrderPDF($pdf, $fileName);

        return $pdf->download();
    }



    /**
     * Undocumented function
     *
     * @param [type] $products
     * @param string $fileName
     * @return void
     */
    public function generateLowStockPDF($products, string $fileName)
    {
        $pdf = PDF::loadView('exports.PDFs.low-stock', [
            'products' => $products
        ])
        ->setOptions([
            'page-size' => 'A4',
            'header-left' => '[page]',
            'header-right' => '[date]'
        ]);

        $this->storeLowStockPDF($pdf, $fileName);

        return $pdf->download();
    }

}
