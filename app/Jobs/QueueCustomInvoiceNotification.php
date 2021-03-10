<?php

namespace App\Jobs;

use App\Notifications\InvoiceNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class QueueCustomInvoiceNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $customerName;
    public string $customerEmail;
    public int $invoiceId;
    public string $paymentDate;
    public string $fileName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $customerName, string $customerEmail, int $invoiceId, string $paymentDate, string $fileName)
    {
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
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
        Notification::route('mail', [
            $this->customerEmail => $this->customerName
        ])->notify(new InvoiceNotification(
            $this->invoiceId,
            $this->paymentDate,
            $this->fileName,
            $this->customerName
        ));
    }
}
