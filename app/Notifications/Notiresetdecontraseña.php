<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Notiresetdecontraseña extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        
        $url = url(
            route('clave.reset', ['token' => $this->token], false)
            . '?correo=' . urlencode($notifiable->correo)
        );

        return (new MailMessage)
            ->subject('Restablecer contraseña - Punto Marino')
            ->greeting('Hola ' . $notifiable->nombre)
            ->line('Recibiste este correo porque solicitaste restablecer tu contraseña.')
            ->action('Restablecer contraseña', $url)
            ->line('Si no solicitaste este cambio, ignora este mensaje.');
    }
}
