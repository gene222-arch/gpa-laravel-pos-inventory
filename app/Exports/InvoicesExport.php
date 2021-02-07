<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoicesExport implements FromView
{
    public function view(): View
    {
        return view('exports.EXCEL-CSVs.invoices', [
            'invoices' => (new Invoice())->loadInvoiceWithDetails()
        ]);
    }
}
