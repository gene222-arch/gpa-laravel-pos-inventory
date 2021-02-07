<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\PaymentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;


class QueueWalkingCustomerPaymentNotif implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $email;
    public string $name;
    public int $paymentId;
    public string $fileName;
    public string $paymentMethod;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, string $name, int $paymentId, string $fileName, string $paymentMethod)
    {
        $this->email = $email;
        $this->name = $name;
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
        Notification::route('mail', [
            $this->email => $this->name
        ])->notify(new PaymentNotification(
                $this->paymentId,
                $this->fileName,
                $this->paymentMethod,
                $this->name));
    }
}
