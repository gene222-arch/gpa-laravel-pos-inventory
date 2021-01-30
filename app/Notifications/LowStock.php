<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LowStock extends Notification
{
    use Queueable;


    private Product $product;
    private int $productRemainingStock;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Product $product, int $productRemainingStock)
    {
        $this->product = $product;
        $this->productRemainingStock = $productRemainingStock;
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
                    ->subject('Low Stock!!!.')
                    ->greeting('Hi Sir/Ma`am,')
                    ->line($this->getBody()[0])
                    ->line($this->getBody()[1])
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }


    private function getBody()
    {
        return [
            $this->product->name . ' is low on stock and needed to be replenish.',
            'Remaining quantity: (' . $this->productRemainingStock . ')'
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
