<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Punto Marino</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('/images/marisco.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        h1 {
            text-align: center;
            font-size: 48px;
            color: white;
            margin-top: 30px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
            font-weight: bold;
        }

        .login-box {
            background-color: rgba(0, 0, 0, 0.65);
            width: 400px;
            border-radius: 15px;
            padding: 40px 30px;
            margin: 100px auto 0;
            text-align: center;
            box-shadow:
                0 8px 16px rgba(0, 0, 0, 0.4),
                0 0 30px rgba(0, 131, 143, 0.5);
        }

        .login-box img {
            width: 100px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.4);
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            outline: none;
        }

        .login-box input::placeholder {
            color: #888;
        }

        .login-box button {
            padding: 12px 25px;
            background-color: #00838f;
            color: white;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .login-box button:hover {
            background-color: #006064;
            transform: scale(1.05);
        }

        .error-box {
            background-color: #ffcdd2;
            color: #b71c1c;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            box-shadow: 0px 0px 10px rgba(183, 28, 28, 0.4);
        }

        .ok-box {
            background-color: #c8e6c9;
            color: #1b5e20;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            box-shadow: 0px 0px 10px rgba(27, 94, 32, 0.25);
        }

        .login-links {
            margin-top: 14px;
        }
        .login-links a {
            color: #4fc3f7;
            text-decoration: none;
            font-weight: bold;
        }
        .login-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>PUNTO MARINO</h1>

    <div class="login-box">
        <img src="/images/logo.jpg" alt="Icono del sistema">

        {{-- Mensajes de éxito (p.ej. después de restablecer la clave) --}}
        @if (session('success'))
            <div class="ok-box">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensaje genérico (p.ej. “Si el correo existe, se envió un enlace…”) --}}
        @if (session('estado'))
            <div class="ok-box">
                {{ session('estado') }}
            </div>
        @endif

        {{-- Errores de validación/login --}}
        @if ($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" name="usuario" placeholder="Usuario" required><br>
            <input type="password" name="contrasena" placeholder="Contraseña" required><br>
            <button type="submit">INGRESAR</button>
        </form>

        <div class="login-links">
            <a href="{{ route('clave.olvidada') }}">¿Olvidaste tu clave?</a>
        </div>
    </div>

</body>
</html>
