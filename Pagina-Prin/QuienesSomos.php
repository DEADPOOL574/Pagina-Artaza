<?php
session_start();
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiénes somos - Respawn News</title>
  <link rel="stylesheet" href="PaginaPrin.css">
  <link rel="stylesheet" href="menu.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Encabezado -->
  <header>
    <div class="header-content">
      <h1><a href="PaginaPrin.php" style="text-decoration:none; color:inherit;">Respawn news</a></h1>

      <div class="search-container">
        <form method="get" action="buscar.php" style="display:flex; width:100%;">
          <input type="text" name="q" placeholder="Buscar..." style="flex:1; border:none; outline:none; padding:8px 12px; background-color:transparent; font-size:14px;">
          <button type="submit">+</button>
        </form>
      </div>

      <div class="icons">
        <button class="menu-icon" id="menu-btn" aria-label="Abrir menú">☰</button>
      </div>

      <!-- Menú desplegable -->
      <div class="dropdown" id="dropdown-menu" hidden>
        <div class="dropdown-section">
          <h4>Usuario</h4>
          <a href="Perfil/perfil.php">Ver perfil</a>
          <a href="Perfil/cv.php">Ver/Editar CV</a>
          <a href="/ArtazaFinal/auth/logout.php">Cerrar sesión</a>
        </div>
        <div class="dropdown-section">
          <h4>Cursos</h4>
          <a href="Cursos/cursos.php">Ver cursos</a>
        </div>
        <div class="dropdown-section">
          <h4>Comunidad</h4>
          <a href="Blog/Blog.php">Blog</a>
        </div>
        <div class="dropdown-section">
          <h4>Información</h4>
          <a href="QuienesSomos.php">Quiénes somos</a>
        </div>
      </div>
    </div>
  </header>

  <!-- Menú de navegación -->
  <nav>
    <ul>
      <li><a href="PaginaAnime/Anime.php">Anime</a></li>
      <li><a href="PaginaVideojuegos/Videojuego.php">Videojuegos</a></li>
      <li><a href="Novedades/Novedades.php">Novedades</a></li>
    </ul>
  </nav>

  <!-- Contenido principal -->
  <main>
    <!-- Sección Quiénes somos -->
    <section class="quienes-somos">
      <h2 class="quienes-somos-titulo">Quiénes somos</h2>
      <div class="quienes-somos-contenido">
        <div class="quienes-somos-texto">
          <p>
            <strong>Respawn News</strong> es tu plataforma de referencia para las últimas noticias, 
            análisis y contenido relacionado con <strong>anime</strong> y <strong>videojuegos</strong>. 
            Nuestro equipo está formado por apasionados de la cultura japonesa, los videojuegos 
            y la tecnología, unidos por el objetivo común de crear una comunidad vibrante y 
            bien informada para todos los entusiastas del entretenimiento digital.
          </p>
          <p>
            Nos dedicamos a brindarte contenido de calidad, actualizado y relevante para mantenerte 
            al día con las últimas tendencias, lanzamientos y novedades del mundo del entretenimiento 
            digital y la animación. Nuestro compromiso es ofrecerte análisis profundos, reseñas 
            honestas y cobertura completa de los eventos más importantes del sector, desde los 
            estrenos más esperados hasta los descubrimientos más interesantes de la industria.
          </p>
          <p>
            En <strong>Respawn News</strong>, creemos en el poder de la comunidad. Por eso, además 
            de ofrecerte las mejores noticias y análisis, proporcionamos herramientas para que puedas 
            ser parte activa de nuestra plataforma. Ofrecemos <strong>cursos</strong> especializados 
            diseñados tanto para principiantes como para aquellos que buscan profundizar en áreas 
            específicas del anime, los videojuegos y la tecnología relacionada.
          </p>
          <p>
            Nuestro <strong>blog</strong> comunitario es un espacio abierto donde puedes compartir 
            tus opiniones, experiencias y descubrimientos con otros miembros de la comunidad. 
            Valoramos cada voz y cada perspectiva, creando un ambiente inclusivo donde todos pueden 
            expresarse y aprender unos de otros.
          </p>
          <p>
            También ofrecemos un espacio completo para que los usuarios puedan crear y gestionar 
            su perfil profesional, incluyendo la posibilidad de mantener un CV actualizado que 
            refleje sus habilidades, intereses y logros en el mundo del entretenimiento digital. 
            Esta herramienta te permite conectar con otros profesionales y oportunidades en la industria.
          </p>
          <p>
            Nuestra misión es ser más que un simple sitio de noticias: queremos ser tu compañero 
            de confianza en el viaje por el fascinante mundo del anime y los videojuegos. Ya sea 
            que busques información sobre el último lanzamiento, quieras aprender algo nuevo, 
            o simplemente conectarte con otros apasionados como tú, <strong>Respawn News</strong> 
            está aquí para acompañarte en cada paso.
          </p>
        </div>
      </div>
    </section>
  </main>

  <script src="Funcion.js"></script>
</body>
</html>

