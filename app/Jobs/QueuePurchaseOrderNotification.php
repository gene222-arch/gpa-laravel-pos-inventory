<?php

namespace App\Jobs;

use App\Models\Supplier;
use App\Notifications\PurchaseOrderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueuePurchaseOrderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Supplier $supplier;
    public string $subject;
    public string $note;
    public string $fileName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Supplier $supplier, string $subject, string $note, string $fileName)
    {
        $this->supplier = $supplier;
        $this->subject = $subject;
        $this->note = $note;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->supplier->notify(new PurchaseOrderNotification(
            $this->subject,
            $this->note,
            $this->fileName
        ));
    }
}
