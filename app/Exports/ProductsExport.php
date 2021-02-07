<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ProductsExport implements FromView
{
    public function view(): View
    {
        return view('exports.EXCEL-CSVs.products', [
            'products' => (new Product())->loadProductsWithStocks()
        ]);
    }
}
