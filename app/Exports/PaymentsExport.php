<?php

namespace App\Exports;

use App\Models\PosPayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentsExport implements FromView
{
    use Exportable;

    public function view(): View
    {
        return view('exports.EXCEL-CSVs.payments', [
            'payments' => (new PosPayment())->loadPayments()
        ]);
    }


}
