@extends('layouts.app')

@section('title', 'Olvidé mi clave')

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

    input[type="email"] {
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

    .alert {
        background-color: #2e7d32;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
        color: white;
    }

    .error {
        background-color: #b71c1c;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
        color: white;
    }
</style>

<div class="form-container">
    <h2>¿Olvidaste tu clave?</h2>

    @if (session('estado'))
        <div class="alert">{{ session('estado') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('clave.enviar') }}">
        @csrf
        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" value="{{ old('correo') }}" placeholder="ejemplo@dominio.com" required>
        </div>

        <button type="submit" class="btn-submit">Enviar enlace</button>
        <a href="{{ route('login') }}" class="btn-cancel">Volver</a>
    </form>
</div>
@endsection
