<?php
// session_start();
// if (!isset($_SESSION['logueado'])) {
//     // Si no hay sesión iniciada, redirigir al login
//     header("location: login.php");
//     exit;
// }

// Conectar a la base de datos
require_once "../conexionbd.php";

// Preparar la consulta SQL
$consulta = "SELECT CI_Adm, Nombre_Adm, Apellido_PAdm, Apellido_MAdm, Genero_Adm, Direccion_Adm, Tipo_Usu, Contrasena_Adm FROM administracion";

// Ejecutar la consulta
$respuesta = mysqli_query($conexion, $consulta);

// Verificar si la consulta se ejecutó correctamente
if (!$respuesta) {
    echo "Error al ejecutar la consulta: " . mysqli_error($conexion);
    exit;
}
$ruta = "../";
include_once "../plantilla/cabecera.php";
?>

<h3 class="text-center">Lista de Administradores</h3>
<link rel="stylesheet" href="nuevo.css"> <!-- Asegúrate de que la ruta sea correcta -->

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Carnet</th>
            <th scope="col">Nombre</th>
            <th scope="col">Apellido Paterno</th>
            <th scope="col">Apellido Materno</th>
            <th scope="col">Género</th>
            <th scope="col">Dirección</th>
            <th scope="col">Tipo de Administración</th>
            <th scope="col">Contraseña</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        while ($fila = mysqli_fetch_assoc($respuesta)) {
            $i++;
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $fila['CI_Adm']; ?></td>
                <td><?php echo ucwords(strtolower($fila['Nombre_Adm'])); ?></td>
<td><?php echo ucwords(strtolower($fila['Apellido_PAdm'])); ?></td>
<td><?php echo ucwords(strtolower($fila['Apellido_MAdm'])); ?></td>
<td><?php echo ucfirst($fila['Genero_Adm']); ?></td>
                <td><?php echo ucfirst($fila['Direccion_Adm']); ?></td>
                <td><?php echo ucfirst($fila['Tipo_Usu']); ?></td>
                <td><?php echo ucfirst($fila['Contrasena_Adm']); ?></td>
<td> 
                    
    <a href="modificar.php?CI_Adm=<?php echo base64_encode($fila['CI_Adm']); ?>" class="btn btn-warning" title="Modificar">
        <i class="fas fa-pencil-alt"></i> 
    </a>
    <a href="eliminar.php?CI_Adm=<?php echo base64_encode($fila['CI_Adm']); ?>" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar?')">
        <i class="fas fa-trash"></i> 
    </a>
</td>


            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
<?php
include_once "../plantilla/piepagina.php";
?>
