<?php

namespace App\Http\Controllers\Api\ExportControllers;

use App\Exports\SalesReturnExport;
use App\Http\Controllers\Controller;
use App\Models\SalesReturn;
use Excel;

class ExportSalesReturnController extends Controller
{
    public function toExcel()
    {
        $fileName = 'sales-returns-' . now()->toDateString() . time() .  '.xlsx';

        $this->storeExcel($fileName);
        return Excel::download(new SalesReturnExport(),  $fileName);
    }


    public function toCSV()
    {
        $fileName = 'sales-returns-' . now()->toDateString() . time() .  '.csv';

        $this->storeCSV($fileName);
        return Excel::download(new SalesReturnExport(), $fileName);
    }

    public function storeExcel($fileName)
    {
        Excel::store(new SalesReturnExport(), 'excels/sales-returns/' . $fileName);
    }


    public function storeCSV($fileName)
    {
        Excel::store(new SalesReturnExport(), 'csv/sales-returns/' . $fileName);
    }

}
