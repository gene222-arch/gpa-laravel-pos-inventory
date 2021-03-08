<?php

namespace App\Http\Controllers\Api\ExportControllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Traits\PDF\PDFPrintServices;
use Illuminate\Http\Request;


class ExportSalesController extends Controller
{
    use PDFPrintServices;

    public function print (Sale $sale)
    {
        return $this->printSalesReceipt($sale->id);
    }
}
