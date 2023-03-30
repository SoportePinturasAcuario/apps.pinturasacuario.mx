<?php

namespace Apps\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class WasteAuthorizationRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');

        return (new MailMessage)
            ->subject('Notificación de restablecimiento de contraseña')
            ->greeting('Hola')
            ->line('Usted ha recibido este correo electrónico porque se ha solicitado el restablecimiento de contraseña para su cuenta.')
            ->action('Restablecer la contraseña', 'https://spa.pinturasacuario.mx/auth/#/updatepassword/')
            ->line('Si usted no solicitó un restablecimiento de contraseña ignore esta notificación.')
            ->salutation("¡Saludos!");        
    }
}
