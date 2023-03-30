<?php

namespace Apps\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WasteRejectedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($waste)
    {
        $this->waste = $waste;
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
            ->subject('Reporte de desecho: ' . $this->waste->id . ' rechazado.')
            ->greeting('¡Hola!')
            ->line('Usted ha recibido este correo electrónico porque la solicitud asociada al reporte de desecho con folio: ' . $this->waste->id . ' fue rechazada.')
            ->action('Ver reporte', 'https://spa.pinturasacuario.mx/formularios/02/#/waste/' . $this->waste->id . '/show')
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
