<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;

class MesaController extends Controller
{
    public function cambiarEstado($id)
    {
        $mesa = Mesa::findOrFail($id);

        $mesa->estado = ($mesa->estado === 'ocupada') ? 'disponible' : 'ocupada';
        $mesa->save();

        return redirect()->route('reservaciones.index')->with('success', 'Estado de la mesa actualizado correctamente.');
    }
}
