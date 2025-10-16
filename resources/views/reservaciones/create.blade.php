@extends('layouts.app')

@section('title', 'Registrar Reservación')

@section('content')
<style>
    .form-container {
        max-width: 600px;
        margin: 50px auto;
        background-color: #1e1e1e;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        color: white;
    }

    .form-container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #00bcd4;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="time"],
    input[type="number"],
    select {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: none;
        background-color: #2c2c2c;
        color: white;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-guardar {
        background-color: #00bcd4;
        color: white;
    }

    .btn-cancelar {
        background-color: #ff5252;
        color: white;
    }
</style>

@php
    // A) Precargar valores si vienes desde el tablero
    //    - $fecha viene del controller; si no, toma ?fecha de la URL o hoy.
    $fechaSeleccionada = isset($fecha) ? $fecha : (request('fecha') ?: now()->toDateString());
    //    - Si se pasó mesa_id por query (desde el botón "Reservar") la preseleccionamos.
    $mesaPre = request('mesa_id');
@endphp

<div class="form-container">
    <h2>Registrar Reservación</h2>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservaciones.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nombre_cliente">Nombre del cliente</label>
            <input type="text" id="nombre_cliente" name="nombre_cliente" value="{{ old('nombre_cliente') }}" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo del cliente</label>
            <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required>
        </div>

        <div class="form-group">
            <label for="mesa_id">Mesa disponible</label>
            <select id="mesa_id" name="mesa_id" required>
                <option value="">Seleccionar...</option>
                @foreach($mesasDisponibles as $mesa)
                    <option
                        value="{{ $mesa->id_mesa }}"
                        {{ (old('mesa_id') == $mesa->id_mesa || (int)$mesaPre === (int)$mesa->id_mesa) ? 'selected' : '' }}>
                        {{ $mesa->numero_mesa }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="fecha">Fecha</label>
            {{-- B) Usar la fecha seleccionada/pre-cargada --}}
            <input type="date" id="fecha" name="fecha" value="{{ old('fecha', $fechaSeleccionada) }}" required>
        </div>

        <div class="form-group">
            <label for="hora">Hora</label>
            <input type="time" id="hora" name="hora" value="{{ old('hora') }}" required>
        </div>

        <div class="form-group">
            <label for="num_personas">Número de personas</label>
            <input type="number" id="num_personas" name="num_personas" min="1" value="{{ old('num_personas') }}" required>
        </div>

        <div class="form-actions">
            <a href="{{ route('reservaciones.index', ['fecha' => $fechaSeleccionada]) }}" class="btn btn-cancelar">Cancelar</a>
            <button type="submit" class="btn btn-guardar">Guardar</button>
        </div>
    </form>
</div>

<script>
  
    document.addEventListener('DOMContentLoaded', function () {
        const inputFecha = document.getElementById('fecha');
        const today = new Date().toISOString().split('T')[0];
        inputFecha.setAttribute('min', today);
    });
</script>
@endsection
