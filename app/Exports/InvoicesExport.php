<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class InvoicesExport implements FromView
{
    use Exportable;

    public function view(): View
    {
        return view('exports.EXCEL-CSVs.invoices', [
            'invoices' => (new Invoice())->invoiceDetailstoExcel()
        ]);
    }
}
