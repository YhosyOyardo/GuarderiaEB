<?php
// Conexión a la base de datos
require_once 'conexionbd.php';

// Recibir datos del niño y tutor
$ci_nino = $_POST['CI_N'];
$ci_tutor = $_POST['CI_T'];

// Obtener la fecha actual
$fechaGeneracion = date('Y-m-d');

// Insertar el registro del QR en la base de datos
$query = "INSERT INTO qr_control (CI_N, CI_T, FechaGeneracionQR, Estado) 
          VALUES ('$ci_nino', '$ci_tutor', '$fechaGeneracion', 'AUTORIZADO')";
$result = mysqli_query($conexion, $query);

// Verificar si se insertó correctamente
if ($result) {
    // Generar el enlace con los datos del niño para el QR
    $url_qr = "http://tu-sitio.com/ver_credencial.php?ci_nino=$ci_nino";

    // Usar una librería para generar el código QR (por ejemplo, PHP QR Code)
    include('phpqrcode/qrlib.php');
    QRcode::png($url_qr);
    
    echo "QR generado y registrado en la base de datos.";
} else {
    echo "Error al generar el QR.";
}

// Cerrar la conexión
mysqli_close($conexion);
?>
