<?php

namespace App\Http\Controllers\Api\ExportControllers;

use App\Exports\BadOrdersExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;

class ExportBadOrdersController extends Controller
{
    public function toExcel()
    {
        $fileName = 'bad-orders-' . now()->toDateString() . time() .  '.xlsx';
        $this->storeExcel($fileName);

        return Excel::download(new BadOrdersExport(), $fileName);
    }

    public function toCSV()
    {
        $fileName = 'bad-orders-' . now()->toDateString() . time() .  '.csv';

        $this->storeCSV($fileName);
        return Excel::download(new BadOrdersExport(),  $fileName);
    }


    public function storeExcel($fileName)
    {
        Excel::store(new BadOrdersExport(),
            'excels/bad-orders/' . $fileName);
    }


    public function storeCSV($fileName)
    {
        Excel::store(new BadOrdersExport(),
            'csv/bad-orders/' . $fileName);
    }


}
