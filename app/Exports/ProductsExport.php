<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ProductsExport implements FromView
{
    use Exportable;

    public function view(): View
    {
        return view('exports.EXCEL-CSVs.products', [
            'products' => (new Product())->getAll()
        ]);
    }
}
