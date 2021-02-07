<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification
{
    use Queueable;

    public int $paymentId;
    public string $fileName;
    public string $paymentMethod;
    public $customerName;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $paymentId, string $fileName, string $paymentMethod, string $customerName = null)
    {
        $this->paymentId = $paymentId;
        $this->fileName = $fileName;
        $this->paymentMethod = $paymentMethod;
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
        $customerName = $notifiable->name ?? $this->customerName;

        return (new MailMessage)
                    ->subject('Successful Payment')
                    ->greeting('Hi, ' . $customerName)
                    ->line('')
                    ->line("Your $this->paymentMethod payment was properly received.")
                    ->line('We are thankful for the trust you had put on our store.')
                    ->line('For more information about the details of your order the')
                    ->line('receipt was properly prepared for you.')
                    ->line('Receipt is attached below.')
                    ->line('')
                    ->attach(storage_path('app/pdf/payments/' . $this->fileName), [
                        'as' => $customerName . '-payments.pdf',
                        'mime' => 'text/pdf'
                    ])
                    ->line('Thank you for your patronage!');
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
