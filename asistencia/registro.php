<?php  
// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "guarderiaeb");

// Verificar si la conexión es exitosa
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados
    $ci_ninos = $_POST['CI_N']; // Array de CI de los niños
    $fecha_asist = $_POST['Fecha_Asist']; // Array de fechas de asistencia
    $estado = $_POST['Estado']; // Array de estados de presencia
    
    $errores = [];
    $registros_exitosos = 0;

    // Recorrer los datos y hacer las inserciones
    foreach ($ci_ninos as $index => $ci_nino) {
        // Obtener los datos del niño desde la tabla ninos usando CI_N
        $sql_nino = "SELECT Nombre_N, Apellido_PN, Apellido_MN FROM ninos WHERE CI_N = ?";
        $stmt_nino = $conexion->prepare($sql_nino);
        $stmt_nino->bind_param("s", $ci_nino);
        $stmt_nino->execute();
        $resultado_nino = $stmt_nino->get_result();

        if ($resultado_nino->num_rows > 0) {
            // Obtener los datos del niño
            $fila = $resultado_nino->fetch_assoc();
            $nombre_nino = $fila['Nombre_N'];
            $apellido_pn = $fila['Apellido_PN'];
            $apellido_mn = $fila['Apellido_MN'];

            // Obtener la fecha y el estado de la asistencia
            $fecha = $fecha_asist[$index];
            $estado_asistencia = $estado[$ci_nino];

            // Verificar si ya existe un registro con el mismo CI_N y Fecha_Asist
            $sql_check = "SELECT * FROM asistencia WHERE CI_N = ? AND Fecha_Asist = ?";
            $stmt_check = $conexion->prepare($sql_check);
            $stmt_check->bind_param("ss", $ci_nino, $fecha);
            $stmt_check->execute();
            $resultado_check = $stmt_check->get_result();

            if ($resultado_check->num_rows > 0) {
                $errores[] = "La asistencia ya está registrada para el niño con CI: $ci_nino en la fecha $fecha.";
            } else {
                // Insertar los datos de asistencia en la base de datos
                $sql_insert = "INSERT INTO asistencia (CI_N, Nombre_N, Apellido_PN, Apellido_MN, Fecha_Asist, Estado) 
                               VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert = $conexion->prepare($sql_insert);
                $stmt_insert->bind_param("ssssss", $ci_nino, $nombre_nino, $apellido_pn, $apellido_mn, $fecha, $estado_asistencia);

                if ($stmt_insert->execute()) {
                    $registros_exitosos++;
                } else {
                    $errores[] = "Error al registrar asistencia para el niño con CI: $ci_nino.";
                }
            }
        } else {
            $errores[] = "No se encontraron datos para el niño con CI: $ci_nino.";
        }
    }

    // Cerrar la conexión
    $conexion->close();

    // Mostrar mensaje y redirigir
    if (count($errores) > 0) {
        echo "<script>
                alert('Se encontraron los siguientes errores:\\n" . implode("\\n", $errores) . "');
                window.location.href = 'listar.php';
              </script>";
    } else {
        echo "<script>
                alert('Se registraron exitosamente " . $registros_exitosos . " asistencias.');
                window.location.href = 'listar.php';
              </script>";
    }
} else {
    // Si no es una solicitud POST, redirigir
    header("Location: nuevo.php");
    exit();
}
?>