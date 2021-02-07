<?php

namespace App\Http\Controllers\Api\ExportControllers;

use Excel;
use PDF;
use App\Models\PosPayment;
use App\Exports\PaymentsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PDFRequest\GeneratePaymentRequest;
use App\Traits\PDF\PDFGeneratorServices;

class ExportPaymentsController extends Controller
{

    use PDFGeneratorServices;

    public function toPDF()
    {
        $paymentId = 1;
        $fileName = 'payment-' . now()->toDateString() . '-' . time() . '-' . $paymentId . '.pdf';

        return $this->generatePaymentsPDF($paymentId, $fileName);
    }


    public function toExcel()
    {
        $fileName = 'payment-' . now()->toDateString() . '-' . time() . '.xlsx';

        $this->storeExcel($fileName);
        return Excel::download(new PaymentsExport(), 'payments.xlsx');
    }


    public function toCSV()
    {
        $fileName = 'payment-' . now()->toDateString() . '-' . time() . '.csv';

        $this->storeCSV($fileName);
        return Excel::download(new PaymentsExport(), 'payments.csv');
    }


    private function storeExcel($fileName)
    {
        Excel::store(new PaymentsExport(),
            'excels/payments/' . $fileName
        );
    }


    private function storeCSV($fileName)
    {
        Excel::store(new PaymentsExport(),
            'excels/payments/payments-' . $fileName
        );
    }

}
