<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'contrasena' => 'required|string',
        ]);

        if (Auth::attempt([
            'usuario' => $request->usuario,
            'password' => $request->contrasena,
        ])) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'usuario' => 'Credenciales incorrectas.',
        ])->withInput();
    }

    public function dashboard()
    {
        $rol = Auth::user()->rol->nombre_rol;
        return view('dashboard', compact('rol'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
