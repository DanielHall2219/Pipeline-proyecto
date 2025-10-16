@extends('layouts.app')

@section('title', 'Inventario')

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
        color: white;
        text-align: center;
    }

    .header-inventario {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-inventario h2 {
        color: #00bcd4;
    }

    .btn-group button {
        background-color: #00bcd4;
        color: white;
        padding: 10px 15px;
        margin-left: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-group button:hover {
        background-color: #0097a7;
    }

    .mensaje {
        margin-bottom: 20px;
        font-weight: bold;
        padding: 10px;
        border-radius: 6px;
    }

    .mensaje.success {
        color: #4caf50;
        background-color: #1e4620;
    }

    .mensaje.error {
        color: #f44336;
        background-color: #4a2323;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .card {
        background-color: #1e1e1e;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.4);
        position: relative;
    }

    .card img {
        width: 100px;
        margin-bottom: 15px;
        border-radius: 8px;
        object-fit: cover;
    }

    .card h4 {
        margin-bottom: 10px;
        color: #00bcd4;
    }

    .id-producto {
        font-size: 13px;
        color: #bbbbbb;
        margin-bottom: 4px;
    }

    .alerta {
        color: #ff5252;
        font-weight: bold;
        margin-top: 10px;
    }

    .acciones {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .acciones a,
    .acciones form button {
        color: white;
        background-color: #333;
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
    }

    .acciones a:hover,
    .acciones form button:hover {
        background-color: #555;
    }

    .form-inline {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 5px 0;
        gap: 6px;
        flex-wrap: wrap;
    }

    .form-inline input[type="number"] {
        width: 60px;
        padding: 5px;
        border-radius: 4px;
        border: none;
    }
</style>

<div class="container">
    <div class="header-inventario">
        <h2>Inventario de Productos</h2>

        @if(Auth::user()->rol->nombre_rol === 'admin')
        <div class="btn-group">
            <a href="{{ route('inventario.create') }}"><button>Ingresar nuevo producto</button></a>
            <a href="{{ request()->query('desactivados') === '1' ? route('inventario.index') : route('inventario.index', ['desactivados' => 1]) }}">
                <button>
                    {{ request()->query('desactivados') === '1' ? 'Ver productos activos' : 'Ver productos desactivados' }}
                </button>
            </a>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="mensaje success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mensaje error">{{ session('error') }}</div>
    @endif

    <div class="grid">
        @forelse ($productos as $producto)
            <div class="card">
                {{-- Imagen --}}
                @if($producto->imagen && file_exists(public_path('storage/' . $producto->imagen)))
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen del producto">
                @endif

                {{-- ID --}}
                <p class="id-producto">ID: {{ $producto->id_producto }}</p>

                {{-- Nombre y cantidad --}}
                <h4>{{ $producto->nombre_producto }}</h4>
                <p>Cantidad: {{ $producto->cantidad }}</p>

                {{-- Alerta de stock bajo --}}
                @if ($producto->cantidad <= $producto->stock_minimo)
                    <div class="alerta">Stock bajo</div>
                @endif

                {{-- ACCIONES SOLO PARA ADMIN --}}
                @if(Auth::user()->rol->nombre_rol === 'admin')
                    <div class="acciones">
                        <a href="{{ route('inventario.edit', $producto->id_producto) }}">Editar</a>
                    </div>

                    <div class="acciones">
                        @if($producto->estado === 'activo')
                            <form action="{{ route('inventario.reducir', $producto->id_producto) }}" method="POST" class="form-inline" onsubmit="return confirmarReduccion(this);">
                                @csrf
                                @method('PUT')
                                <input type="number" name="cantidad_reducir" min="1" max="{{ $producto->cantidad }}" value="1">
                                <button type="submit">Reducir</button>
                            </form>

                            <form action="{{ route('inventario.entrada', $producto->id_producto) }}" method="POST" class="form-inline" onsubmit="return confirmarEntrada(this);">
                                @csrf
                                @method('PUT')
                                <input type="number" name="cantidad_entrada" min="1" value="1">
                                <button type="submit">Agregar</button>
                            </form>

                            <form action="{{ route('inventario.desactivar', $producto->id_producto) }}" method="POST" onsubmit="return confirm('¿Deseas desactivar este producto?');">
                                @csrf
                                @method('PUT')
                                <button type="submit">Desactivar</button>
                            </form>
                        @else
                            <form action="{{ route('inventario.activar', $producto->id_producto) }}" method="POST" onsubmit="return confirm('¿Deseas activar este producto nuevamente?');">
                                @csrf
                                @method('PUT')
                                <button type="submit">Activar</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <p>No hay productos registrados.</p>
        @endforelse
    </div>

    <script>
        function confirmarReduccion(formulario) {
            const cantidad = formulario.cantidad_reducir.value;
            if (cantidad <= 0) {
                alert('La cantidad debe ser mayor a cero.');
                return false;
            }
            return confirm(`¿Estás seguro de reducir ${cantidad} unidad(es) de este producto?`);
        }

        function confirmarEntrada(formulario) {
            const cantidad = formulario.cantidad_entrada.value;
            if (cantidad <= 0) {
                alert('La cantidad debe ser mayor a cero.');
                return false;
            }
            return confirm(`¿Deseas agregar ${cantidad} unidad(es) a este producto?`);
        }
    </script>
</div>
@endsection
