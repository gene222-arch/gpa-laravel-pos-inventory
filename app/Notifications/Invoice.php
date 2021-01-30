<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Invoice extends Notification
{
    use Queueable;


    private int $invoiceId;
    private string $companyName;
    private string $dueDate;
    private string $recipientName;
    private string $senderName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $invoiceId, string $companyName, string $dueDate, string $recipientName, string $senderName)
    {
        $this->$invoiceId = $invoiceId;
        $this->$companyName = $companyName;
        $this->$dueDate = $dueDate;
        $this->$recipientName = $recipientName;
        $this->$senderName = $senderName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->subject($this->getSubject())
                    ->greeting($this->getGreeting())
                    ->line($this->getBody()[0])
                    ->line($this->getBody()[1])
                    ->line($this->getBody()[2])
                    ->action('Notification Action', url('/'))
                    ->line('')
                    ->line($this->getSincerelyYours()[0])
                    ->line($this->getSincerelyYours()[1])
                    ->line('Thank you for using our application!');
    }


    private function getSubject()
    {
        return 'Invoice #' . $this->invoiceId . ' for ' . $this->companyName;
    }

    private function getGreeting()
    {
        return 'Hi ' . $this->recipientName;
    }

    private function getBody()
    {
        return [
            'I hope you’re well! Please see attached invoice number ' . $this->invoiceId . ' for ',
            'due on ' . $this->dueDate . '. Don’t hesitate to reach out if you have',
            'any questions.'
        ];
    }


    private function getSincerelyYours()
    {
        return [
            'Kind regards,',
            $this->senderName
        ];
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
