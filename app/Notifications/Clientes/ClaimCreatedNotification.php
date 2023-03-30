<?php

namespace Apps\Notifications\Clientes;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ClaimCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($claim, $status, $user)
    {
        $this->claim = $claim;
        $this->status = $status;
        $this->user = $user;
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
            ->subject('Nueva reclamación: #' . $this->claim->folio)
            ->greeting('¡Hola!')
            ->line('Se ha publicado una nueva reclamación')
            ->line('Usuario: ' . $this->user->get('name'))
            ->line('Estado actual: ' . $this->status->get('label') . '. Puedes ver los detalles haciendo click en el siguiente botón.')
            ->action('Ver',
                'https://spa.pinturasacuario.mx/auth')
            ->salutation("¡Saludos!");  
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
