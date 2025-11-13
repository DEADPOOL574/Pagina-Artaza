<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
$stmt = $mysqli->prepare("SELECT id, titulo, contenido, categoria, imagen_url, creado_en FROM noticias WHERE categoria = 'videojuegos' ORDER BY creado_en DESC LIMIT 10");
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videojuegos</title>
    <link rel="stylesheet" href="Videojuego.css">
    <link rel="stylesheet" href="../menu.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

    <!-- HEADER con botón de menú -->
    <header>
        <div class="header-content">
            <h1>Videojuegos</h1>

            <div class="search-container">
                <input type="text" placeholder="Buscar...">
                <button>+</button>
            </div>

            <div class="icons">
                <button class="menu-icon" id="menu-btn" aria-label="Abrir menú">☰</button>
            </div>

            <!-- Menú desplegable -->
            <div class="dropdown" id="dropdown-menu" hidden>
                <div class="dropdown-section">
                    <h4>Usuario</h4>
                    <a href="../Perfil/perfil.php">Ver perfil</a>
                    <a href="../Perfil/cv.php">Ver/Editar CV</a>
                    <a href="/ArtazaFinal/auth/logout.php">Cerrar sesión</a>
                </div>
                <div class="dropdown-section">
                    <h4>Actividad</h4>
                    <a href="../Actividad/actividad.php">Ver actividad reciente</a>
                    <a href="../Guardados/guardados.php">Publicaciones guardadas</a>
                </div>
                <div class="dropdown-section">
                    <h4>Cursos</h4>
                    <a href="../Cursos/cursos.php">Ver cursos</a>
                </div>
                <div class="dropdown-section">
                    <h4>Comunidad</h4>
                    <a href="../Blog/Blog.php">Blog</a>
                </div>
                <div class="dropdown-section">
                    <h4>Información</h4>
                    <a href="../QuienesSomos.php">Quiénes somos</a>
                </div>
            </div>
        </div>
    </header>

    <!-- BARRA DE NAVEGACIÓN -->
    <nav>
        <ul>
            <li><a href="../PaginaPrin.php">Inicio</a></li>
            <li><a href="../PaginaAnime/Anime.php">Anime</a></li>
            <li><a href="../Novedades/Novedades.php">Novedades</a></li>
        </ul>
    </nav>

    <!-- SECCIÓN "LO MÁS RECIENTE" -->
    <section class="contenedor">
        <h2 class="subtitulo">Lo más reciente</h2>

        <?php 
        $count = 0;
        while($row = $res->fetch_assoc()): 
          $count++;
          $fecha = date('d/m/Y', strtotime($row['creado_en']));
          $imagen = !empty($row['imagen_url']) ? $row['imagen_url'] : '../../Image/top5juegos.webp';
          $contenido_corto = strlen($row['contenido']) > 200 ? substr($row['contenido'], 0, 200) . '...' : $row['contenido'];
        ?>
        <a href="../detalle_noticia.php?id=<?php echo (int)$row['id']; ?>" style="text-decoration:none; color:inherit;">
          <div class="noticia2" style="margin-bottom:30px;">
            <img src="<?php echo htmlspecialchars($imagen); ?>" class="img-noticia" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
            <div class="texto-largo">
              <p class="fecha"><?php echo htmlspecialchars($fecha); ?></p>
              <h2 class="titulo-noticia"><?php echo htmlspecialchars($row['titulo']); ?></h2>
              <p class="texto">
                <?php echo nl2br(htmlspecialchars($contenido_corto)); ?>
              </p>
              <p style="color:#f39c12; font-weight:700; margin-top:10px;">Leer más →</p>
            </div>
          </div>
        </a>
        <?php endwhile; ?>
        
        <?php if ($count === 0): ?>
          <div class="noticia">
            <p class="texto">No hay noticias de videojuegos publicadas aún. Las noticias aparecerán aquí cuando se creen desde el panel de administración.</p>
          </div>
        <?php endif; ?>
    </section>

    <script src="../Funcion.js"></script>
</body>
</html>


