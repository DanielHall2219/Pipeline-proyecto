<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\MovimientosInventario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventarioController extends Controller
{
    public function index()
    {
        $mostrarDesactivados = request()->query('desactivados') === '1';

        $productos = Inventario::where('estado', $mostrarDesactivados ? 'desactivado' : 'activo')->get();

        return view('inventario.index', compact('productos'));
    }

    public function create()
    {
        return view('inventario.create');
    }

public function store(Request $request)
{
    $request->validate([
        'nombre_producto' => 'required|string|max:100',
        'descripcion' => 'nullable|string',
        'cantidad' => 'required|integer|min:0',
        'unidad_medida' => 'required|string|max:50',
        'fecha_ingreso' => 'required|date',
        'stock_minimo' => 'required|integer|min:1',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $datos = $request->all();
    $datos['estado'] = 'activo';

    if ($request->hasFile('imagen')) {
        $ruta = $request->file('imagen')->store('imagenes', 'public');
        $datos['imagen'] = $ruta;
    }

    $producto = Inventario::create($datos);

    MovimientosInventario::create([
        'id_producto' => $producto->id_producto,
        'tipo_movimiento' => 'entrada',
        'cantidad' => $producto->cantidad,
        'fecha_movimiento' => now(),
        'realizado_por' => Auth::id()
    ]);

    return redirect()->route('inventario.index')->with('success', 'Producto registrado.');
}


    public function edit($id)
    {
        $producto = Inventario::findOrFail($id);
        return view('inventario.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $producto = Inventario::findOrFail($id);

        $request->validate([
            'nombre_producto' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|integer|min:0',
            'unidad_medida' => 'required|string|max:50',
            'fecha_ingreso' => 'required|date',
            'stock_minimo' => 'required|integer|min:1',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $datos = $request->all();

        if ($request->hasFile('imagen')) {
            $ruta = $request->file('imagen')->store('imagenes', 'public');
            $datos['imagen'] = $ruta;
        }

        $producto->update($datos);

        return redirect()->route('inventario.index')->with('success', 'Producto actualizado.');
    }

    public function reducir(Request $request, $id)
    {
        $producto = Inventario::findOrFail($id);

        $request->validate([
            'cantidad_reducir' => 'required|integer|min:1|max:' . $producto->cantidad
        ]);

        $cantidad = $request->cantidad_reducir;
        $producto->cantidad -= $cantidad;
        $producto->save();

        MovimientosInventario::create([
            'id_producto' => $producto->id_producto,
            'tipo_movimiento' => 'salida',
            'cantidad' => $cantidad,
            'fecha_movimiento' => now(),
            'realizado_por' => Auth::id()
        ]);

        return redirect()->route('inventario.index')->with('success', 'Stock reducido correctamente.');
    }

    public function entrada(Request $request, $id)
    {
        $producto = Inventario::findOrFail($id);

        $request->validate([
            'cantidad_entrada' => 'required|integer|min:1'
        ]);

        $cantidad = $request->cantidad_entrada;
        $producto->cantidad += $cantidad;
        $producto->save();

        MovimientosInventario::create([
            'id_producto' => $producto->id_producto,
            'tipo_movimiento' => 'entrada',
            'cantidad' => $cantidad,
            'fecha_movimiento' => now(),
            'realizado_por' => Auth::id()
        ]);

        return redirect()->route('inventario.index')->with('success', 'Stock actualizado con nueva entrada.');
    }

    public function desactivar($id)
    {
        $producto = Inventario::findOrFail($id);

        if ($producto->cantidad > 0) {
            return redirect()->route('inventario.index')->with('error', 'No se puede desactivar un producto con stock mayor a cero.');
        }

        $producto->estado = 'desactivado';
        $producto->save();

        return redirect()->route('inventario.index')->with('success', 'Producto desactivado.');
    }

    public function activar($id)
    {
        $producto = Inventario::findOrFail($id);
        $producto->estado = 'activo';
        $producto->save();

        return redirect()->route('inventario.index')->with('success', 'Producto activado nuevamente.');
    }
}
