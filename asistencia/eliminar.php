<?php

// Conectar a la base de datos
require_once "../conexionbd.php";

// Verificar si se recibieron los parámetros necesarios
if (isset($_GET['CI_N']) && isset($_GET['Fecha_Asist']) && isset($_GET['Estado']) && isset($_GET['Tipo_Fam']) && isset($_GET['CI_Prof'])) {
    
    // Decodificar los parámetros recibidos (si es necesario)
    $carnetN = base64_decode($_GET['CI_N']);
    $fechaAsist = base64_decode($_GET['Fecha_Asist']);
    $estado = base64_decode($_GET['Estado']);
    $tipoFam = base64_decode($_GET['Tipo_Fam']);
    $carnetProf = base64_decode($_GET['CI_Prof']);
    
    // Preparar la consulta de eliminación
    $consulta = "DELETE FROM asistencia WHERE CI_N = '$carnetN' AND Fecha_Asist = '$fechaAsist' AND Estado = '$estado' AND Tipo_Fam = '$tipoFam' AND CI_Prof = '$carnetProf'";

    // Ejecutar la consulta
    $respuesta = mysqli_query($conexion, $consulta);

    // Verificar si la eliminación fue exitosa
    if ($respuesta) {
        // Redirigir a la lista de asistencia con un mensaje de éxito
        header("location: listar.php?mensaje=Asistencia eliminada correctamente.");
    } else {
        // Redirigir a la lista de asistencia con un mensaje de error
        header("location: listar.php?mensaje=Error al eliminar la asistencia: " . mysqli_error($conexion));
    }
} else {
    // Si no se reciben todos los parámetros, redirigir a la lista
    header("location: listar.php");
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
