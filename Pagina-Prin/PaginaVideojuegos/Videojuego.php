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
            <h1>VIDEOJUEGOS</h1>

            <div class="search-container">
                <form method="get" action="../buscar.php" style="display:flex; width:100%;">
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
                    <a href="../Perfil/perfil.php">Ver perfil</a>
                    <a href="../Perfil/cv.php">Ver/Editar CV</a>
                    <a href="/ArtazaFinal/auth/logout.php">Cerrar sesión</a>
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

    <!-- Contenido principal -->
    <main>
        <?php 
        $count = 0;
        while($row = $res->fetch_assoc()): 
          $count++;
          $fecha = date('d/m/Y', strtotime($row['creado_en']));
          $imagen = !empty($row['imagen_url']) ? $row['imagen_url'] : 'https://via.placeholder.com/320x200?text=Videojuegos';
          $contenido_corto = strlen($row['contenido']) > 250 ? substr($row['contenido'], 0, 250) . '...' : $row['contenido'];
        ?>
        <a href="../detalle_noticia.php?id=<?php echo (int)$row['id']; ?>" style="text-decoration:none; color:inherit;">
          <section class="noticia">
            <div class="noticia-img">
              <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
            </div>
            <div class="noticia-texto">
              <?php if ($count === 1): ?>
                <h2>Lo más reciente</h2>
              <?php endif; ?>
              <p class="fecha"><?php echo htmlspecialchars($fecha); ?></p>
              <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
              <p>
                <?php echo nl2br(htmlspecialchars($contenido_corto)); ?>
              </p>
              <p style="color:#f39c12; font-weight:700; margin-top:10px;">Leer más →</p>
            </div>
          </section>
        </a>
        <?php endwhile; ?>
        
        <?php if ($count === 0): ?>
          <section class="noticia">
            <div class="noticia-texto">
              <h2>Lo más reciente</h2>
              <p>No hay noticias de videojuegos publicadas aún. Las noticias aparecerán aquí cuando se creen desde el panel de administración.</p>
            </div>
          </section>
        <?php endif; ?>
    </main>

    <script src="../Funcion.js"></script>
</body>
</html>


