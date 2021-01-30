<?php

namespace App\Traits\Invoice;

use Carbon\Carbon;

trait InvoiceHelperServices
{

    private function prepareInvoicePaymentDate(int $numberOfDays)
    {
        return Carbon::now()->addDays($numberOfDays);
    }

}
