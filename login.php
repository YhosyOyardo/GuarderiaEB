<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio de Sesión - Guardería Estrellas de Belén</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="loggin.css" rel="stylesheet">
</head>
<body>
<footer class="footer20">
<div class="button-container">
      <img src="imgInfo/logo2.png" alt="logo" class="logo">
      <a href="login.php" class="square-button">INICIO DE SESIÓN</a>
      <a href="index.php" class="square-button">INFORMACIÓN</a>
    </div>
  </footer>
<!-- Encabezado con video de fondo -->
<header class="header">
  <video autoplay muted loop id="background-video">
    <source src="imgInfo/animaciones.mp4" type="video/mp4">
    Tu navegador no soporta el elemento de video.
  </video>

  <!-- Texto superpuesto al video -->
  <div class="text-overlay">
    <h1>GUARDERIA ESTRELLAS DE BELEN</h1>
    
  </div>
</header>
<!-- Contenedor del formulario de inicio de sesión -->
<main class="page-content">
  <div class="content-box">
    <div class="flex-container">
      <form action="procesar_login.php" method="post">
        <h2 class="login-header">INICIO DE SESIÓN</h2>

        <div class="form-group">
          <label for="infanteCarnet">Carnet del Usuario:</label>
          <input type="text" class="form-control" id="infanteCarnet" name="infanteCarnet" required>
        </div>
        <div class="form-group">
          <label for="password">Contraseña:</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        
        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger mt-3" role="alert">
            <?php
            switch($_GET['error']) {
              case '1':
                echo "Carnet o contraseña incorrectos";
                break;
              case '2':
                echo "Sesión expirada. Por favor, inicie sesión nuevamente";
                break;
              default:
                echo "Error desconocido";
            }
            ?>
          </div>
        <?php endif; ?>
      </form>

      <!-- Imagen alineada a la derecha -->
      <img src="imgInfo/estrellacolumpio.jpeg" alt="Descripción de la imagen" class="login-image">
      
    </div>
  </div>
</main>

<!-- Footer -->
<footer class="footer">
  &copy; 2024 Guardería Estrellas de Belén. Todos los derechos reservados.
</footer>

<script src="js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
