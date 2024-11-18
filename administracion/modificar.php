<?php 
// session_start();
// if (!isset($_SESSION['logueado'])) {
//     header("location: login.php");
//     exit;
// }

require_once "../conexionbd.php";

$ci_admin_encoded = $_GET['CI_Adm'] ?? null;
if ($ci_admin_encoded) {
    $ci_admin = base64_decode($ci_admin_encoded);
    $consulta = "SELECT CI_Adm, Nombre_Adm, Apellido_PAdm, Apellido_MAdm, Genero_Adm, Direccion_Adm, Tipo_Usu FROM administracion WHERE CI_Adm = ?";
    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, 'i', $ci_admin);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) == 0) {
        echo "Administrador no encontrado.";
        exit;
    }

    $fila = mysqli_fetch_assoc($resultado);
} else {
    echo "No se recibió el CI del administrador.";
    exit;
}

include_once "../plantilla/cabecera.php";
?>
<link rel="stylesheet" href="modificar.css"> <!-- Incluye este enlace si usas un archivo CSS -->
    
<video autoplay muted loop class="video-background">
    <source src="../imgInfo/flores.mp4" type="video/mp4">
    Tu navegador no soporta el video.
</video>
<script>
    // Espera a que el contenido se cargue
    document.addEventListener('DOMContentLoaded', function () {
        var video = document.querySelector('.video-background');

        // Establecer la velocidad de reproducción (1.0 es normal, 2.0 es el doble de velocidad, etc.)
        video.playbackRate = 0.5; // Cambia este valor a la velocidad deseada

        // Establecer un tiempo específico para comenzar la reproducción
        video.currentTime = 3; // Comienza a reproducir desde 10 segundos

        // Si deseas que el video se detenga después de un tiempo específico
        // puedes usar un setTimeout
        setTimeout(function() {
            video.pause(); // Detiene la reproducción
        }, 30000); // Detener después de 30 segundos
    });
</script>

<div class="container form-container">
    <h3 class="text-center">Modificar Administrador</h3>
    <form action="actualizar.php" method="post">
        <div class="form-group">
            <label for="nombreAdm">Carnet del Administrador:</label>
            <input type="number" name="carnetAdm" value="<?php echo $fila['CI_Adm']; ?>">
        </div>

        <div class="form-group">
            <label for="nombreAdm">Nombre del Administrador:</label>
            <input type="text" id="nombreAdm" name="nombreAdm" class="form-control" value="<?php echo $fila['Nombre_Adm']; ?>" required>
        </div>

        <div class="form-group">
            <label for="apellidoAP">Apellido Paterno:</label>
            <input type="text" id="apellidoAP" name="apellidoAP" class="form-control" value="<?php echo $fila['Apellido_PAdm']; ?>" required>
        </div>

        <div class="form-group">
            <label for="apellidoAM">Apellido Materno:</label>
            <input type="text" id="apellidoAM" name="apellidoAM" class="form-control" value="<?php echo $fila['Apellido_MAdm']; ?>" required>
        </div>

        <div class="form-group">
            <label>Género:</label><br>
            <div class="form-check form-check-inline">
                <input type="radio" id="masculino" name="genero" value="masculino" class="form-check-input" <?php echo ($fila['Genero_Adm'] == 'M') ? 'checked' : ''; ?> required>
                <label class="form-check-label" for="masculino">Masculino</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="femenino" name="genero" value="femenino" class="form-check-input" <?php echo ($fila['Genero_Adm'] == 'F') ? 'checked' : ''; ?> required>
                <label class="form-check-label" for="femenino">Femenino</label>
            </div>
        </div>

        <div class="form-group">
            <label for="tipoAdm">Tipo de Administración:</label>
            <select id="tipoAdm" name="tipoAdm" class="form-control" required>
                <option value="" disabled>Seleccione el tipo</option>
                <option value="Directora" <?php echo ($fila['Tipo_Usu'] == 'Directora') ? 'selected' : ''; ?>>Directora</option>
                <option value="Secretaria" <?php echo ($fila['Tipo_Usu'] == 'Secretaria') ? 'selected' : ''; ?>>Secretaria</option>
                <option value="Profesora" <?php echo ($fila['Tipo_Usu'] == 'Profesora') ? 'selected' : ''; ?>>Profesora</option>
            </select>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo $fila['Direccion_Adm']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" class="form-control" placeholder="Ingrese la contraseña">
        </div>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Actualizar Administrador</button>
            <a href="lista.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php
mysqli_close($conexion);
include_once "../plantilla/piepagina.php";
?>
