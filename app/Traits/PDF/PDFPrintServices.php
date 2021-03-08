<?php

namespace App\Traits\PDF;

use App\Models\PosPayment;
use App\Models\Sale;
use \PDF;
use Illuminate\Support\Facades\DB;

trait PDFPrintServices
{

    public function printSalesReceipt (int $salesId)
    {
        $sales = Sale::find($salesId);
        $payment = PosPayment::where('pos_id', '=', $sales->pos_id)->first();

        $items = DB::table('sales_details')
            ->selectRaw('
                sales_details.id as id,
                products.name as product_description,
                sales_details.quantity as quantity,
                sales_details.price as unit_price,
                sales_details.sub_total as sub_total
            ')
            ->join('products', 'products.id', '=', 'sales_details.product_id')
            ->get()
            ->toArray();

        $customer = (new PosPayment())->paymentCustomerInfo($payment->id);

        $pdf = PDF::loadView('exports.PDFs.payment', [
            'payment' => $payment,
            'customer' => $customer,
            'paymentDetails' => $items,
            'taxRate' => '%12'
        ])
        ->setOptions([
            'page-width' => '82.55',
            'page-height' => '200'
        ]);

        return $pdf->inline();
    }

}