<?php

namespace App\Traits\PDF;

trait StorePDFServices
{

    /**
     * Undocumented function
     *
     * @param [type] $pdf
     * @param string $fileName
     * @return void
     */
    private function storeInvoicePDF($pdf, string $fileName)
    {
        $pdf->save(storage_path('app/pdf/invoices/' . $fileName));
    }



    /**
     * Undocumented function
     *
     * @param [type] $pdf
     * @param string $fileName
     * @return void
     */
    public function storePaymentsPDF($pdf, string $fileName)
    {
        $pdf->save(storage_path('app/pdf/payments/' . $fileName));
    }


    /**
     * Undocumented function
     *
     * @param [type] $pdf
     * @param string $fileName
     * @return void
     */
    public function storePurchaseOrderPDF($pdf, string $fileName)
    {
        $pdf->save(storage_path('app/pdf/purchase-orders/' . $fileName));
    }



    /**
     * Undocumented function
     *
     * @param [type] $pdf
     * @param string $fileName
     * @return void
     */
    public function storeLowStockPDF($pdf, string $fileName)
    {
        $pdf->save(storage_path('app/pdf/low-stock-notifications/' . $fileName));
    }


}
