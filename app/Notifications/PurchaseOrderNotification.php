<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $subject;
    public string $note;
    public string $fileName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $note, string $fileName)
    {
        $this->subject = $subject;
        $this->note = $note;
        $this->fileName = $fileName;
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
        return (new MailMessage)
                    ->subject($this->subject)
                    ->greeting('Hi Sir/Madaam, ')
                    ->line($this->note)
                    ->line('Thank you for your service!')
                    ->attach(storage_path('app/pdf/purchase-orders/' . $this->fileName), [
                        'as' => $notifiable->name . '-po.pdf',
                        'mime' => 'text/pdf'
                    ]);
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
