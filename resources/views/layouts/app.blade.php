<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Punto Marino')</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(255, 255, 255);
            color: #f5f5f5;
            position: relative;
            min-height: 100vh;
        }

        .background-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.20;
            width: 400px;
            height: auto;
            z-index: 0;
        }

        .header {
            background-color: #1f1f1f;
            padding: 20px;
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            color: #00bcd4;
            position: relative;
            z-index: 2;
        }

        .rol-logout {
            position: absolute;
            top: 22px;
            right: 25px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .rol-info,
        .user-info,
        .logout-form button {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            font-weight: 600;
            border: none;
        }

        .logout-form button {
            background-color: #ff5252;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-form button:hover {
            background-color: #e53935;
        }

        .menu {
            background-color: #262626;
            display: flex;
            justify-content: center;
            padding: 14px 0;
            z-index: 2;
            position: relative;
        }

        .menu a {
            color: #e0e0e0;
            text-decoration: none;
            margin: 0 20px;
            font-weight: bold;
            font-size: 16px;
            transition: 0.3s;
        }

        .menu a:hover {
            color: #00bcd4;
            transform: scale(1.05);
        }

        .content {
            position: relative;
            z-index: 2;
            padding: 50px 30px;
        }
    </style>
</head>
<body>
    <img src="/images/logo.jpg" alt="Logo marca de agua" class="background-logo">

    <div class="header">
        PUNTO MARINO
        <div class="rol-logout">
            {{-- Si hay usuario logueado, mostramos usuario/rol y botón salir --}}
            @auth
                <div class="user-info">
                    Usuario: {{ optional(Auth::user())->usuario }}
                </div>
                <div class="rol-info">
                    Rol: {{ ucfirst(optional(Auth::user()->rol)->nombre_rol ?? '') }}
                </div>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit">Cerrar sesión</button>
                </form>
            @endauth

            {{-- Si es invitado (ej. /clave-olvidada, /nueva-clave) --}}
            @guest
                <div class="user-info">
                    Usuario: Invitado
                </div>
                <div class="rol-info">
                    Rol: —
                </div>
            @endguest
        </div>
    </div>

    {{-- Menú solo para usuarios autenticados --}}
    @auth
        <div class="menu">
            @if (optional(Auth::user()->rol)->nombre_rol === 'admin')
                <a href="{{ route('dashboard') }}">Inicio</a>
                <a href="{{ route('inventario.index') }}">Inventario</a>
                <a href="#">Análisis de tendencias</a>
                <a href="{{ route('reportes.index') }}">Reportes</a>
                <a href="{{ route('usuarios.index') }}">Usuarios</a>
            @elseif (optional(Auth::user()->rol)->nombre_rol === 'empleado')
                <a href="{{ route('dashboard') }}">Inicio</a>
                <a href="{{ route('inventario.index') }}">Inventario</a>
                <a href="{{ route('reservaciones.index') }}">Reservas</a>
                <a href="{{ route('opiniones.crear') }}">Opiniones</a>
            @endif
        </div>
    @endauth

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
