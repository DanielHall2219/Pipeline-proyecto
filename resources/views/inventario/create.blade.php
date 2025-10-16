@extends('layouts.app')

@section('title', 'Ingresar Producto')

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
    input[type="number"],
    input[type="date"],
    textarea,
    input[type="file"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: none;
        background-color: #2c2c2c;
        color: white;
    }

    textarea {
        resize: vertical;
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

<div class="form-container">
    <h2>Registrar nuevo producto</h2>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inventario.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="nombre_producto">Nombre del producto</label>
            <input type="text" id="nombre_producto" name="nombre_producto" value="{{ old('nombre_producto') }}" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion">{{ old('descripcion') }}</textarea>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" value="{{ old('cantidad') }}" min="0" required>
        </div>

        <div class="form-group">
            <label for="unidad_medida">Unidad de medida</label>
            <input type="text" id="unidad_medida" name="unidad_medida" value="{{ old('unidad_medida') }}" required>
        </div>

        <div class="form-group">
            <label for="fecha_ingreso">Fecha de ingreso</label>
            <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" required>
        </div>

        <div class="form-group">
            <label for="stock_minimo">Stock mínimo</label>
            <input type="number" id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo') }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del producto (opcional)</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">
        </div>

        <div class="form-actions">
            <a href="{{ route('inventario.index') }}" class="btn btn-cancelar">Cancelar</a>
            <button type="submit" class="btn btn-guardar">Guardar</button>
        </div>
    </form>
</div>
@endsection
