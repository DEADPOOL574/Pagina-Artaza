<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
  header('Location: PaginaPrin.php');
  exit;
}

$stmt = $mysqli->prepare("SELECT id, titulo, contenido, categoria, imagen_url, creado_en FROM noticias WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$noticia = $stmt->get_result()->fetch_assoc();

if (!$noticia) {
  header('Location: PaginaPrin.php');
  exit;
}

// Formatear fecha
$fecha = date('d/m/Y H:i', strtotime($noticia['creado_en']));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($noticia['titulo']); ?> - Respawn News</title>
  <link rel="stylesheet" href="PaginaPrin.css">
  <link rel="stylesheet" href="menu.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    .detalle-container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 0 20px;
    }
    .detalle-header {
      margin-bottom: 30px;
    }
    .detalle-categoria {
      display: inline-block;
      padding: 6px 12px;
      background: #f39c12;
      color: #fff;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 700;
      text-transform: uppercase;
      margin-bottom: 12px;
    }
    .detalle-titulo {
      font-size: 48px;
      font-weight: 900;
      margin: 0 0 12px 0;
      color: #222;
      line-height: 1.2;
    }
    .detalle-fecha {
      color: #666;
      font-size: 14px;
      margin-bottom: 24px;
    }
    .detalle-imagen {
      width: 100%;
      max-height: 500px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 30px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .detalle-contenido {
      font-size: 18px;
      line-height: 1.8;
      color: #333;
      white-space: pre-wrap;
    }
    .volver-btn {
      display: inline-block;
      margin-top: 40px;
      padding: 12px 24px;
      background: linear-gradient(135deg, #f39c12, #e67e22);
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 700;
      transition: transform 0.2s;
    }
    .volver-btn:hover {
      transform: translateY(-2px);
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

  <!-- Contenido del detalle -->
  <div class="detalle-container">
    <div class="detalle-header">
      <span class="detalle-categoria"><?php echo htmlspecialchars($noticia['categoria']); ?></span>
      <h1 class="detalle-titulo"><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
      <p class="detalle-fecha">Publicado el <?php echo htmlspecialchars($fecha); ?></p>
    </div>

    <?php if (!empty($noticia['imagen_url'])): ?>
      <img src="<?php echo htmlspecialchars($noticia['imagen_url']); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>" class="detalle-imagen">
    <?php endif; ?>

    <div class="detalle-contenido">
      <?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?>
    </div>

    <a href="PaginaPrin.php" class="volver-btn">← Volver a noticias</a>
  </div>

  <script src="Funcion.js"></script>
</body>
</html>

