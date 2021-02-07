<?php

namespace App\Exports;

use App\Models\BadOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BadOrdersExport implements FromView
{
    public function view(): View
    {
        return view('exports.EXCEL-CSVs.bad-orders', [
            'badOrders' => (new BadOrder())->loadBadOrdersWithDetails()
        ]);
    }

}
