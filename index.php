<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Pago</title>
</head>
<body>
<?php
// Verificar si el pago ha sido realizado
$pagoRealizado = false; // Cambia esto con tu lógica real de verificación de pago

if ($pagoRealizado) {
    // Si el pago ha sido realizado, redirigir al usuario a la plataforma
    header("Location: plataforma.php");
    exit();
}
?>

<div>
    <h1>Recordatorio de Pago</h1>
    <p>Por favor, realiza el pago para acceder a la plataforma.</p>
    <p>Una vez que hayas realizado el pago, podrás ingresar y disfrutar de nuestros servicios.</p>
    <!-- Agrega aquí el enlace o botón para llevar al usuario a la página de pago -->

</div>
</body>
</html>







