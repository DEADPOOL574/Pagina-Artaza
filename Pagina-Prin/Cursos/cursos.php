<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// Obtener todos los cursos
$res = $mysqli->query("SELECT id, titulo, descripcion, categoria, imagen_url, duracion, nivel, creado_en FROM cursos ORDER BY creado_en DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cursos - Respawn News</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../menu.css">
  <style>
    body{font-family:'Roboto',sans-serif; margin:0; background:#f7f7f7}
    header{background:linear-gradient(135deg,#9b59b6,#8e44ad); padding:16px 20px; position:relative; box-shadow:0 4px 12px rgba(155,89,182,.3)}
    h1{font-family:'Bebas Neue',sans-serif; margin:0; font-size:64px; color:#fff; text-align:center}
    main{max-width:1200px; margin:24px auto; padding:0 20px}
    .cursos-grid{display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:24px; margin-top:24px}
    .curso-card{background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,.1); transition:transform 0.2s, box-shadow 0.2s}
    .curso-card:hover{transform:translateY(-4px); box-shadow:0 8px 24px rgba(0,0,0,.15)}
    .curso-imagen{width:100%; height:200px; object-fit:cover; background:linear-gradient(135deg,#9b59b6,#8e44ad)}
    .curso-content{padding:20px}
    .curso-categoria{display:inline-block; padding:4px 12px; background:#f3e5f5; color:#7b1fa2; border-radius:20px; font-size:12px; font-weight:700; margin-bottom:12px}
    .curso-titulo{margin:0 0 12px 0; font-size:20px; font-weight:700; color:#222}
    .curso-descripcion{color:#666; font-size:14px; line-height:1.6; margin-bottom:16px; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden}
    .curso-info{display:flex; justify-content:space-between; align-items:center; padding-top:16px; border-top:1px solid #eee}
    .curso-nivel{display:inline-block; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700}
    .curso-nivel.principiante{background:#e3f2fd; color:#1976d2}
    .curso-nivel.intermedio{background:#fff3e0; color:#f57c00}
    .curso-nivel.avanzado{background:#fce4ec; color:#c2185b}
    .curso-duracion{color:#999; font-size:12px}
    .empty-state{text-align:center; padding:60px 20px; background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,.1)}
    .empty-state h2{color:#666; margin-bottom:12px}
    .empty-state p{color:#999}
  </style>
</head>
<body>
  <header>
    <h1>Cursos</h1>
    <button class="menu-icon" id="menu-btn" aria-label="Abrir menú" style="position:absolute; right:16px; top:16px; background:transparent; border:none; color:#fff; font-size:24px; cursor:pointer;">☰</button>
    <div class="dropdown" id="dropdown-menu" hidden>
      <div class="dropdown-section">
        <h4>Usuario</h4>
        <a href="../Perfil/perfil.php">Ver perfil</a>
        <a href="../Perfil/cv.php">Ver/Editar CV</a>
        <a href="/ArtazaFinal/auth/logout.php">Cerrar sesión</a>
        <a href="/ArtazaFinal/auth/eliminar_cuenta.php">Eliminar cuenta</a>
      </div>
      <div class="dropdown-section">
        <h4>Actividad</h4>
        <a href="../Actividad/actividad.php">Ver actividad reciente</a>
        <a href="../Guardados/guardados.php">Publicaciones guardadas</a>
      </div>
      <div class="dropdown-section">
        <h4>Cursos</h4>
        <a href="cursos.php">Ver cursos</a>
      </div>
      <div class="dropdown-section">
        <h4>Comunidad</h4>
        <a href="../Blog/Blog.php">Blog</a>
      </div>
    </div>
  </header>
  <main>
    <?php if ($res->num_rows === 0): ?>
      <div class="empty-state">
        <h2>No hay cursos disponibles</h2>
        <p>Los cursos aparecerán aquí cuando se creen desde el panel de administración.</p>
        <p><a href="../PaginaPrin.php">← Volver al inicio</a></p>
      </div>
    <?php else: ?>
      <div class="cursos-grid">
        <?php while($curso = $res->fetch_assoc()): ?>
          <div class="curso-card">
            <?php if (!empty($curso['imagen_url'])): ?>
              <img src="<?php echo htmlspecialchars($curso['imagen_url']); ?>" alt="<?php echo htmlspecialchars($curso['titulo']); ?>" class="curso-imagen">
            <?php else: ?>
              <div class="curso-imagen"></div>
            <?php endif; ?>
            <div class="curso-content">
              <span class="curso-categoria"><?php echo htmlspecialchars($curso['categoria']); ?></span>
              <h3 class="curso-titulo"><?php echo htmlspecialchars($curso['titulo']); ?></h3>
              <p class="curso-descripcion"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
              <div class="curso-info">
                <span class="curso-nivel <?php echo htmlspecialchars($curso['nivel']); ?>"><?php echo htmlspecialchars($curso['nivel']); ?></span>
                <?php if (!empty($curso['duracion'])): ?>
                  <span class="curso-duracion">⏱️ <?php echo htmlspecialchars($curso['duracion']); ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </main>
  <script src="../Funcion.js"></script>
</body>
</html>
