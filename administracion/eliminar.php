<?php


// Conectar a la base de datos
require_once "../conexionbd.php";

// Verificar si se recibió el parámetro CI_Adm
if (isset($_GET['CI_Adm'])) {
    // Decodificar el CI_Adm recibido
    $carnetAdm = base64_decode($_GET['CI_Adm']);

    // Preparar la consulta de eliminación
    $consulta = "DELETE FROM administracion WHERE CI_Adm = '$carnetAdm'";

    // Ejecutar la consulta
    $respuesta = mysqli_query($conexion, $consulta);

    // Verificar si la eliminación fue exitosa
    if ($respuesta) {
        // Redirigir a la lista de administradores con un mensaje de éxito
        header("location: listar.php?mensaje=Administrador eliminado correctamente.");
    } else {
        // Redirigir a la lista de administradores con un mensaje de error
        header("location: listar.php?mensaje=Error al eliminar al administrador: " . mysqli_error($conexion));
    }
} else {
    // Si no se recibe CI_Adm, redirigir a la lista
    header("location: listar.php");
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
