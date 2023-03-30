<?php

namespace Apps\Notifications\Store;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaceOrderStatusChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($salesOrder)
    {
        $this->salesOrder = $salesOrder;
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
            ->subject('Orden de compra #' . $this->salesOrder->id . ': ' . $this->salesOrder->status->name)
            ->greeting('¡Hola!')
            ->line('El estado de la orden de compra #' . $this->salesOrder->id . ' se ha actualizado.')
            ->line(' Puedes ver los detalles haciendo click en el siguiente botón.')
            ->action('Ver', 'https://pedidos.pinturasacuario.mx/')
            ->salutation("Reciba un cordial saludo de parte de Pinturas Acuario.");                
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
