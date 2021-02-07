<?php

namespace App\Exports;

use App\Models\PosPayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentsExport implements FromView
{
    public function view(): View
    {
        return view('exports.EXCEL-CSVs.payments', [
            'payments' => (new PosPayment())->loadPayments()
        ]);
    }


}
