<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class PurchaseOrdersExport implements FromView
{
    use Exportable;

    public function view(): View
    {
        return view('exports.EXCEL-CSVs.purchase-orders', [
            'purchaseOrders' => (new PurchaseOrder())->getAllPurchaseOrders()
        ]);
    }
}
