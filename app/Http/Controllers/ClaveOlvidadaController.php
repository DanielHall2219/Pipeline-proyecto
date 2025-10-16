<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ClaveOlvidadaController extends Controller
{
    public function formulario()
    {
        return view('recuperacion.clave-olvidada');
    }

    public function enviar(Request $request)
    {
        $request->validate([
            'correo' => ['required','email'],
        ]);

        
        Password::sendResetLink($request->only('correo'));

        return redirect()
            ->route('login')
            ->with('estado', 'Si el correo existe, enviamos un enlace para restablecer tu clave.');
    }
}
