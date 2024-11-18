<!DOCTYPE html>  
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio de Sesión - Guardería Estrellas de Belén</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="index.css" rel="stylesheet">
</head>

<body>
  <div class="header">
    <video autoplay muted loop id="background-video">
      <source src="imgInfo/animaciones.mp4" type="video/mp4">
      Tu navegador no soporta el elemento de video.
    </video>
    <h1>GUARDERIA ESTRELLAS DE BELEN</h1>
    <h3>Donde la diversión y el aprendizaje nunca se detienen.</h3>
    <div class="button-container">
      <img src="imgInfo/logo2.png" alt="Logo de la Guardería" class="logo">
      <a href="login.php" class="square-button">INICIO DE SESIÓN</a>
      <a href="index.php" class="square-button">INFORMACIÓN</a>
     
    </div>
  </div>

<!-- Mensaje de bienvenida debajo del video -->
<div class="welcome-message">
    <img src="imgInfo/niño.jpg" alt="Descripción de la otra imagen" class="otra-imagen">
    <div class="text-container">
        <h2>BIENVENIDOS!!!</h2>
        <p>En Guardería Estrellas de Belén, no solo cuidamos a tus hijos; ¡los llevamos a un fascinante viaje de aventuras! Cada día es una nueva oportunidad para explorar, aprender y, por supuesto, hacer travesuras. ¿Quién dijo que la guardería tenía que ser monótona? Aquí, ¡la diversión nunca se detiene!</p>
        <p>Nuestra misión es simple pero poderosa: crear un entorno donde los pequeños puedan soñar en grande y vivir sus sueños...</p>
    </div>
</div>
<!--*****************************                                  -->
<div class="novedades">
    <h1>NOVEDADES DE LA GUARDERÍA</h1>
    <div class="novedad-container">
    <div class="novedad-item">
        <img src="imgInfo/lugar.jpg" alt="Descripción de la imagen 1" class="novedad-imagen">
        <h2>JUEGOS DIDÁCTICOS</h2>
      </div>

    <div class="novedad-item">
       
        <img src="imgInfo/basqet.jpg" alt="Descripción de la imagen 1" class="novedad-imagen">
        <h2>APRENDIZAJE ÓPTIMO</h2>
      </div>
    
    <div class="novedad-item">
       
        <img src="imgInfo/niño.jpg" alt="Descripción de la imagen 1" class="novedad-imagen">
        <h2>TRABAJOS MANUALES</h2>
      </div>

    <div class="novedad-item">
       
        <img src="imgInfo/cm2.jpg" alt="Descripción de la imagen 1"class="novedad-imagen">
        <h2>CONVIVENCIA</h2>
      </div>
      <script>
    const imagenes = document.querySelectorAll('.novedad-imagen');

    imagenes.forEach(imagen => {
        imagen.addEventListener('click', () => {
            // Alterna la clase 'zoom' para agrandar la imagen
            imagen.classList.toggle('zoom');
        });
    });
</script>

</div>
</div>

<div class="guarderia-info"> 
    <h3>PREGUNTAS FRECUENTES</h3>

    <div class="faq">
        <div class="question">¿Qué actividades se ofrecen para los niños?</div>
        <p class="answer">Ofrecemos una variedad de actividades educativas y recreativas, incluyendo juegos, manualidades, música, y actividades al aire libre que fomentan el desarrollo integral de los niños.</p>
    </div>
    
    <div class="faq">
        <div class="question">¿Cómo es el personal de la guardería?</div>
        <p class="answer">Nuestro personal está altamente capacitado y es amable, dedicado y apasionado por el cuidado y la educación de los niños. Contamos con educadores, asistentes y personal de apoyo que garantizan un ambiente seguro y estimulante.</p>
    </div>
    
    <div class="faq">
        <div class="question">¿Qué edades aceptan en la guardería?</div>
        <p class="answer">Aceptamos niños desde 1 año hasta 5 años.</p>
    </div>
    
    <div class="faq">
        <div class="question">¿Cuáles son los requisitos para la inscripción en la guardería?</div>
        <p class="answer">Los requisitos para la inscripción incluyen:
        
            Certificado de nacimiento del niño (fotocopia y original).
            Carnet original y fotocopia de los tutores y del infante.
            Factura de luz o agua (original y fotocopia).</p>
        
    </div>
    
    <div class="faq">
        <div class="question">¿Cuál es el costo de la mensualidad?</div>
        <p class="answer">La mensualidad es de 300 Bs.</p>
    </div>
    
    <div class="faq">
        <div class="question">¿Cuáles son los horarios de atención?</div>
        <p class="answer">Atendemos de lunes a viernes en los siguientes turnos:
        
            Turno de mañana: 7:30 a 12:00
            Turno de tarde: 13:00 a 18:00
</p>
    </div>
</div>


<script src="script.js">
    // Seleccionamos todas las preguntas
    const questions = document.querySelectorAll('.question');

    questions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling; // Seleccionamos el siguiente elemento que es la respuesta

            if (answer.style.display === "block") {
                answer.style.display = "none"; // Ocultar respuesta si ya está visible
            } else {
                answer.style.display = "block"; // Mostrar respuesta si está oculta
            }
        });
    });
</script> <!-- Asegúrate de enlazar tu archivo JavaScript -->
<!-- Mapa embebido -->
<iframe
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3827.0135730302097!2d-68.187886!3d-16.5968444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915ed9f70651401b%3A0x54b9da66eeaaa8d8!2sEstrellita%20De%20Belen!5e0!3m2!1ses!2sbo!4v1698698800846!5m2!1ses!2sbo"
    width="600"
    height="650"
    class="map-frame"
    allowfullscreen=""
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
</div>


  <footer>
    <div class="container">
      <div class="copy text-center">
        <h4>2024 Guardería Estrellas de Belen.Todos los derechos reservados</h4>
      </div>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
