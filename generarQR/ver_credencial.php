<?php
// Conexión a la base de datos
require_once 'conexionbd.php';

// Recibir el CI del niño
$ci_nino = $_GET['ci_nino'];

// Obtener la información del QR y la fecha de generación
$query = "SELECT * FROM qr_control WHERE CI_N = '$ci_nino'";
$result = mysqli_query($conexion, $query);

if ($row = mysqli_fetch_assoc($result)) {
    // Calcular la diferencia de fechas
    $fechaGeneracion = new DateTime($row['FechaGeneracionQR']);
    $fechaActual = new DateTime();
    $diferencia = $fechaGeneracion->diff($fechaActual);

    // Si ha pasado más de 1 mes, cambiar el estado a "DENEGADO"
    if ($diferencia->m >= 1) {
        $queryUpdate = "UPDATE qr_control SET Estado = 'DENEGADO' WHERE CI_N = '$ci_nino'";
        mysqli_query($conexion, $queryUpdate);
        echo "QR DENEGADO: No tiene autorización para recoger al niño.";
    } else {
        // Mostrar la información del niño
        echo "QR AUTORIZADO: Puede recoger al niño.";
        // Aquí puedes agregar el código para mostrar la credencial completa.
    }
} else {
    echo "QR no encontrado.";
}

// Cerrar la conexión
mysqli_close($conexion);
?>
