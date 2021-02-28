<?php

namespace App\Http\Controllers\Api\ExportControllers;

use Excel;
use PDF;
use App\Models\PosPayment;
use App\Exports\PaymentsExport;
use App\Http\Controllers\Controller;
use App\Traits\PDF\PDFGeneratorServices;

class ExportPaymentsController extends Controller
{

    use PDFGeneratorServices;

    public function toPDF(PosPayment $payment)
    {
        $fileName = 'payment-' . now()->toDateString() . '-' . time() . '-' . $payment->id . '.pdf';

        return $this->generatePaymentsPDF($payment->id, $fileName);
    }


    public function toExcel()
    {
        $fileName = 'payment-' . now()->toDateString() . '-' . time() . '.xlsx';

        $this->storeExcel($fileName);
        return (new PaymentsExport())->download($fileName);
    }


    public function toCSV()
    {
        $fileName = 'payment-' . now()->toDateString() . '-' . time() . '.csv';

        $this->storeCSV($fileName);
        return (new PaymentsExport())->download($fileName);
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
