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
$carnetAdm = $_POST['carnetAdm'];
$nombreAdm = $_POST['nombreAdm'];
$apellidoAP = $_POST['apellidoAP'];
$apellidoAM = $_POST['apellidoAM'];
$genero = $_POST['genero'];
$tipoAdm = $_POST['tipoAdm'];
$direccion = $_POST['direccion'];
$contraseña = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';

// //Valores que se reciben del formulario
// echo 'carnetAdm: '.$carnetAdm.'<br>';
// echo 'nombreAdm: '.$nombreAdm.'<br>';
// echo 'apellidoAP: '.$apellidoAP.'<br>';
// echo 'apellidoAM: '.$apellidoAM.'<br>';
// echo 'genero: '.$genero.'<br>';
// echo 'tipoAdm: '.$tipoAdm.'<br>';
// echo 'direccion: '.$direccion.'<br>';
// echo 'contraseña: '.$contraseña.'<br>';
// exit();

if ($genero == 'masculino') {
    $genero = 'm';
} elseif ($genero == 'femenino') {
    $genero = 'f';
}

// Si se proporciona una nueva contraseña, encriptarla
if (!empty($contraseña)) {
    $contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
    $consulta = "UPDATE administracion SET Nombre_Adm=?, Apellido_PAdm=?, Apellido_MAdm=?, Genero_Adm=?, Direccion_Adm=?, Tipo_Usu=?, Contrasena_Adm=? WHERE CI_Adm=?";
    // Preparar la consulta SQL
    $stmt = mysqli_prepare($conexion, $consulta);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssssssss', $nombreAdm, $apellidoAP, $apellidoAM, $genero, $direccion, $tipoAdm, $contraseña, $carnetAdm);
    }
} else {
    // Si no se proporciona una nueva contraseña, no actualizarla
    $consulta = "UPDATE administracion SET Nombre_Adm=?, Apellido_PAdm=?, Apellido_MAdm=?, Genero_Adm=?, Direccion_Adm=?, Tipo_Usu=? WHERE CI_Adm=?";
    // Preparar la consulta SQL
    $stmt = mysqli_prepare($conexion, $consulta);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssssss', $nombreAdm, $apellidoAP, $apellidoAM, $genero, $direccion, $tipoAdm, $carnetAdm);
    }
}

// Verificar si hubo error en la preparación
if (!$stmt) {
    die("Error en la preparación de la consulta: " . mysqli_error($conexion));
}

// Ejecutar la consulta
if (mysqli_stmt_execute($stmt)) {
    header("location: listar.php"); // Redirigir a la lista de administradores
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}

// Cerrar la sentencia y la conexión
mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>

