<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\PaymentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class QueuePaymentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Customer $customer;
    public int $paymentId;
    public string $fileName;
    public string $paymentMethod;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, int $paymentId, string $fileName, string $paymentMethod)
    {
        $this->customer = $customer;
        $this->paymentId = $paymentId;
        $this->fileName = $fileName;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->customer
            ->notify(new PaymentNotification(
                $this->paymentId,
                $this->fileName,
                $this->paymentMethod)
            );
    }
}
