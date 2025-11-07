<?php
session_start();
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
    header{background:#f7931e; padding:16px 20px; position:relative}
    h1{font-family:'Bebas Neue',sans-serif; margin:0; font-size:64px}
    main{max-width:1000px; margin:24px auto; background:#fff; padding:24px; border-radius:12px}
  </style>
</head>
<body>
  <header>
    <h1>Cursos</h1>
    <button class="menu-icon" id="menu-btn" aria-label="Abrir menú" style="position:absolute; right:16px; top:16px;">☰</button>
    <div class="dropdown" id="dropdown-menu">
      <div class="dropdown-section">
        <h4>Usuario</h4>
        <a href="../Perfil/perfil.php">Ver perfil</a>
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
      </div>
    </div>
  </header>
  <main>
    <h2>Próximamente</h2>
    <p>Aquí verás los cursos disponibles de Anime y Videojuegos.</p>
    <p><a href="../PaginaPrin.php">← Volver al inicio</a></p>
  </main>
  <script src="../Funcion.js"></script>
</body>
</html>
