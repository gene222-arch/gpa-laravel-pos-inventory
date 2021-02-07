<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\InvoiceNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class QueueInvoiceNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Customer $customer;
    public int $invoiceId;
    public string $paymentDate;
    public string $fileName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, int $invoiceId, string $paymentDate, string $fileName)
    {
        $this->customer = $customer;
        $this->invoiceId = $invoiceId;
        $this->paymentDate = $paymentDate;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->customer->notify(new InvoiceNotification(
            $this->invoiceId,
            $this->paymentDate,
            $this->fileName
        ));
    }
}
