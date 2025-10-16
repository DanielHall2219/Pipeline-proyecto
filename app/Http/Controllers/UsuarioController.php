<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
  
    public function index(Request $request)
    {
        $buscar = trim($request->buscar);

        $usuarios = Usuario::with('rol')
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', '%' . $buscar . '%')
                      ->orWhere('usuario', 'like', '%' . $buscar . '%')
                      ->orWhere('correo', 'like', '%' . $buscar . '%') 
                      ->orWhereHas('rol', function ($rolQuery) use ($buscar) {
                          $rolQuery->where('nombre_rol', 'like', '%' . $buscar . '%');
                      });
                });
            })
            ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    // Mostrar formulario de registro
    public function create()
    {
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|string|max:100',
            'usuario'    => 'required|string|max:100|unique:usuarios,usuario',
            'correo'     => 'nullable|email|max:100|unique:usuarios,correo', 
            'contrasena' => 'required|string|min:6',
            'id_rol'     => 'required|exists:roles,id_rol',
        ]);

        Usuario::create([
            'nombre'     => $request->nombre,
            'usuario'    => $request->usuario,
            'correo'     => $request->correo, 
            'contrasena' => Hash::make($request->contrasena),
            'id_rol'     => $request->id_rol,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }


    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles = Rol::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

   
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre'  => 'required|string|max:100',
            'usuario' => [
                'required','string','max:100',
                Rule::unique('usuarios','usuario')->ignore($usuario->id_usuario,'id_usuario'),
            ],
            'correo'  => [
                'nullable','email','max:100',
                Rule::unique('usuarios','correo')->ignore($usuario->id_usuario,'id_usuario'), 
            ],
            'contrasena' => 'nullable|string|min:6',
            'id_rol'  => 'required|exists:roles,id_rol',
        ]);

        $usuario->nombre  = $request->nombre;
        $usuario->usuario = $request->usuario;
        $usuario->correo  = $request->correo; 
        $usuario->id_rol  = $request->id_rol;

        if ($request->filled('contrasena')) {
            $usuario->contrasena = Hash::make($request->contrasena);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        Usuario::destroy($id);
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
}
