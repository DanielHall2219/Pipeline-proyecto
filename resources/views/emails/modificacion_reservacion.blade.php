<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservación Modificada</title>

</head>
<body>
    <div class="container">
        <h2>¡Reservación modificada con éxito!</h2>

        <p>Estimado/a <strong>{{ $detalles['nombre'] }}</strong>,</p>

        <p>Se ha realizado una modificación en su reservación. A continuación se detallan los datos actualizados:</p>

        <div class="info">
            <p><strong>Correo:</strong> {{ $detalles['correo'] }}</p>
            <p><strong>Mesa:</strong> Mesa {{ $detalles['mesa'] }}</p>
            <p><strong>Fecha:</strong> {{ $detalles['fecha'] }}</p>
            <p><strong>Hora:</strong> {{ $detalles['hora'] }}</p>
            <p><strong>Número de personas:</strong> {{ $detalles['personas'] }}</p>
        </div>

        <p>Si no solicitó esta modificación o tiene alguna duda, por favor contáctenos.</p>

        <div class="footer">
            Restaurante Punto Marino – Este es un mensaje automático, por favor no responder.
        </div>
    </div>
</body>
</html>
