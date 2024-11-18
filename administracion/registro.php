<?php  
 session_start();
 if(!isset($_SESSION['logueado'])){
    header("location: login.php");
}

// 1er paso: Recibir las variables enviadas desde el formulario
$carnet_Adm = $_POST['carnetAdm'];
$nombre_Adm = mb_strtolower($_POST['nombreAdm']);
$apellido_Padm = mb_strtolower($_POST['apellidoAP']);
$apellido_Madm = mb_strtolower($_POST['apellidoAM']);
$genero_Adm = $_POST['genero'];
$direccion_Adm = mb_strtolower($_POST['direccion']);
$tipo_Adm = $_POST['tipoAdm'];
$contraseña_Adm = $_POST['contraseña'];

if ($genero_Adm == 'masculino') {
    $genero_Adm = 'm';
} elseif ($genero_Adm == 'femenino') {
    $genero_Adm = 'f';
}

// Encriptar la contraseña antes de guardar
$contrasena_hash = password_hash($contraseña_Adm, PASSWORD_DEFAULT);

// Conectar a la base de datos
require_once "../conexionbd.php";

// Verificar si el CI_Adm ya existe
$consulta_verificar = "SELECT CI_Adm FROM administracion WHERE CI_Adm = ?";
$stmt_verificar = mysqli_prepare($conexion, $consulta_verificar);
mysqli_stmt_bind_param($stmt_verificar, 'i', $carnet_Adm);
mysqli_stmt_execute($stmt_verificar);
mysqli_stmt_store_result($stmt_verificar);

if (mysqli_stmt_num_rows($stmt_verificar) > 0) {
    // Si el CI_Adm ya existe, mostrar un mensaje de error y regresar al formulario
    echo "<script>alert('Error: El carnet ya está registrado.');</script>";
    echo "<script>window.history.back();</script>"; // Regresar al formulario anterior
} else {
    // Preparar la consulta SQL para insertar
    $consulta = "INSERT INTO administracion 
                    (CI_Adm, Nombre_Adm, Apellido_PAdm, Apellido_MAdm, Genero_Adm, Direccion_Adm, Tipo_Usu, Contrasena_Adm)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                 
    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, 'isssssss', $carnet_Adm, $nombre_Adm, $apellido_Padm, $apellido_Madm, $genero_Adm, $direccion_Adm, $tipo_Adm, $contrasena_hash);

    if (mysqli_stmt_execute($stmt)) {
        // Inserción exitosa
        header("Location: listar.php");
    } else {
        echo "Error al registrar al administrador. Intente de nuevo.";
    }

    mysqli_stmt_close($stmt);
}

// Cerrar la declaración y la conexión
mysqli_stmt_close($stmt_verificar);
mysqli_close($conexion);
?>
