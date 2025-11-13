<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$busqueda = trim($_GET['q'] ?? '');
$resultados = [];
$total = 0;

if (!empty($busqueda)) {
    $busqueda_like = '%' . $mysqli->real_escape_string($busqueda) . '%';
    $stmt = $mysqli->prepare("SELECT id, titulo, contenido, categoria, imagen_url, creado_en FROM noticias WHERE titulo LIKE ? OR contenido LIKE ? ORDER BY creado_en DESC");
    $stmt->bind_param('ss', $busqueda_like, $busqueda_like);
    $stmt->execute();
    $resultados = $stmt->get_result();
    $total = $resultados->num_rows;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Búsqueda<?php echo !empty($busqueda) ? ': ' . htmlspecialchars($busqueda) : ''; ?> - Respawn News</title>
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
          <input type="text" name="q" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>" style="flex:1; border:none; outline:none; padding:8px 12px; background-color:transparent; font-size:14px;">
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
    <section style="padding:30px 80px;">
      <h2 style="font-family:'Bebas Neue', sans-serif; font-size:36px; margin-bottom:20px;">
        <?php if (empty($busqueda)): ?>
          Buscar noticias
        <?php else: ?>
          Resultados de búsqueda: "<?php echo htmlspecialchars($busqueda); ?>"
        <?php endif; ?>
      </h2>

      <?php if (empty($busqueda)): ?>
        <div style="text-align:center; padding:60px 20px;">
          <p style="font-size:18px; color:#666;">Ingresa un término de búsqueda para encontrar noticias</p>
        </div>
      <?php elseif ($total === 0): ?>
        <div style="text-align:center; padding:60px 20px;">
          <p style="font-size:18px; color:#666;">No se encontraron resultados para "<?php echo htmlspecialchars($busqueda); ?>"</p>
          <p style="color:#999; margin-top:10px;">Intenta con otros términos de búsqueda</p>
        </div>
      <?php else: ?>
        <p style="color:#666; margin-bottom:30px;">Se encontraron <?php echo $total; ?> resultado(s)</p>
        
        <?php while($row = $resultados->fetch_assoc()): 
          $fecha = date('d/m/Y', strtotime($row['creado_en']));
          $imagen = !empty($row['imagen_url']) ? $row['imagen_url'] : 'https://via.placeholder.com/350x200?text=Sin+imagen';
          $contenido_corto = strlen($row['contenido']) > 200 ? substr($row['contenido'], 0, 200) . '...' : $row['contenido'];
        ?>
        <a href="detalle_noticia.php?id=<?php echo (int)$row['id']; ?>" style="text-decoration:none; color:inherit;">
          <section class="noticia">
            <div class="noticia-img">
              <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
            </div>
            <div class="noticia-texto">
              <p class="fecha"><?php echo htmlspecialchars($fecha); ?> - <?php echo htmlspecialchars($row['categoria']); ?></p>
              <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
              <p>
                <?php echo nl2br(htmlspecialchars($contenido_corto)); ?>
              </p>
              <p style="color:#f39c12; font-weight:700; margin-top:10px;">Leer más →</p>
            </div>
          </section>
        </a>
        <?php endwhile; ?>
      <?php endif; ?>
    </section>
  </main>

  <script src="Funcion.js"></script>
</body>
</html>

