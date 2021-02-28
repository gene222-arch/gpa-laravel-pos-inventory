<?php

namespace App\Http\Controllers\Api\ExportControllers;

use PDF;
use Excel;
use App\Models\Invoice;
use App\Models\Customer;
use App\Exports\InvoicesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PDFRequest\GenerateInvoiceRequest;
use App\Traits\PDF\PDFGeneratorServices;

class ExportInvoiceController extends Controller
{
    use PDFGeneratorServices;

    public function toPDF(Invoice $invoice)
    {
        $fileName = 'invoice-' . now()->toDateString() . '-' . time() . '-' . $invoice->id . '.pdf';

        return $this->generateInvoicePDF($invoice->id, $fileName);
    }


    public function toExcel()
    {
        $fileName = 'invoice-' . now()->toDateString() . '-' . time() . '.xlsx';
        $this->storeExcel($fileName);

        return (new InvoicesExport())->download($fileName);
    }


    public function toCSV()
    {
        $fileName = 'invoice-' . now()->toDateString() . '-' . time() . '.csv';
        $this->storeCSV($fileName);

        return (new InvoicesExport())->download($fileName);
    }


    private function storeExcel(string $fileName)
    {
        Excel::store(new InvoicesExport(),
            'excels/invoices/' . $fileName
        );
    }


    private function storeCSV(string $fileName)
    {
        Excel::store(new InvoicesExport(),
            'csv/invoices/' . $fileName
        );
    }

}
