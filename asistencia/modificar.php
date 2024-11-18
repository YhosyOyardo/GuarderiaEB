<?php
// session_start();
// if (!isset($_SESSION['logueado'])) {
//     header("location: login.php");
//     exit;
// }

// Conectar a la base de datos
require_once "../conexionbd.php";

// Obtener el CI del niño codificado
$ci_nino_encoded = $_GET['CI_N'] ?? null;
if ($ci_nino_encoded) {
    $ci_nino = base64_decode($ci_nino_encoded);

    // Preparar la consulta SQL
    $consulta = "SELECT CI_N, Fecha_Asist, Estado, Tipo_Fam, CI_Prof FROM asistencia WHERE CI_N = ?";
    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, 'i', $ci_nino);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) == 0) {
        echo "Registro de asistencia no encontrado.";
        exit;
    }

    $fila = mysqli_fetch_assoc($resultado);
} else {
    echo "No se recibió el CI del niño.";
    exit;
}

include_once "../plantilla/cabecera.php";
?>

<div class="container form-container">
    <h3 class="text-center">Modificar Asistencia</h3>
    <form action="actualizar_asistencia.php" method="post">
        <input type="hidden" name="CI_N" value="<?php echo $fila['CI_N']; ?>">

        <div class="form-group">
            <label for="Fecha_Asist">Fecha de Asistencia:</label>
            <input type="date" id="Fecha_Asist" name="Fecha_Asist" class="form-control" value="<?php echo $fila['Fecha_Asist']; ?>" required>
        </div>

        <div class="form-group">
            <label for="Estado">Estado:</label>
            <select id="Estado" name="Estado" class="form-control" required>
                <option value="" disabled>Seleccione el estado</option>
                <option value="Presente" <?php echo ($fila['Estado'] == 'Presente') ? 'selected' : ''; ?>>Presente</option>
                <option value="Ausente" <?php echo ($fila['Estado'] == 'Ausente') ? 'selected' : ''; ?>>Ausente</option>
            </select>
        </div>

        <div class="form-group">
            <label for="Tipo_Fam">Tipo de Familiar:</label>
            <select id="Tipo_Fam" name="Tipo_Fam" class="form-control" required>
                <option value="" disabled>Seleccione el tipo de familiar</option>
                <option value="Padre" <?php echo ($fila['Tipo_Fam'] == 'Padre') ? 'selected' : ''; ?>>Padre</option>
                <option value="Madre" <?php echo ($fila['Tipo_Fam'] == 'Madre') ? 'selected' : ''; ?>>Madre</option>
                <option value="Tutor" <?php echo ($fila['Tipo_Fam'] == 'Tutor') ? 'selected' : ''; ?>>Tutor</option>
            </select>
        </div>

        <div class="form-group">
            <label for="CI_Prof">Carnet del Profesor:</label>
            <input type="text" id="CI_Prof" name="CI_Prof" class="form-control" value="<?php echo $fila['CI_Prof']; ?>" required>
        </div>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Actualizar Asistencia</button>
            <a href="lista_asistencia.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conexion);
include_once "../plantilla/piepagina.php";
?>
