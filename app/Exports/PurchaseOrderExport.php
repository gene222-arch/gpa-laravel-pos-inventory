<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class PurchaseOrderExport implements FromView
{
    use Exportable;

    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        return view('exports.EXCEL-CSVs.purchase-order', [
            'purchaseOrders' => (new PurchaseOrder())->getPurchaseOrderDetails($this->id)
        ]);
    }
}
