@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<style>
    .container {
        color: white;
        max-width: 700px;
        margin: 40px auto;
        padding: 20px;
        background-color: #1e1e1e;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    }

    h2 {
        color: #00bcd4;
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-top: 12px;
        font-weight: bold;
    }

    input, select {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border-radius: 6px;
        border: none;
        background-color: #2c2c2c;
        color: white;
    }

    input::placeholder {
        color: #bbb;
    }

    .acciones {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-cancelar, .btn-guardar {
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        border: none;
        cursor: pointer;
    }

    .btn-cancelar {
        background-color: #757575;
        color: white;
    }

    .btn-cancelar:hover {
        background-color: #616161;
    }

    .btn-guardar {
        background-color: #00bcd4;
        color: white;
    }

    .btn-guardar:hover {
        background-color: #0097a7;
    }

    .alert {
        background-color: #ef5350;
        color: white;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 6px;
    }
</style>

<div class="container">
    <h2>Editar Usuario</h2>

    @if ($errors->any())
        <div class="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.update', $usuario->id_usuario) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>

        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" value="{{ old('usuario', $usuario->usuario) }}" required>

        {{-- Campo de correo --}}
        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo', $usuario->correo) }}" placeholder="ejemplo@dominio.com">

        <label for="contrasena">Nueva Contraseña (opcional)</label>
        <input type="password" id="contrasena" name="contrasena" placeholder="Dejar en blanco para no cambiar">

        <label for="id_rol">Rol</label>
        <select name="id_rol" id="id_rol" required>
            <option value="">Seleccione un rol</option>
            @foreach ($roles as $rol)
                <option value="{{ $rol->id_rol }}" {{ $usuario->id_rol == $rol->id_rol ? 'selected' : '' }}>
                    {{ ucfirst($rol->nombre_rol) }}
                </option>
            @endforeach
        </select>

        <div class="acciones">
            <a href="{{ route('usuarios.index') }}" class="btn-cancelar">Cancelar</a>
            <button type="submit" class="btn-guardar">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection
