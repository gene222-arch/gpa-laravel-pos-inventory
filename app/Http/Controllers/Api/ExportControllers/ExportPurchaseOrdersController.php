<?php

namespace App\Http\Controllers\Api\ExportControllers;

use App\Exports\PurchaseOrderExport;
use App\Exports\PurchaseOrdersExport;
use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Traits\ApiResponser;
use App\Traits\PDF\PDFGeneratorServices;
use Excel;

class ExportPurchaseOrdersController extends Controller
{
    use ApiResponser, PDFGeneratorServices;

    public function toPDF(PurchaseOrder $purchaseOrder)
    {
        $fileName = 'PO-' . now()->toDateString() . '-' . time() . '.pdf';

        return $this->generatePurchaseOrderPDF(
            $purchaseOrder->id,
            $fileName);
    }

    public function toExcel()
    {
        $fileName = 'purchase-orders-' . now()->toDateString() . time() .  '.xlsx';
        $this->storeExcel($fileName);

        return (new PurchaseOrdersExport())->download($fileName);
    }

    public function purchaseOrderToCSV(PurchaseOrder $purchaseOrder)
    {
        $fileName = 'purchase-order-' . now()->toDateString() . time() .  '.csv';

        $this->storeCSV($fileName);
        return (new PurchaseOrderExport($purchaseOrder->id))->download($fileName);
    }


    public function toCSV()
    {
        $fileName = 'purchase-orders-' . now()->toDateString() . time() .  '.csv';

        $this->storeCSV($fileName);
        return (new PurchaseOrdersExport())->download($fileName);
    }


    public function storeExcel($fileName)
    {
        Excel::store(new PurchaseOrdersExport(),
            'excels/purchase-orders/' . $fileName);
    }


    public function storeCSV($fileName)
    {
        Excel::store(new PurchaseOrdersExport(),
            'csv/purchase-orders/' . $fileName);
    }

}
