<?php  
    $ruta = "../"; // Establecer la ruta base para incluir otros archivos
   include_once $ruta."plantilla/cabecera.php"; // Incluir la cabecera del sitio
?> 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Configuración de codificación de caracteres -->
    <title>Registro de Administrador</title> <!-- Título de la página -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configuración para hacer el sitio responsive -->
    
    <!-- Inclusión del CSS de Bootstrap para estilos predeterminados -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Inclusión del archivo CSS separado para los estilos del formulario -->
    <link rel="stylesheet" href="nuevo.css"> <!-- Asegúrate de que la ruta sea correcta -->
</head>

<body>

<!-- Video de fondo -->
<video autoplay muted loop class="video-background"> <!-- Agrega las clases y atributos para el video -->
    <source src="../imgInfo/flores.mp4" type="video/mp4"> <!-- Ruta a tu video -->
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

<div class="container form-container m-5">
    <h3 class="text-center">REGISTRO DE ADMINISTRACION</h3>

    <form action="registro.php" method="post" onsubmit="return validateForm()"> <!-- Añadido onsubmit para validación -->

        <?php if (isset($_GET['error']) && $_GET['error'] == 'true'): ?>
            <div class="mb-3">
                <div class="error-message">Error: El carnet ya está registrado.</div>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="carnetAdm" class="form-label">Carnet:</label>
            <input type="number" id="carnetAdm" name="carnetAdm" class="form-control" placeholder="Ingrese el carnet" required>
        </div>

        <div class="mb-3">
            <label for="nombreAdm" class="form-label">Nombre del Administrador:</label>
            <input type="text" id="nombreAdm" name="nombreAdm" class="form-control" placeholder="Ingrese el nombre" required oninput="validateText(this)">
        </div>

        <div class="mb-3">
            <label for="apellidoAP" class="form-label">Apellido Paterno:</label>
            <input type="text" id="apellidoAP" name="apellidoAP" class="form-control" placeholder="Ingrese el apellido paterno" oninput="validateText(this)">
        </div>

        <div class="mb-3">
            <label for="apellidoAM" class="form-label">Apellido Materno:</label>
            <input type="text" id="apellidoAM" name="apellidoAM" class="form-control" placeholder="Ingrese el apellido materno" oninput="validateText(this)">
        </div>

        <div class="mb-3">
            <label class="form-label">Género:</label><br>
            <div class="form-check form-check-inline">
                <input type="radio" id="masculino" name="genero" value="masculino" class="form-check-input" required>
                <label class="form-check-label" for="masculino_t">Masculino</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="femenino" name="genero" value="femenino" class="form-check-input" required>
                <label class="form-check-label" for="femenino_t">Femenino</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="tipoAdm" class="form-label">Tipo de Administración:</label>
            <select id="tipoAdm" name="tipoAdm" class="form-control" required>
                <option value="" disabled selected>Seleccione el tipo</option>
                <option value="directora">Directora</option>
                <option value="secretaria">Secretaria</option>
                <option value="profesora">Profesora</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección:</label>
            <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Ingrese la dirección" required>
        </div>

        <!-- Campo para la fecha de nacimiento -->
        
 <!-- Campo para la contraseña -->
 <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" class="form-control" placeholder="Ingrese la contraseña" required>
        </div>

        <!-- Botones para enviar el formulario o limpiar los campos -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Registrar Administrador</button> <!-- Botón de envío -->
            <button type="reset" class="btn btn-secondary">Limpiar</button> <!-- Botón para limpiar campos -->
        </div></form>
</div>

<script>
// Función para calcular la edad al ingresar la fecha de nacimiento
function calculateAge() {
    const birthDate = new Date(document.getElementById('fechaNacimiento').value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    document.getElementById('edad').value = age;
}

// Función para validar que solo se ingresen letras en nombre y apellidos
function validateText(input) {
    const regex = /^[a-zA-Z\s]*$/;
    if (!regex.test(input.value)) {
        input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
    }
}

// Función para verificar que al menos uno de los apellidos esté completado
function validateForm() {
    const apellidoAP = document.getElementById('apellidoAP').value;
    const apellidoAM = document.getElementById('apellidoAM').value;
    if (!apellidoAP && !apellidoAM) {
        alert("Debe ingresar al menos uno de los apellidos (paterno o materno).");
        return false;
    }
    return true;
}
</script>

  

<!-- Inclusión de scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
    include_once "../plantilla/piepagina.php"; // Incluir el pie de página del sitio
