<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModificacionReservacion extends Mailable
{
    use Queueable, SerializesModels;

    public $detalles;

    public function __construct($detalles)
    {
        $this->detalles = $detalles;
    }

    public function build()
    {
        return $this->subject('Modificación de Reservación')
                    ->view('emails.modificacion_reservacion')
                    ->with('detalles', $this->detalles);
    }
}
