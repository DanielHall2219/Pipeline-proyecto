@extends('layouts.app')

@section('title', 'Editar Reservación')

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
        background-color: #2a2a2a;
        color: white;
    }

    .btn-submit {
        width: 100%;
        background-color: #00bcd4;
        border: none;
        color: white;
        padding: 12px;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background-color: #0097a7;
    }

    .btn-cancel {
        background-color: #555;
        margin-top: 10px;
        text-align: center;
        display: inline-block;
        padding: 10px 20px;
        border-radius: 6px;
        color: white;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background-color: #777;
    }
</style>

<div class="form-container">
    <h2>Editar Reservación</h2>
    <form action="{{ route('reservaciones.update', $reservacion->id_reservacion) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre_cliente">Nombre del cliente</label>
            <input type="text" name="nombre_cliente" value="{{ $reservacion->cliente->nombre_cliente }}" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo del cliente</label>
            <input type="email" name="correo" value="{{ $reservacion->cliente->correo }}" required>
        </div>

        <div class="form-group">
            <label for="mesa_id">Mesa asignada</label>
            <select name="mesa_id" required>
                @foreach($mesas as $mesa)
                    <option value="{{ $mesa->id_mesa }}" {{ $mesa->id_mesa == $reservacion->id_mesa ? 'selected' : '' }}>
                        {{ $mesa->numero_mesa }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" value="{{ $reservacion->fecha }}" required>
        </div>

        <div class="form-group">
            <label for="hora">Hora</label>
            <input type="time" name="hora" value="{{ $reservacion->hora }}" required>
        </div>

        <div class="form-group">
            <label for="num_personas">Número de personas</label>
            <input type="number" name="num_personas" min="1" value="{{ $reservacion->num_personas }}" required>
        </div>

        <button type="submit" class="btn-submit">Guardar cambios</button>
        <a href="{{ route('reservaciones.index') }}" class="btn-cancel">Cancelar</a>
    </form>
</div>
@endsection
