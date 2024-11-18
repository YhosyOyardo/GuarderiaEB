<?php
// session_start();
// if (!isset($_SESSION['logueado'])) {
//     // Si no hay sesión iniciada, redirigir al login
//     header("location: login.php");
//     exit;
// }

// Conectar a la base de datos
require_once "../conexionbd.php";

// Obtener los datos del formulario
$carnetN = $_POST['carnetN'];
$fechaAsist = $_POST['fechaAsist'];
$estado = $_POST['estado'];
$tipoFam = $_POST['tipoFam'];
$carnetProf = $_POST['carnetProf'];

// Si se proporciona una nueva contraseña, encriptarla (si corresponde)
// Aquí puedes agregar más campos si es necesario
if (!empty($_POST['contraseña'])) {
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $consulta = "UPDATE asistencia SET Estado=?, Tipo_Fam=?, CI_Prof=? WHERE CI_N=? AND Fecha_Asist=?";
} else {
    // Si no se proporciona una nueva contraseña, no actualizarla
    $consulta = "UPDATE asistencia SET Estado=?, Tipo_Fam=?, CI_Prof=? WHERE CI_N=? AND Fecha_Asist=?";
}

// Preparar la consulta SQL
$stmt = mysqli_prepare($conexion, $consulta);
if (!empty($_POST['contraseña'])) {
    mysqli_stmt_bind_param($stmt, 'ssssi', $estado, $tipoFam, $carnetProf, $carnetN, $fechaAsist);
} else {
    mysqli_stmt_bind_param($stmt, 'ssssi', $estado, $tipoFam, $carnetProf, $carnetN, $fechaAsist);
}

// Ejecutar la consulta
if (mysqli_stmt_execute($stmt)) {
    header("location: listar.php?mensaje=Asistencia actualizada correctamente."); // Redirigir a la lista de asistencia
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
