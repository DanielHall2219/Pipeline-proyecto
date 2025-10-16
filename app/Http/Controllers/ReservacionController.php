<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\Cliente;
use App\Models\Reservacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmacionReservacion;
use App\Mail\ModificacionReservacion;

class ReservacionController extends Controller
{
    public function index(Request $request)
    {
       
        $fecha = $request->query('fecha') ?: now()->toDateString();

        $mesas = Mesa::with(['reservaciones' => function($q) use ($fecha){
            $q->whereDate('fecha', $fecha)->where('estado','activa');
        }, 'reservaciones.cliente'])
        ->get()

        ->sortBy(function($m){
            return (int) preg_replace('/\D+/', '', (string) $m->numero_mesa);
        })
        ->values();

 
        $ocupadasHoy = $mesas->flatMap(function($m){
            return $m->reservaciones->pluck('id_mesa');
        })->unique()->values()->all();


        $reservaciones = null;
        if ($request->query('consultar')) {
            $reservaciones = Reservacion::with('cliente','mesa')
                ->whereDate('fecha', $fecha)
                ->where('estado','activa')
                ->orderBy('hora')
                ->get();
        }

        return view('reservaciones.index', compact('mesas','reservaciones','fecha','ocupadasHoy'));
    }

    public function create(Request $request)
    {
        $fecha = $request->query('fecha') ?: now()->toDateString();

    
        $ocupadas = Reservacion::whereDate('fecha', $fecha)
            ->where('estado','activa')
            ->pluck('id_mesa')
            ->toArray();

        
        $mesasDisponibles = Mesa::whereNotIn('id_mesa', $ocupadas)
            ->get()
            ->sortBy(function($m){
                return (int) preg_replace('/\D+/', '', (string) $m->numero_mesa);
            })
            ->values();

        return view('reservaciones.create', compact('mesasDisponibles','fecha'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_cliente' => 'required|string|max:100',
            'correo'         => 'required|email|max:100',
            'mesa_id'        => 'required|exists:mesas,id_mesa',
            'fecha'          => 'required|date',
            'hora'           => 'required',
            'num_personas'   => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            $existe = Reservacion::where('id_mesa', $request->mesa_id)
                ->whereDate('fecha', $request->fecha)
                ->where('estado','activa')
                ->exists();

            if ($existe) {
                return back()->withErrors('La mesa ya está reservada para ese día.')->withInput();
            }

            $cliente = Cliente::create([
                'nombre_cliente' => $request->nombre_cliente,
                'correo'         => $request->correo
            ]);

            Reservacion::create([
                'id_cliente'   => $cliente->id_cliente,
                'id_mesa'      => $request->mesa_id,
                'fecha'        => $request->fecha,
                'hora'         => $request->hora,
                'num_personas' => $request->num_personas,
                'estado'       => 'activa'
            ]);

            DB::commit();

            $detalles = [
                'nombre'   => $cliente->nombre_cliente,
                'correo'   => $cliente->correo,
                'fecha'    => $request->fecha,
                'hora'     => $request->hora,
                'mesa'     => $request->mesa_id,
                'personas' => $request->num_personas
            ];
            Mail::to($cliente->correo)->send(new ConfirmacionReservacion($detalles));

            return redirect()->route('reservaciones.index', ['fecha'=>$request->fecha])
                ->with('success', 'Reservación registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('reservaciones.index')->with('error', 'Ocurrió un error al registrar la reservación.');
        }
    }

    public function edit($id)
    {
        $reservacion = Reservacion::with('cliente', 'mesa')->findOrFail($id);

        $mesas = Mesa::where('estado', 'disponible')
            ->orWhere('id_mesa', $reservacion->id_mesa)
            ->get()
            ->sortBy(function($m){
                return (int) preg_replace('/\D+/', '', (string) $m->numero_mesa);
            })
            ->values();

        return view('reservaciones.edit', compact('reservacion', 'mesas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_cliente' => 'required|string|max:100',
            'correo'         => 'required|email|max:100',
            'mesa_id'        => 'required|exists:mesas,id_mesa',
            'fecha'          => 'required|date',
            'hora'           => 'required',
            'num_personas'   => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            $reservacion = Reservacion::with('cliente')->findOrFail($id);
            $cliente = $reservacion->cliente;

            $existe = Reservacion::where('id_mesa', $request->mesa_id)
                ->whereDate('fecha', $request->fecha)
                ->where('estado','activa')
                ->where('id_reservacion','<>',$reservacion->id_reservacion)
                ->exists();

            if ($existe) {
                return back()->withErrors('La mesa ya está reservada para ese día.')->withInput();
            }

            $cliente->update([
                'nombre_cliente' => $request->nombre_cliente,
                'correo'         => $request->correo
            ]);

            $reservacion->update([
                'id_mesa'      => $request->mesa_id,
                'fecha'        => $request->fecha,
                'hora'         => $request->hora,
                'num_personas' => $request->num_personas
            ]);

            DB::commit();

            $detalles = [
                'nombre'   => $cliente->nombre_cliente,
                'correo'   => $cliente->correo,
                'fecha'    => $request->fecha,
                'hora'     => $request->hora,
                'mesa'     => $request->mesa_id,
                'personas' => $request->num_personas
            ];
            Mail::to($cliente->correo)->send(new ModificacionReservacion($detalles));

            return redirect()->route('reservaciones.index', ['fecha'=>$request->fecha])
                ->with('success', 'Reservación actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('reservaciones.index')->with('error', 'Ocurrió un error al actualizar la reservación.');
        }
    }

    public function destroy($id)
    {
        $reservacion = Reservacion::findOrFail($id);
        $reservacion->update(['estado' => 'cancelada']);

        return redirect()->route('reservaciones.index', [
            'consultar' => 1,
            'fecha'     => $reservacion->fecha
        ])->with('success', 'Reservación cancelada correctamente.');
    }

    public function liberarDia(Request $request)
    {
        $request->validate([
            'mesa_id' => 'required|exists:mesas,id_mesa',
            'fecha'   => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            $liberadas = Reservacion::where('id_mesa', $request->mesa_id)
                ->whereDate('fecha', $request->fecha)
                ->where('estado', 'activa')
                ->whereHas('cliente', function ($q) {
                    $q->where('nombre_cliente', 'Cliente en sitio')
                      ->orWhere('correo', '')
                      ->orWhere('correo', 'sin-reserva@local');
                })
                ->update(['estado' => 'liberada']);

            DB::commit();

            if ($liberadas === 0) {
                return back()->with('error', 'No hay ocupación walk-in para liberar. Si la mesa está ocupada por una reservación de cliente, usa "Cancelar".');
            }

            return redirect()->route('reservaciones.index', ['fecha' => $request->fecha])
                ->with('success', "Mesa liberada (walk-in). Registros liberados: {$liberadas}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error','No se pudo liberar la mesa.');
        }
    }


    public function liberarWalkin($id)
    {
        $res = Reservacion::with('cliente')->findOrFail($id);

        $esWalkin = $res->estado === 'activa' && (
            optional($res->cliente)->nombre_cliente === 'Cliente en sitio' ||
            optional($res->cliente)->correo === '' ||
            optional($res->cliente)->correo === 'sin-reserva@local'
        );

        if (! $esWalkin) {
            return back()->with('error', 'Solo puedes liberar ocupaciones walk-in activas. Las reservas reales se cancelan.');
        }

        $res->update(['estado' => 'liberada']);

        return redirect()->route('reservaciones.index', [
            'consultar' => 1,
            'fecha'     => $res->fecha
        ])->with('success', 'Ocupación walk-in liberada.');
    }

    public function liberarReserva($id)
    {
        $res = Reservacion::findOrFail($id);

        if ($res->estado !== 'activa') {
            return back()->with('error', 'Solo se pueden liberar reservaciones activas.');
        }

        $res->update(['estado' => 'liberada']);

        return redirect()->route('reservaciones.index', [
            'consultar' => 1,
            'fecha'     => $res->fecha
        ])->with('success', 'Reservación marcada como liberada.');
    }


    public function ocuparDia(Request $request)
    {
        $request->validate([
            'mesa_id'       => 'required|exists:mesas,id_mesa',
            'fecha'         => 'required|date',
            'hora'          => 'nullable|date_format:H:i',
            'num_personas'  => 'nullable|integer|min:1'
        ]);

        $fecha = $request->fecha;
        $hora  = $request->hora ?: now()->format('H:i');

        // Si ya está ocupada para ese día, no duplicar
        $existe = Reservacion::where('id_mesa', $request->mesa_id)
            ->whereDate('fecha', $fecha)
            ->where('estado','activa')
            ->exists();

        if ($existe) {
            return back()->with('error', 'La mesa ya está reservada/ocupada para esa fecha.');
        }

        DB::beginTransaction();
        try {
            // Cliente genérico para atención en sitio
            $cliente = Cliente::create([
                'nombre_cliente' => 'Cliente en sitio',
                'correo'         => 'sin-reserva@local'
            ]);

            Reservacion::create([
                'id_cliente'   => $cliente->id_cliente,
                'id_mesa'      => $request->mesa_id,
                'fecha'        => $fecha,
                'hora'         => $hora,
                'num_personas' => $request->num_personas ?? 1,
                'estado'       => 'activa'
            ]);

            DB::commit();

            return redirect()->route('reservaciones.index', ['fecha'=>$fecha])
                ->with('success', 'Mesa marcada como ocupada para la fecha seleccionada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo ocupar la mesa.');
        }
    }
}
