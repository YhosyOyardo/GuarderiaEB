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

// Número de registros por página
$limite = 10;

// Obtener el número de página actual, si no está presente, asumir página 1
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $limite;

// Consultar el total de registros para calcular el número de páginas
$sql_count = "SELECT COUNT(*) as total FROM ninos WHERE estado = 'activo'";
$resultado_count = $conexion->query($sql_count);
$total_registros = $resultado_count->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $limite);

// Consulta para niños de 1 a 2 años
$sql_pequenos = "SELECT n.CI_N, n.Nombre_N, n.Apellido_PN, n.Apellido_MN, n.Edad 
                 FROM ninos n
                 WHERE n.estado = 'activo' 
                 AND n.Edad BETWEEN 1 AND 2 
                 ORDER BY n.Apellido_PN, n.Apellido_MN
                 LIMIT $inicio, $limite";

// Consulta para niños de 3 a 5 años
$sql_grandes = "SELECT n.CI_N, n.Nombre_N, n.Apellido_PN, n.Apellido_MN, n.Edad 
                FROM ninos n
                WHERE n.estado = 'activo' 
                AND n.Edad BETWEEN 3 AND 5 
                ORDER BY n.Apellido_PN, n.Apellido_MN
                LIMIT $inicio, $limite";

$resultado_pequenos = $conexion->query($sql_pequenos);
$resultado_grandes = $conexion->query($sql_grandes);

// Agregar después de la conexión a la base de datos
function verificarDeudas($conexion, $ci_nino) {
    // Obtener fecha de inscripción
    $query = "SELECT Fecha_Insc FROM ninos WHERE CI_N = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $ci_nino);
    $stmt->execute();
    $result = $stmt->get_result();
    $fecha_insc = $result->fetch_assoc()['Fecha_Insc'];
    
    // Obtener todos los pagos realizados
    $query = "SELECT DATE_FORMAT(FechaPago, '%Y-%m') as mes_pago 
              FROM mensualidad 
              WHERE CI_N = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $ci_nino);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $pagos_realizados = [];
    while($row = $result->fetch_assoc()) {
        $pagos_realizados[] = $row['mes_pago'];
    }
    
    // Calcular meses desde inscripción
    $fecha_inicio = new DateTime($fecha_insc);
    $fecha_actual = new DateTime();
    $meses_deuda = [];
    
    for($fecha = clone $fecha_inicio; $fecha <= $fecha_actual; $fecha->modify('+1 month')) {
        $mes_año = $fecha->format('Y-m');
        if(!in_array($mes_año, $pagos_realizados)) {
            $meses_deuda[] = $fecha->format('F Y');
        }
    }
    
    return $meses_deuda;
}
// Configuración de paginación
$por_pagina = 2; // Registros por página
$total_niños = count($niños);
$total_paginas = ceil($total_niños / $por_pagina);

// Obtener la página actual desde la URL
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $total_paginas)); // Asegurar que esté dentro de rango

// Calcular el rango de registros a mostrar
$inicio = ($page - 1) * $por_pagina;
$niños_pagina = array_slice($niños, $inicio, $por_pagina);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Asistencia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo para el contenedor */
        .form-container {
            background-color: #E0E0E0; /* Color plomo claro */
            padding: 20px;
            border-radius: 8px;
        }
        
        /* Estilo de la tabla */
        .table {
            background-color: #F2F2F2; /* Fondo plomo claro */
        }

        /* Botones de formulario */
        .btn-primary {
            background-color: #006400; /* Verde oscuro */
            border-color: #006400;
        }
        
        .btn-secondary {
            background-color: #A9A9A9; /* Botón de limpiar color gris */
            border-color: #A9A9A9;
        }

        /* Estilo para los enlaces de paginación */
        .pagination a {
            color: #006400; /* Verde oscuro */
            padding: 8px 16px;
            text-decoration: none;
            margin: 0 4px;
            border-radius: 4px;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #9ACD32; /* Verde más claro */
        }

        .pagination a.active {
            color: #FFFFFF; /* Texto blanco en la página actual */
            background-color: #006400; /* Fondo verde oscuro para la página activa */
            font-size: 1.1em;
        }
        td input[type='radio'] {
            margin-right: 5px; /* Ajusta el valor según el espaciado deseado */
        }

        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-header {
            padding: 1rem;
        }

        .card-header h4 {
            margin: 0;
        }

        .table {
            margin-bottom: 0;
        }

        .form-control {
            width: 120px;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            margin-bottom: 5px;
        }

        select:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
    </style>
