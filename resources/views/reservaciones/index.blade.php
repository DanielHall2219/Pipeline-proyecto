@extends('layouts.app')

@section('title', 'Reservaciones')

@section('content')
<style>
    .container-reservas {
        padding: 30px;
        color: white;
        position: relative;
    }

    h2 {
        color: #00bcd4;
        text-align: center;   
        margin-bottom: 25px;
    }

    .mesas-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        max-width: 700px;
        margin: 0 auto 50px auto;
    }

    .mesa {
        background-color: #4caf50;
        border: none;
        color: white;
        padding: 16px;
        text-align: center;
        border-radius: 10px;
        font-weight: bold;
        font-size: 14px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        transition: transform 0.2s ease-in-out;
    }

    .mesa:hover { transform: scale(1.05); }

    .mesa.ocupada { background-color: #e53935; }

    .mesa form { margin-top: 10px; }

    .mesa form button {
        padding: 8px 12px;
        background-color: #00bcd4;
        border: none;
        border-radius: 6px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        font-size: 12px;
        width: 100%;
    }

    .mesa form button:hover { background-color: #0097a7; }

    .acciones {
        position: absolute;
        right: 50px;
        top: 110px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .acciones a button {
        padding: 10px 18px;
        background-color: #00bcd4;
        border: none;
        border-radius: 6px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.2s;
        width: 100%;
    }

    .acciones a button:hover { background-color: #0097a7; }

    /* === Filtro de fecha en la columna derecha === */
    .input-fecha {
        padding: 8px 10px;
        border-radius: 6px;
        border: none;
        background-color: #2a2a2a;
        color: #fff;
        width: 160px;
    }
    .btn-acciones {
        padding: 10px 18px;
        background-color: #00bcd4;
        border: none;
        border-radius: 6px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.2s;
        width: 100%;
    }
    .btn-acciones:hover { background-color: #0097a7; }
    .acciones > * { margin-bottom: 10px; }
    /* ============================================ */

    table {
        width: 100%;
        margin-top: 40px;
        border-collapse: collapse;
        background-color: #1e1e1e;
    }

    th, td {
        padding: 12px 15px;
        text-align: center;
        border-bottom: 1px solid #444;
        color: white;
    }

    th { background-color: #333; }

    .btn-table {
        padding: 6px 12px;
        font-size: 12px;
        background-color: #00bcd4;
        border: none;
        border-radius: 4px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        margin-right: 5px;
    }

    .btn-table:hover { background-color: #0097a7; }

    .btn-delete { background-color: #ff5252; }
    .btn-delete:hover { background-color: #e53935; }

    .flash { max-width: 700px; margin: 0 auto 20px auto; }
    .flash .ok { background: #2e7d32; padding: 10px 12px; border-radius: 6px; }
    .flash .err { background: #c62828; padding: 10px 12px; border-radius: 6px; }
</style>

<div class="container-reservas">
    {{-- Mensajes --}}
    <div class="flash">
        @if(session('success')) <div class="ok">{{ session('success') }}</div> @endif
        @if(session('error'))   <div class="err">{{ session('error') }}</div>   @endif
        @if($errors->any())     <div class="err">{{ $errors->first() }}</div>   @endif
    </div>

    <h2>Mesas Disponibles</h2>

    {{-- Grilla de mesas --}}
    <div class="mesas-grid">
        @foreach($mesas as $mesa)
            @php
                $ocupada = in_array($mesa->id_mesa, $ocupadasHoy ?? []);
                // Detectar si la ocupación activa es walk-in (Cliente en sitio/sin-reserva@local)
                $hayWalkinActiva = $mesa->reservaciones
                    ->filter(function($r){
                        $nombre = optional($r->cliente)->nombre_cliente ?? '';
                        $correo = optional($r->cliente)->correo ?? '';
                        return ($r->estado === 'activa') && (
                            $nombre === 'Cliente en sitio' ||
                            $correo === '' ||
                            $correo === 'sin-reserva@local'
                        );
                    })->count() > 0;
            @endphp

            <div class="mesa {{ $ocupada ? 'ocupada' : '' }}">
                {{ $mesa->numero_mesa }}

                @if($ocupada)
                    @if($hayWalkinActiva)
                        {{-- Liberar mesa (libera walk-in activos de ese día) --}}
                        <form action="{{ route('reservaciones.liberarDia') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="mesa_id" value="{{ $mesa->id_mesa }}">
                            <input type="hidden" name="fecha" value="{{ $fecha }}">
                            <button type="submit">Liberar</button>
                        </form>
                    @else
                        {{-- Ocupada por reservación real: no se libera desde el grid --}}
                        <div style="margin-top:10px;font-size:12px;opacity:.85;">Ocupada por reservación</div>
                    @endif
                @else
                    {{-- Ocupar rápido (walk-in) --}}
                    <form action="{{ route('reservaciones.ocuparDia') }}" method="POST" class="form-ocupar">
                        @csrf
                        <input type="hidden" name="mesa_id" value="{{ $mesa->id_mesa }}">
                        <input type="hidden" name="fecha" value="{{ $fecha }}">
                        {{-- Hora local del dispositivo (se llena por JS en HH:mm) --}}
                        <input type="hidden" name="hora" class="hora-local">
                        <button type="submit">Ocupar</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Columna derecha de acciones (filtro de fecha + botones globales) --}}
    <div class="acciones">
        <form method="GET" action="{{ route('reservaciones.index') }}" id="form-fecha">
            <input type="date" name="fecha" value="{{ $fecha }}" class="input-fecha" onchange="this.form.submit()">
            {{-- Si ya estás en modo consultar, preserva ese query al cambiar la fecha --}}
            @if(request()->has('consultar'))
                <input type="hidden" name="consultar" value="1">
            @endif
        </form>

        <a href="{{ route('reservaciones.create', ['fecha'=>$fecha]) }}">
            <button class="btn-acciones">Registrar</button>
        </a>

        <a href="{{ request()->has('consultar')
                    ? route('reservaciones.index',['fecha'=>$fecha])
                    : route('reservaciones.index', ['consultar' => 1, 'fecha'=>$fecha]) }}">
            <button class="btn-acciones">
                {{ request()->has('consultar') ? 'Ocultar' : 'Consultar' }}
            </button>
        </a>
    </div>

    @if(request()->has('consultar'))
        <h3 style="text-align:center; margin-top: 40px; color: #00bcd4;">
            Reservaciones Activas ({{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }})
        </h3>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Correo</th>
                    <th>Mesa</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Personas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservaciones as $reservacion)
                    @php
                        $nombre = optional($reservacion->cliente)->nombre_cliente ?? '';
                        $correo = optional($reservacion->cliente)->correo ?? '';
                        $esWalkin = ($nombre === 'Cliente en sitio') || ($correo === '') || ($correo === 'sin-reserva@local');
                    @endphp
                    <tr>
                        <td>{{ $reservacion->cliente->nombre_cliente }}</td>
                        <td>{{ $reservacion->cliente->correo }}</td>
                        <td>{{ $reservacion->mesa->numero_mesa }}</td>
                        <td>{{ $reservacion->fecha }}</td>
                        <td>{{ $reservacion->hora }}</td>
                        <td>{{ $reservacion->num_personas }}</td>
                        <td>
                            <a href="{{ route('reservaciones.edit', ['id' => $reservacion->id_reservacion]) }}">
                                <button class="btn-table">Editar</button>
                            </a>

                            {{-- LIBERAR: disponible para cualquier reservación ACTIVA (walk-in o real) --}}
                            @if($reservacion->estado === 'activa')
                                <form action="{{ route('reservaciones.liberarReserva', ['id' => $reservacion->id_reservacion]) }}"
                                      method="POST" style="display:inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-table">Liberar</button>
                                </form>
                            @endif

                            {{-- CANCELAR: sólo para reservas reales activas --}}
                            @if($reservacion->estado === 'activa' && ! $esWalkin)
                                <form action="{{ route('reservaciones.destroy', ['id' => $reservacion->id_reservacion]) }}"
                                      method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-table btn-delete"
                                            onclick="return confirm('¿Deseas cancelar esta reservación?')">Cancelar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No hay reservaciones activas para este día.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>

{{-- JS: poner hora local en formularios de Ocupar --}}
<script>
(function () {
    function horaLocalHHMM() {
        const d = new Date();
        const hh = String(d.getHours()).padStart(2, '0');
        const mm = String(d.getMinutes()).padStart(2, '0');
        return `${hh}:${mm}`;
    }
   
    document.querySelectorAll('form.form-ocupar').forEach(function (form) {
        form.addEventListener('submit', function () {
            const inputHora = form.querySelector('input.hora-local');
            if (inputHora) inputHora.value = horaLocalHHMM();
        });
    });
})();
</script>
@endsection
