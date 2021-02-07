<?php

namespace App\Traits\Notifications;

use App\Models\Customer;
use App\Jobs\QueuePaymentNotification;
use App\Jobs\QueueWalkingCustomerPaymentNotif;

trait ShouldMailServices
{
    /**
     * Undocumented function
     *
     * @param integer $customerId
     * @param integer $paymentId
     * @param string $paymentMethod
     * @param boolean $isRegistered
     * @param string|null $customerEmail
     * @param string|null $customerName
     * @return void
     */
    public function paymentMail(int $customerId, int $paymentId, string $paymentMethod, bool $isRegistered = false, string $customerEmail = null, string $customerName = null)
    {
        $fileName = 'payment-' . now()->toDateString() . '-' . time() . '-' . $paymentId . '.pdf';

        # generate pdf
        $this->generatePaymentsPDF($paymentId, $fileName);

        switch ($isRegistered)
        {
            case true:
                $customer = Customer::find($customerId);

                dispatch(new QueuePaymentNotification($customer,
                    $paymentId,
                    $fileName,
                    $paymentMethod))
                ->delay(now()->addSecond(10));
                break;

            case false:
                    dispatch(
                        new QueueWalkingCustomerPaymentNotif(
                            $customerEmail,
                            $customerName,
                            $paymentId,
                            $fileName,
                            $paymentMethod
                    ))
                    ->delay(now()->addSecond(10));
                break;
        }
    }

}
