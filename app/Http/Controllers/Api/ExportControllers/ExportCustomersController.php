<?php

namespace App\Http\Controllers\Api\ExportControllers;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use Excel;

class ExportCustomersController extends Controller
{
    public function toExcel()
    {
        $fileName = 'customers-' . now()->toDateString() . time() .  '.xlsx';

        $this->storeExcel($fileName);
        return Excel::download(new CustomersExport(), $fileName);
    }


    public function toCSV()
    {
        $fileName = 'customers-' . now()->toDateString() . time() .  '.csv';

        $this->storeCSV($fileName);
        return Excel::download(new CustomersExport(), $fileName);
    }


    public function storeCSV($fileName)
    {
        Excel::store(new CustomersExport(), 'csv/customers/' . $fileName);
    }

    public function storeExcel($fileName)
    {
        Excel::store(new CustomersExport(), 'excels/customers/customers-' . $fileName);
    }
}
