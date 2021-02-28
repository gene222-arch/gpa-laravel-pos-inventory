<?php

namespace App\Http\Controllers\Api\ExportControllers;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use Excel;

class ExportProductsController extends Controller
{

    public function toExcel()
    {
        $fileName = 'products-' . now()->toDateString() . time() .  '.xlsx';

        $this->storeExcel($fileName);
        return (new ProductsExport())->download($fileName);
    }

    public function storeExcel($fileName)
    {
        Excel::store(new ProductsExport(),
            'excels/products/' . $fileName);
    }


    public function toCSV()
    {
        $fileName = 'products-' . now()->toDateString() . time() .  '.csv';

        $this->storeCSV($fileName);
        return (new ProductsExport())->download($fileName);
    }


    public function storeCSV($fileName)
    {
        Excel::store(new ProductsExport(),
            'csv/products/' . $fileName);
    }
}
