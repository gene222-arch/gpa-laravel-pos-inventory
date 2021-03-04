<?php

namespace App\Exports;

use App\Models\SalesReturn;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SalesReturnExport implements FromView
{
    use Exportable;

    public function view(): View
    {
        return view('exports.EXCEL-CSVs.sales-returns', [
            'salesReturns' => (new SalesReturn())->loadSalesReturns()
        ]);
    }
}
