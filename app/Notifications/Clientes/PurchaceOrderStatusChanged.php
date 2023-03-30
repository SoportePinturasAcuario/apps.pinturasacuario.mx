<?php

namespace Apps\Notifications\Clientes;

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
            ->subject('Pedido #' . $this->salesOrder->id . ': ' . $this->salesOrder->status->name)
            ->greeting('¡Hola!')
            ->line('El estado de su pedido #' . $this->salesOrder->id . ' se ha actualizado. Puedes ver los detalles haciendo click en el siguiente botón.')
            ->action('Ver pedido',
                'https://spa.pinturasacuario.mx/auth/#/login?to=https://spa.pinturasacuario.mx/clientes/%23/perfil/cliente/compra/' . $this->salesOrder->id)
            ->salutation("Recibe un cordial saludo de parte de Pinturas Acuario.");                    
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
