<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class InvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $invoiceId;
    public string $dueDate;
    public string $fileName;
    public ?string $customerName = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $invoiceId, string $dueDate, string $fileName, string $customerName = null)
    {
        $this->invoiceId = $invoiceId;
        $this->dueDate = Carbon::createFromTimeStamp(strtotime($dueDate))->diffForHumans();
        $this->fileName = $fileName;
        $this->customerName = $customerName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $customer = $notifiable->name ?? $this->customerName;
        
        return (new MailMessage)
                    ->subject('Invoice Receipt')
                    ->greeting("Hi $customer,")
                    ->line("")
                    ->line('I hope you’re well! Please see attached invoice id ' . $this->invoiceId . ' below.')
                    ->line('Due on ' . $this->dueDate . '. Don’t hesitate to reach out if you have')
                    ->line('any questions.')
                    ->attach(storage_path('app/pdf/invoices/' . $this->fileName), [
                        'as' => $customer . '-invoice.pdf',
                        'mime' => 'text/pdf'
                    ])
                    ->line('Thank you for your patronage.!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
