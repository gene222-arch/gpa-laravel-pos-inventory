<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CustomersExport implements FromView
{
    use Exportable;

    public function view(): View
    {
        return view('exports.EXCEL-CSVs.customers',[
            'customers' => (new Customer())->loadCustomers()
        ]);
    }
}
