<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
$stmt = $mysqli->prepare("SELECT id, titulo, contenido, categoria, imagen_url, creado_en FROM noticias WHERE categoria = 'novedades' ORDER BY creado_en DESC LIMIT 10");
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Novedades: últimas noticias de anime y videojuegos.">
    <title>Novedades</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="Novedades.css">
    <link rel="stylesheet" href="../menu.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Encabezado principal estilo captura -->
    <header class="hero" style="position:relative;">
        <h1 style="font-family: 'Bebas Neue', sans-serif; text-align:center;">Novedades</h1>
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
                <a href="../Cursos/cursos.php">Ver cursos</a>
            </div>
        </div>
        <nav class="subnav" aria-label="Navegación secundaria">
            <a href="../PaginaPrin.php">Inicio</a>
            <a href="../PaginaAnime/Anime.php">Anime</a>
            <a href="../PaginaVideojuegos/Videojuego.php">Videojuegos</a>
        </nav>
    </header>

    <main class="container" id="inicio">

        <!-- Bloque destacado con imagen + texto -->
        <section class="destacado">
            <img class="destacado-img" src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=900" alt="Mano humana saludando a mano robótica">
            <div class="destacado-texto">
                <h2>La alianza entre humanos y robots es cada vez más real</h2>
                <p>
                    Lo que antes parecía <i>ciencia ficción</i>, hoy está más cerca que nunca: 
                    la colaboración entre humanos e <strong>inteligencia artificial</strong> ya se aplica en medicina, 
                    educación y hasta en la creación de <b>anime</b> y videojuegos.
                </p>
                <hr>
                <p style="background-color:#fff5e6; color:#333; font-family:Arial; font-size:16px; text-align:left;">
                    Expertos aseguran que esta fusión no busca reemplazarnos, sino potenciar nuestras habilidades. 
                    Desde asistentes virtuales hasta brazos robóticos para cirugías, el futuro ya está entre nosotros.
                </p>
            </div>
        </section>

        <!-- Listas de lectura sugerida -->
        <section class="listas" id="anime">
            <h3>Lecturas recomendadas (Anime)</h3>
            <ul>
                <li>Nuevos animes que llegan en 2025</li>
                <li>Guía de estreno por temporadas</li>
                <li>Mejores openings del año</li>
            </ul>

            <h3>Top 3 temas del mes</h3>
            <ol>
                <li>Universos compartidos</li>
                <li>Películas de animación</li>
                <li>Series que regresan</li>
            </ol>

            <h3>Glosario rápido</h3>
            <dl>
                <dt>Mecha</dt>
                <dd>- Subgénero centrado en robots gigantes.</dd>
                <dt>Seiyuu</dt>
                <dd>- Actor o actriz de voz en japonés.</dd>
            </dl>
        </section>

        <!-- Noticias dinámicas desde BD -->
        <section class="tabla-simple" id="videojuegos">
            <h3>Últimas novedades</h3>
            <?php 
            $count = 0;
            while($row = $res->fetch_assoc()): 
              $count++;
              $fecha = date('d/m/Y', strtotime($row['creado_en']));
              $imagen = !empty($row['imagen_url']) ? $row['imagen_url'] : 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=900';
              $contenido_corto = strlen($row['contenido']) > 300 ? substr($row['contenido'], 0, 300) . '...' : $row['contenido'];
            ?>
            <a href="../detalle_noticia.php?id=<?php echo (int)$row['id']; ?>" style="text-decoration:none; color:inherit;">
              <div class="destacado" style="margin-bottom:20px; cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <?php if (!empty($row['imagen_url'])): ?>
                  <img class="destacado-img" src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
                <?php endif; ?>
                <div class="destacado-texto">
                  <p style="color:#666; font-size:14px; margin:0 0 8px 0;"><?php echo htmlspecialchars($fecha); ?></p>
                  <h2><?php echo htmlspecialchars($row['titulo']); ?></h2>
                  <p><?php echo nl2br(htmlspecialchars($contenido_corto)); ?></p>
                  <p style="color:#f39c12; font-weight:700; margin-top:12px;">Leer más →</p>
                </div>
              </div>
            </a>
            <?php endwhile; ?>
            
            <?php if ($count === 0): ?>
              <div class="destacado">
                <div class="destacado-texto">
                  <h2>No hay novedades publicadas aún</h2>
                  <p>Las noticias aparecerán aquí cuando se creen desde el panel de administración.</p>
                </div>
              </div>
            <?php endif; ?>
        </section>

        <!-- Video de YouTube embebido -->
        <section class="media">
            <h3>Resumen semanal</h3>
            <div class="ratio">
                <iframe 
                    src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                    title="Resumen semanal en YouTube" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
                </iframe>
            </div>
        </section>

        <!-- Texto preformateado de ejemplo -->
        <pre>
Hora   Evento
10:00  Publicación
18:00  Directo en vivo
        </pre>

    </main>

    <!-- Comentario: Puedes enlazar esta página desde tu menú principal -->

    <script src="../Funcion.js"></script>
</body>
</html>


