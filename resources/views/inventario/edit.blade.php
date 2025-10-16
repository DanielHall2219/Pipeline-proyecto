@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<style>
    .form-container {
        max-width: 600px;
        margin: 40px auto;
        background-color: #1e1e1e;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
        color: white;
    }

    .form-container h2 {
        margin-bottom: 20px;
        color: #00bcd4;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: none;
        background-color: #2a2a2a;
        color: white;
    }

    .form-group textarea {
        resize: vertical;
    }

    .btn-submit {
        background-color: #00bcd4;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-submit:hover {
        background-color: #0097a7;
    }

    .btn-cancel {
        background-color: #555;
        margin-left: 10px;
    }

    .btn-cancel:hover {
        background-color: #777;
    }

    .form-group img {
        max-width: 120px;
        margin-top: 10px;
        border-radius: 6px;
    }
</style>

<div class="form-container">
    <h2>Editar Producto</h2>
    <form action="{{ route('inventario.update', $producto->id_producto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre_producto">Nombre del Producto</label>
            <input type="text" name="nombre_producto" value="{{ $producto->nombre_producto }}" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion">{{ $producto->descripcion }}</textarea>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" min="0" value="{{ $producto->cantidad }}" required>
        </div>

        <div class="form-group">
            <label for="unidad_medida">Unidad de Medida</label>
            <input type="text" name="unidad_medida" value="{{ $producto->unidad_medida }}" required>
        </div>

        <div class="form-group">
            <label for="fecha_ingreso">Fecha de Ingreso</label>
            <input type="date" name="fecha_ingreso" value="{{ $producto->fecha_ingreso }}" required>
        </div>

        <div class="form-group">
            <label for="stock_minimo">Stock Mínimo</label>
            <input type="number" name="stock_minimo" min="1" value="{{ $producto->stock_minimo }}" required>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del producto (opcional)</label>
            <input type="file" name="imagen" accept="image/*">
            @if ($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen actual del producto">
            @endif
        </div>

        <button type="submit" class="btn-submit">Guardar Cambios</button>
        <a href="{{ route('inventario.index') }}" class="btn-submit btn-cancel">Cancelar</a>
    </form>
</div>
@endsection
