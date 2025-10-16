<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmación de Reservación</title>
</head>
<body>
    <h2>¡Gracias por reservar en Punto Marino!</h2>
    <p>Hola {{ $detalles['nombre'] }},</p>
    <p>Hemos registrado tu reservación con los siguientes datos:</p>

    <ul>
        <li><strong>Fecha:</strong> {{ $detalles['fecha'] }}</li>
        <li><strong>Hora:</strong> {{ $detalles['hora'] }}</li>
        <li><strong>Mesa:</strong> {{ $detalles['mesa'] }}</li>
        <li><strong>Número de personas:</strong> {{ $detalles['personas'] }}</li>
    </ul>

    <p>Te esperamos con mucho gusto.</p>
    <p><strong>Punto Marino</strong></p>
</body>
</html>