</head>

<form action="registro.php" method="POST">
    <div class="container">
        <h3 class="text-center mb-4">Registro de Asistencia</h3>

        <!-- Grupo 1-2 años -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4>Grupo 1-2 años</h4>
            </div>
            <div class="card-body">
                <!-- Campo para la fecha -->
                <div class="form-group">
                    <label for="fecha_1_2">Fecha de Asistencia (1-2 años):</label>
                    <input type="date" name="fecha_1_2" class="form-control" required>
                </div>

                <!-- Tabla de estudiantes -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>CI</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Edad</th>
                            <th>Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado_pequenos->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['CI_N']); ?></td>
                                <td><?php echo htmlspecialchars($fila['Nombre_N']); ?></td>
                                <td><?php echo htmlspecialchars($fila['Apellido_PN']); ?></td>
                                <td><?php echo htmlspecialchars($fila['Apellido_MN']); ?></td>
                                <td><?php echo htmlspecialchars($fila['Edad']); ?></td>
                                <td>
                                    <?php 
                                    $deudas = verificarDeudas($conexion, $fila['CI_N']);
                                    if (empty($deudas)) { ?>
                                        <select name="asistencia_1_2[<?php echo $fila['CI_N']; ?>]" class="form-control">
                                            <option value="Presente">Presente</option>
                                            <option value="Ausente">Ausente</option>
                                        </select>
                                    <?php } else { ?>
                                        <div class="alert alert-danger" style="font-size: 0.9em; padding: 5px; margin: 0;">
                                            <strong>Deudas pendientes:</strong><br>
                                            <?php echo implode("<br>", $deudas); ?>
                                        </div>
                                        <select class="form-control" disabled>
                                            <option>Registro bloqueado</option>
                                        </select>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Registrar Asistencia</button>
  
        <!-- Grupo 3-5 años -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h4>Grupo 3-5 años</h4>
            </div>
            <div class="card-body">
                <!-- Campo para la fecha -->
                <div class="form-group">
                    <label for="fecha_3_5">Fecha de Asistencia (3-5 años):</label>
                    <input type="date" name="fecha_3_5" class="form-control" required>
                </div>

                <!-- Tabla de estudiantes -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>CI</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Edad</th>
                            <th>Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado_grandes->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['CI_N']); ?></td>
                                <td><?php echo htmlspecialchars($fila['Nombre_N']); ?></td>
                                <td><?php echo htmlspecialchars($fila['Apellido_PN']); ?></td>
                                <td><?php echo htmlspecialchars($fila['Apellido_MN']); ?></td>
                                <td><?php echo htmlspecialchars($fila['Edad']); ?></td>
                                <td>
                                    <?php 
                                    $deudas = verificarDeudas($conexion, $fila['CI_N']);
                                    if (empty($deudas)) { ?>
                                        <select name="asistencia_3_5[<?php echo $fila['CI_N']; ?>]" class="form-control">
                                            <option value="Presente">Presente</option>
                                            <option value="Ausente">Ausente</option>
                                        </select>
                                    <?php } else { ?>
                                        <div class="alert alert-danger" style="font-size: 0.9em; padding: 5px; margin: 0;">
                                            <strong>Deudas pendientes:</strong><br>
                                            <?php echo implode("<br>", $deudas); ?>
                                        </div>
                                        <select class="form-control" disabled>
                                            <option>Registro bloqueado</option>
                                        </select>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botón para enviar -->
        <button type="submit" class="btn btn-success">Registrar Asistencia</button>
    </div>
</form>

<!-- Paginación -->
<div>
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">Anterior</a>
        <?php endif; ?>
        Página <?= $page ?> de <?= $total_paginas ?>
        <?php if ($page < $total_paginas): ?>
            <a href="?page=<?= $page + 1 ?>">Siguiente</a>
        <?php endif; ?>
    </div>
<script>
    function aplicarFechaATodos() {
        const fechaSeleccionada = document.getElementById('fecha_unica').value;
        const camposFecha = document.querySelectorAll('input[name="Fecha_Asist[]"]');
        
        camposFecha.forEach(campo => {
            campo.value = fechaSeleccionada;
        });
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conexion->close();
include_once "../plantilla/piepagina.php";
?>