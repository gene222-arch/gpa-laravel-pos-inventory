<?php

namespace App\Traits\Payment;

use App\Models\Payment;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

trait PaymentServices
{

    /**
     * Undocumented function
     *
     * @param integer $posId
     * @param array $posDetails
     * @return boolean
     */
    public function processPayment(int $posId, array $posDetails): bool
    {
        try {
            DB::transaction(function () use($posId, $posDetails)
            {
                $payment = $this->createPayment($posId);

                $payment->paymentDetails()->attach($posDetails);

                (new Stock())->stockOutMany($posDetails);
            });
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $posId
     * @return Payment
     */
    public function createPayment(int $posId): Payment
    {
        return Payment::create([
            'pos_id' => $posId
        ]);
    }

}
