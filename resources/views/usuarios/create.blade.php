@extends('layouts.app')

@section('title', 'Registrar Usuario')

@section('content')
<style>
    .form-container {
        max-width: 600px;
        margin: 50px auto;
        background-color: #1e1e1e;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        color: white;
    }

    .form-container h2 {
        margin-bottom: 25px;
        color: #00bcd4;
        text-align: center;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #ccc;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: none;
        border-radius: 6px;
        background-color: #2c2c2c;
        color: white;
    }

    input::placeholder,
    select {
        color: #aaa;
    }

    .btn-container {
        text-align: right;
    }

    .btn-cancelar {
        background-color: #616161;
        color: white;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        margin-right: 10px;
        transition: background 0.3s;
    }

    .btn-cancelar:hover {
        background-color: #424242;
    }

    .btn-guardar {
        background-color: #00bcd4;
        color: white;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-guardar:hover {
        background-color: #0097a7;
    }

    .alert-danger {
        background-color: #b71c1c;
        padding: 12px;
        border-radius: 6px;
        color: white;
        margin-bottom: 20px;
    }
</style>

<div class="form-container">
    <h2>Registrar nuevo usuario</h2>

    @if ($errors->any())
        <div class="alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <label for="nombre">Nombre y apellido del empleado</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>

        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario" value="{{ old('usuario') }}" required>

        {{-- Nuevo campo correo --}}
        <label for="correo">Correo</label>
        <input type="email" name="correo" id="correo" value="{{ old('correo') }}" placeholder="ejemplo@dominio.com">

        <label for="contrasena">Contraseña</label>
        <input type="password" name="contrasena" id="contrasena" placeholder="******" required>

        <label for="id_rol">Rol</label>
        <select name="id_rol" id="id_rol" required>
            <option value="">Seleccione un rol</option>
            @foreach ($roles as $rol)
                <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>
                    {{ ucfirst($rol->nombre_rol) }}
                </option>
            @endforeach
        </select>

        <div class="btn-container">
            <a href="{{ route('usuarios.index') }}" class="btn-cancelar">Cancelar</a>
            <button type="submit" class="btn-guardar">Guardar usuario</button>
        </div>
    </form>
</div>
@endsection
