@extends('layouts.app')

@section('title', 'Opiniones')

@section('content')
<style>
    .container-op {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
        color: white;
        text-align: center;
    }

    .header-op {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-op h2 { color: #00bcd4; }

    .btn-group-op a button{
        background-color: #00bcd4;
        color: white;
        padding: 10px 15px;
        margin-left: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-group-op a button:hover { background-color: #0097a7; }

    .mensaje { margin-bottom: 20px; font-weight: bold; padding: 10px; border-radius: 6px; }
    .mensaje.success { color:#4caf50; background:#1e4620; }
    .mensaje.error   { color:#f44336; background:#4a2323; }

    .tabla-wrapper {
        background-color: #1e1e1e;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.4);
    }

    table.tabla-op {
        width: 100%;
        border-collapse: collapse;
        color: white;
    }

    .tabla-op th, .tabla-op td {
        padding: 12px 10px;
        border-bottom: 1px solid #333;
        text-align: left;
    }

    .tabla-op thead th {
        background: #242424;
        color: #ddd;
    }

    .star-yellow { color: #FFD700; }
    .star-gray   { color: gray; }

    .pie-op {
        display:flex; justify-content:space-between; align-items:center; margin-top:15px; flex-wrap:wrap; gap:10px;
    }
</style>

<div class="container-op">
    <div class="header-op">
        <h2>Calificación</h2>
        <div class="btn-group-op">
            <a href="{{ route('opiniones.crear') }}"><button>Agregar opinión</button></a>
            {{-- Botón opcional para reporte si luego lo implementas --}}
            {{-- <a href="#"><button>Generar reporte</button></a> --}}
        </div>
    </div>

    {{-- Mensajes --}}
    @if(session('success')) <div class="mensaje success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="mensaje error">{{ session('error') }}</div> @endif

    {{-- Promedio --}}
    <div style="margin-bottom:14px;">
        @php $prom = $promedio ?? 0; @endphp
        <strong>Promedio: {{ number_format($prom,1) }}</strong>
        @for($i=1;$i<=5;$i++)
            <span class="{{ $i<=round($prom) ? 'star-yellow' : 'star-gray' }}">&#9733;</span>
        @endfor
    </div>

    {{-- Tabla --}}
    <div class="tabla-wrapper">
        <table class="tabla-op">
            <thead>
                <tr>
                    <th style="width:140px;">Fecha</th>
                    <th style="width:180px;">Calificación</th>
                    <th>Comentarios sobre la experiencia</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opiniones as $op)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($op->fecha)->format('Y-m-d') }}</td>
                        <td>
                            @for($i=1;$i<=5;$i++)
                                <span class="{{ $i <= $op->calificacion ? 'star-yellow' : 'star-gray' }}">&#9733;</span>
                            @endfor
                        </td>
                        <td>{{ $op->comentario }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align:center;">Aún no hay opiniones registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pie-op">
        <a href="{{ route('opiniones.crear') }}" class="btn btn-primary">Nueva opinión</a>
        <div>{{ $opiniones->links() }}</div>
    </div>
</div>
@endsection
