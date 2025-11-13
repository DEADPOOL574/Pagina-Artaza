<?php
session_start();
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Preguntas Frecuentes - Respawn News</title>
  <link rel="stylesheet" href="PaginaPrin.css">
  <link rel="stylesheet" href="menu.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    .faq-container {
      max-width: 900px;
      margin: 0 auto;
      padding: 40px 20px;
    }
    
    .faq-titulo {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 48px;
      color: #000;
      margin-bottom: 40px;
      text-align: center;
    }
    
    .faq-item {
      background-color: #f9f9f9;
      border-radius: 8px;
      margin-bottom: 20px;
      padding: 25px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .faq-pregunta {
      font-size: 20px;
      font-weight: 700;
      color: #f7931e;
      margin-bottom: 12px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .faq-pregunta:hover {
      color: #d67a0a;
    }
    
    .faq-respuesta {
      font-size: 16px;
      line-height: 1.6;
      color: #333;
      margin-top: 10px;
      display: none;
    }
    
    .faq-respuesta.activa {
      display: block;
    }
    
    .faq-icono {
      font-size: 24px;
      transition: transform 0.3s;
    }
    
    .faq-item.activa .faq-icono {
      transform: rotate(180deg);
    }
    
    .faq-seccion {
      margin-bottom: 50px;
    }
    
    .faq-seccion-titulo {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 32px;
      color: #000;
      margin-bottom: 25px;
      border-bottom: 3px solid #f7931e;
      padding-bottom: 10px;
    }
  </style>
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

      <div class="header-right">
        <div class="clock" id="clock">00:00:00</div>
        <div class="icons">
          <button class="menu-icon" id="menu-btn" aria-label="Abrir menú">☰</button>
        </div>
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
          <a href="PreguntasFrecuentes.php">Preguntas frecuentes</a>
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
    <div class="faq-container">
      <h1 class="faq-titulo">Preguntas Frecuentes</h1>
      
      <div class="faq-seccion">
        <h2 class="faq-seccion-titulo">Cuenta y Perfil</h2>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Cómo puedo crear una cuenta?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Para crear una cuenta, haz clic en "Registrarse" en la página principal. Necesitarás proporcionar tu nombre, email y una contraseña segura. Una vez completado el registro, recibirás un email de confirmación.
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Cómo puedo editar mi perfil?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Puedes editar tu perfil accediendo al menú desplegable (botón de tres líneas) y seleccionando "Ver perfil". Desde allí podrás actualizar tu información personal, foto de perfil y preferencias.
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Olvidé mi contraseña, qué hago?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Si olvidaste tu contraseña, puedes usar la opción "¿Olvidaste tu contraseña?" en la página de inicio de sesión. Te enviaremos un enlace a tu email para restablecerla.
          </div>
        </div>
      </div>
      
      <div class="faq-seccion">
        <h2 class="faq-seccion-titulo">Noticias y Contenido</h2>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Con qué frecuencia se publican nuevas noticias?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Publicamos noticias regularmente sobre anime, videojuegos y otras novedades. La frecuencia depende de los eventos y lanzamientos importantes en el mundo del entretenimiento. Puedes seguirnos para recibir notificaciones de nuevas publicaciones.
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Puedo compartir las noticias en redes sociales?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            ¡Por supuesto! Todas nuestras noticias pueden ser compartidas en tus redes sociales favoritas. En cada artículo encontrarás botones para compartir en Facebook, Twitter, Instagram y otras plataformas.
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Cómo puedo buscar noticias específicas?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Puedes usar la barra de búsqueda ubicada en el encabezado de la página. Simplemente escribe palabras clave relacionadas con la noticia que buscas y presiona Enter o haz clic en el botón de búsqueda.
          </div>
        </div>
      </div>
      
      <div class="faq-seccion">
        <h2 class="faq-seccion-titulo">Cursos y Educación</h2>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Los cursos son gratuitos?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Ofrecemos tanto cursos gratuitos como de pago. Los cursos gratuitos están disponibles para todos los usuarios registrados, mientras que los cursos premium ofrecen contenido más avanzado y certificaciones.
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Puedo obtener un certificado al completar un curso?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Sí, al completar exitosamente un curso premium recibirás un certificado digital que puedes descargar y compartir. Los certificados están disponibles en tu perfil en la sección "Mis Cursos".
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Cómo puedo acceder a mis cursos?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Puedes acceder a tus cursos desde el menú desplegable seleccionando "Ver cursos". Allí encontrarás todos los cursos en los que estás inscrito y tu progreso en cada uno.
          </div>
        </div>
      </div>
      
      <div class="faq-seccion">
        <h2 class="faq-seccion-titulo">Acceso de Administrador</h2>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Cómo puedo acceder al panel de administración?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Para acceder al panel de administración, necesitas tener una cuenta de administrador. Ve a la siguiente URL en tu navegador: <strong>http://localhost/ArtazaFinal/Pagina-Prin/Admin/login.php</strong><br><br>
            Asegúrate de que XAMPP esté ejecutándose (Apache y MySQL activos). Si es la primera vez, es posible que necesites crear una cuenta de administrador desde el panel de configuración.
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Qué puedo hacer en el panel de administración?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Desde el panel de administración puedes gestionar noticias (crear, editar, eliminar), administrar usuarios registrados, gestionar cursos, ver estadísticas del sitio y acceder a la galería de imágenes utilizadas en las noticias.
          </div>
        </div>
      </div>
      
      <div class="faq-seccion">
        <h2 class="faq-seccion-titulo">Soporte y Contacto</h2>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Cómo puedo contactar con el soporte?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Puedes contactarnos a través del formulario de contacto en la sección "Quiénes somos" o enviando un email directamente. Nuestro equipo de soporte responderá en un plazo de 24-48 horas.
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-pregunta" onclick="toggleFaq(this)">
            <span>¿Tienen presencia en redes sociales?</span>
            <span class="faq-icono">▼</span>
          </div>
          <div class="faq-respuesta">
            Sí, puedes seguirnos en nuestras redes sociales para estar al día con las últimas noticias, actualizaciones y contenido exclusivo. Encuentra los enlaces en el pie de página de nuestro sitio web.
          </div>
        </div>
      </div>
    </div>
  </main>
  
  <script>
    function toggleFaq(element) {
      const item = element.closest('.faq-item');
      const respuesta = item.querySelector('.faq-respuesta');
      const isActiva = item.classList.contains('activa');
      
      // Cerrar todas las demás
      document.querySelectorAll('.faq-item').forEach(faq => {
        if (faq !== item) {
          faq.classList.remove('activa');
          faq.querySelector('.faq-respuesta').classList.remove('activa');
        }
      });
      
      // Toggle del item actual
      if (isActiva) {
        item.classList.remove('activa');
        respuesta.classList.remove('activa');
      } else {
        item.classList.add('activa');
        respuesta.classList.add('activa');
      }
    }
  </script>
  <script src="Funcion.js"></script>
</body>
</html>

