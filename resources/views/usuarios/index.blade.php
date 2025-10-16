@extends('layouts.app')

@section('title', 'Lista de Usuarios')

@section('content')
<style>
    .container {
        color: white;
        max-width: 1000px;
        margin: auto;
    }

    h1 {
        margin-top: 30px;
        color: #00bcd4;
        text-align: center;
    }

    .top-actions {
        margin: 25px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    input[type="text"] {
        padding: 8px;
        border-radius: 6px;
        border: none;
        background-color: #2c2c2c;
        color: white;
        width: 220px;
    }

    input::placeholder {
        color: #aaa;
    }

    .btn-search {
        padding: 8px 12px;
        background-color: #00bcd4;
        border: none;
        color: white;
        margin-left: 5px;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-search:hover {
        background-color: #0097a7;
    }

    .btn-create {
        background-color: #00bcd4;
        color: white;
        padding: 10px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-create:hover {
        background-color: #0097a7;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #1e1e1e;
        box-shadow: 0 8px 25px rgba(209, 203, 203, 0.1);
    }

    th, td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #444;
    }

    th {
        background-color: #262626;
        color: #00bcd4;
        font-weight: bold;
    }

    tr:hover {
        background-color: #2c2c2c;
    }

    .acciones a {
        color: #4fc3f7;
        margin-right: 8px;
        text-decoration: none;
        font-weight: bold;
    }

    .acciones a:hover {
        text-decoration: underline;
    }

    .acciones form {
        display: inline;
    }

    .acciones button {
        background: none;
        border: none;
        color: #ef5350;
        cursor: pointer;
        font-weight: bold;
    }

    .acciones button:hover {
        text-decoration: underline;
    }

    .mensaje {
        margin: 20px 0;
        color: #4caf50;
        font-weight: bold;
        text-align: center;
    }

    .sin-resultados {
        text-align: center;
        padding: 20px;
        color: #ccc;
    }
</style>

<div class="container">
    <h1>Lista de Usuarios</h1>

    @if(session('success'))
        <div class="mensaje">
            {{ session('success') }}
        </div>
    @endif

    <div class="top-actions">
        <form action="{{ route('usuarios.index') }}" method="GET">
            <input type="text" name="buscar" id="buscarInput" placeholder="Buscar por nombre, usuario o correo..." value="{{ request('buscar') }}">
            <button type="submit" class="btn-search">Buscar</button>
        </form>
        <a href="{{ route('usuarios.create') }}" class="btn-create">Registrar nuevo usuario</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Correo</th> {{-- nueva columna --}}
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id_usuario }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->usuario }}</td>
                    <td>{{ $usuario->correo ?? '—' }}</td> {{-- mostrar correo o guion --}}
                    <td>{{ $usuario->rol->nombre_rol ?? '—' }}</td>
                    <td class="acciones">
                        <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}">Editar</a>
                        <form action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" method="POST" onsubmit="return confirm('¿Eliminar usuario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="sin-resultados">No se encontraron usuarios.</td> {{-- ajustar colspan a 6 --}}
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('buscarInput');
        input.addEventListener('input', function () {
            if (this.value.trim() === '') {
                window.location.href = "{{ route('usuarios.index') }}";
            }
        });
    });
</script>
@endsection
