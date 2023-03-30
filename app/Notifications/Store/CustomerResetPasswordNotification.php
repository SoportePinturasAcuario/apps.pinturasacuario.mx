<?php

namespace Apps\Notifications\Store;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->subject('Solicitud para recuperar contraseña')
            ->greeting('¡Hola!')
            ->line('Usted ha recibido este mensaje a partir de una solicitud de recuperación de contraseña para la cuenta asociada a esta dirección de correo electrónico.')
            ->line('Haga click en el siguiente botón para recuperar su contraseña.')
            ->action('Recuperar Contraseña', 'https://pedidos.pinturasacuario.mx/#/password/actualizar/' . $this->token)
            ->line('Usted cuenta con 1 hora para actualizar la recuperación de contraseña a partir del envió de esta solicitud. De lo contrario, tendrá que realizar una nueva solicitud.')
            ->line('Si usted no ha realizado esta solicitud. Puede ignorar este mensaje.')
            ->salutation("Reciba un cordial saludo por parte de Pinturas Acuario.");
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
