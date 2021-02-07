<?php

namespace App\Http\Controllers\Api\ExportControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PDFRequest\GeneratePurchaseOrderRequest;
use App\Traits\ApiResponser;
use App\Traits\PDF\PDFGeneratorServices;
use Illuminate\Http\Request;

class ExportPurchaseOrdersController extends Controller
{
    use ApiResponser, PDFGeneratorServices;

    public function toPDF(GeneratePurchaseOrderRequest $request)
    {
        $fileName = 'PO-' . now()->toDateString() . '-' . time() . '.pdf';

        return $this->generatePurchaseOrderPDF(
            $request->purchase_order_id,
            $fileName);
    }

}
