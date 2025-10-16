<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

class NuevaClaveController extends Controller
{
    public function formulario(string $token, Request $request)
    {
        $correo = $request->query('correo');

        if ($correo) {
           
            $row = DB::table('crear_token')
                ->where('email', $correo)
                ->first();

            
            if (!$row || !Hash::check($token, $row->token)) {
                return redirect()
                    ->route('login')
                    ->withErrors(['enlace' => 'El enlace es inválido, ya fue usado o venció.']);
            }
        }

        return view('recuperacion.nueva-clave', [
            'token'  => $token,
            'correo' => $correo,
        ]);
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'token'  => ['required'],
            'correo' => ['required','email'],
            'clave'  => ['required','confirmed','min:6'],
        ]);

        $estado = Password::reset(
            [
                'correo'                => $request->correo,
                'password'              => $request->clave,
                'password_confirmation' => $request->clave_confirmation,
                'token'                 => $request->token,
            ],
            function ($usuario) use ($request) {
                $usuario->contrasena = Hash::make($request->clave);
                $usuario->save();
                event(new PasswordReset($usuario));
            }
        );

        return $estado === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Clave actualizada correctamente.')
            : back()->withErrors(['correo' => 'No se pudo restablecer la clave.']);
    }
}
