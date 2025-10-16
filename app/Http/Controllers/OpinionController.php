<?php
namespace App\Http\Controllers;

use App\Models\Opinion;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    public function index()
    {
        $opiniones = Opinion::orderBy('fecha','desc')->paginate(10);
        $promedio  = Opinion::avg('calificacion');
        return view('opiniones.index', compact('opiniones','promedio'));
    }

    public function create()
    {
        return view('opiniones.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'calificacion' => ['required','integer','between:1,5'],
            'comentario'   => ['required','string','min:3','max:1000'],
            'id_cliente'   => ['nullable','integer'],
        ]);

        $data['fecha'] = now(); // usa tu campo fecha
        Opinion::create($data);

        return redirect()->route('opiniones.index')->with('ok','¡Opinión registrada!');
    }
}
