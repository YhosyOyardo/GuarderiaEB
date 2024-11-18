<?php
$ruta = "../";
include_once "../plantilla/cabecera.php";
?>
<?php 
// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "guarderiaeb");

// Verificar si la conexión es exitosa
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Variables para los filtros
$buscar_ci_nombre = "";
$buscar_fecha = "";
$buscar_fecha_exacta = "";

// Verificar si se ha enviado una solicitud de búsqueda
if (isset($_POST['buscar'])) {
    $buscar_ci_nombre = $_POST['buscar_ci_nombre'];
    // Verificar si la variable está definida en $_POST antes de acceder a ella
    $buscar_fecha = isset($_POST['buscar_fecha']) ? $_POST['buscar_fecha'] : '';
    $buscar_fecha_exacta = isset($_POST['buscar_fecha_exacta']) ? $_POST['buscar_fecha_exacta'] : '';
}

// Obtener el rol y CI del usuario de la sesión
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
$ci_tutor = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Modificar la consulta SQL base según el rol
$sql_asistencia = "SELECT n.CI_N, n.Nombre_N, n.Apellido_PN, n.Apellido_MN, 
                   n.Edad, a.Fecha_Asist, a.Estado 
                   FROM asistencia a 
                   JOIN ninos n ON a.CI_N = n.CI_N";

// Si es un tutor, agregar la restricción para mostrar solo sus niños
if ($rol === 'Tutor' && $ci_tutor) {
    $sql_asistencia .= " JOIN tutores t ON n.CI_N = t.CI_N 
                         WHERE t.CI_T = '$ci_tutor'";
}

// Filtro por CI, Nombre o Apellidos
if (!empty($buscar_ci_nombre)) {
    $sql_asistencia .= " AND (n.CI_N LIKE '%$buscar_ci_nombre%' 
                          OR n.Nombre_N LIKE '%$buscar_ci_nombre%' 
                          OR n.Apellido_PN LIKE '%$buscar_ci_nombre%' 
                          OR n.Apellido_MN LIKE '%$buscar_ci_nombre%')";
}

// Filtro por Año y Mes
if (!empty($buscar_fecha)) {
    // Asegúrate de que el formato de la fecha sea 'YYYY/MM'
    $fecha_parts = explode("/", $buscar_fecha);
    if (count($fecha_parts) == 2) {
        $anio = $fecha_parts[0];
        $mes = $fecha_parts[1];
        // Filtrar por año y mes en la fecha de asistencia
        $sql_asistencia .= " AND YEAR(a.Fecha_Asist) = '$anio' AND MONTH(a.Fecha_Asist) = '$mes'";
    }
}

// Filtro por fecha exacta
if (!empty($buscar_fecha_exacta)) {
    // Filtrar por fecha exacta
    $sql_asistencia .= " AND DATE(a.Fecha_Asist) = '$buscar_fecha_exacta'";
}

$sql_asistencia .= " ORDER BY a.Fecha_Asist DESC";
$resultado_asistencia = $conexion->query($sql_asistencia);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }
        .text-success {
            color: #28a745;
        }
        .text-warning {
            color: #ffc107;
            font-weight: bold;
        }
        /* Estilo para el fondo y cabecera */
        body {
            background-color: #d4edda; /* Verde claro para el fondo */
        }
        h3 {
            color: #155724; /* Verde oscuro para el título */
        }
        .btn-primary {
            background-color: #155724; /* Verde oscuro para los botones */
            border-color: #155724;
        }
        .btn-primary:hover {
            background-color: #0e3d0e; /* Color verde más oscuro al pasar el mouse */
            border-color: #0e3d0e;
        }
        table th, table td {
            background-color: #f8f9fa; /* Fondo claro para las celdas de la tabla */
        }
        table {
            background-color: #ffffff; /* Fondo blanco para la tabla */
        }
    </style>
</head>
<body>
<div class="container">
    <h3 class="text-center">Lista de Asistencia Registrada</h3>

    <!-- Formularios de Búsqueda -->
    <form method="POST" class="form-inline mb-3">
    <input type="text" name="buscar_ci_nombre" class="form-control" placeholder="Buscar por CI, Nombre, Apellido Paterno o Apellido Materno" value="<?php echo $buscar_ci_nombre; ?>">

    <!-- Selección de Año y Mes (Formato Año/Mes) -->
    <input type="text" name="buscar_fecha" class="form-control ml-2" placeholder="YYYY/MM" value="<?php echo $buscar_fecha; ?>" maxlength="7" pattern="\d{4}/\d{2}">

    <!-- Selección de Fecha Exacta -->
    <input type="date" name="buscar_fecha_exacta" class="form-control ml-2" value="<?php echo $buscar_fecha_exacta; ?>">

    <button type="submit" name="buscar" class="btn btn-primary ml-2">Buscar</button>
</form>

    <!-- Tabla de Resultados -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>CI del Niño</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Edad</th>
                <th>Fecha de Asistencia</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($resultado_asistencia->num_rows > 0) {
            while ($asistencia = $resultado_asistencia->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $asistencia['CI_N'] . "</td>";
                echo "<td>" . $asistencia['Nombre_N'] . "</td>";
                echo "<td>" . $asistencia['Apellido_PN'] . "</td>";
                echo "<td>" . $asistencia['Apellido_MN'] . "</td>";
                echo "<td>" . $asistencia['Edad'] . "</td>";
                echo "<td>" . $asistencia['Fecha_Asist'] . "</td>";
                echo "<td>" . $asistencia['Estado'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No hay registros de asistencia</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <?php if ($rol !== 'Tutor'): ?>
    <form method="POST" action="generar_pdf.php" target="_blank">
        <input type="hidden" name="buscar_ci_nombre" value="<?php echo $buscar_ci_nombre; ?>">
        <input type="hidden" name="buscar_fecha" value="<?php echo $buscar_fecha; ?>">
        <input type="hidden" name="buscar_fecha_exacta" value="<?php echo $buscar_fecha_exacta; ?>">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </button>
    </form>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conexion->close();
include_once "../plantilla/piepagina.php";
?>
