<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// Asegurar tablas del blog si no existen
$mysqli->query("CREATE TABLE IF NOT EXISTS blog_posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  titulo VARCHAR(200) NOT NULL,
  contenido TEXT NOT NULL,
  imagen_url VARCHAR(400) NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$mysqli->query("CREATE TABLE IF NOT EXISTS blog_comentarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  usuario_id INT NOT NULL,
  contenido TEXT NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$mysqli->query("CREATE TABLE IF NOT EXISTS blog_likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  usuario_id INT NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_like (post_id, usuario_id),
  FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Obtener posts del blog con información del usuario
$res = $mysqli->query("
  SELECT bp.*, u.nombre as autor_nombre,
  (SELECT COUNT(*) FROM blog_likes WHERE post_id = bp.id) as likes_count,
  (SELECT COUNT(*) FROM blog_comentarios WHERE post_id = bp.id) as comentarios_count
  FROM blog_posts bp
  INNER JOIN usuarios u ON bp.usuario_id = u.id
  ORDER BY bp.creado_en DESC
  LIMIT 20
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blog: publicaciones y opiniones de la comunidad.">
    <title>Blog - Respawn News</title>
    <link rel="stylesheet" href="Blog.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header principal -->
    <header class="blog-header">
        <div class="header-bar">
            <a class="back" href="../PaginaPrin.php" aria-label="Volver">←</a>
            <h1>Blog</h1>
            <div class="header-actions" aria-label="Acciones">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="crear_post.php" style="background:#f39c12; color:#fff; padding:8px 12px; border-radius:8px; text-decoration:none; font-weight:700; font-size:14px;">+ Publicar</a>
                <?php endif; ?>
            </div>
        </div>
        <p class="subtitle">Observa todo lo que publica y opina la gente</p>
    </header>

    <main class="container">
        <?php if ($res->num_rows === 0): ?>
            <div class="empty-state">
                <h2>No hay publicaciones aún</h2>
                <p>¡Sé el primero en publicar algo en el blog!</p>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="crear_post.php" class="btn-crear-post">+ Crear primera publicación</a>
                <?php else: ?>
                    <p><a href="/ArtazaFinal/InicioDeSesion/InicioSesion.php">Inicia sesión</a> para publicar</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php while($post = $res->fetch_assoc()): ?>
                <article class="post">
                    <?php if (!empty($post['imagen_url'])): ?>
                        <img class="post-image" src="<?php echo htmlspecialchars($post['imagen_url']); ?>" alt="<?php echo htmlspecialchars($post['titulo']); ?>">
                    <?php endif; ?>
                    <div class="post-content">
                        <h2 class="post-author">
                            <a href="../Perfil/perfil.php?id=<?php echo (int)$post['usuario_id']; ?>" style="text-decoration:none; color:inherit;">
                                <?php echo htmlspecialchars($post['autor_nombre']); ?>
                            </a>
                            <span style="font-weight:400; color:#999; font-size:14px;">
                                publicó el <?php echo date('d/m/Y H:i', strtotime($post['creado_en'])); ?>
                            </span>
                        </h2>
                        <h3 class="post-titulo"><?php echo htmlspecialchars($post['titulo']); ?></h3>
                        <p class="post-text">
                            <?php echo nl2br(htmlspecialchars($post['contenido'])); ?>
                        </p>
                        <div class="post-actions">
                            <button class="action like-btn" data-post-id="<?php echo (int)$post['id']; ?>" title="Me gusta">
                                ❤ <span class="like-count"><?php echo (int)$post['likes_count']; ?></span>
                            </button>
                            <a href="ver_post.php?id=<?php echo (int)$post['id']; ?>" class="action" title="Ver comentarios">
                                💬 <span><?php echo (int)$post['comentarios_count']; ?></span>
                            </a>
                            <button class="action" title="Compartir">↗</button>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>
    <script>
        // Funcionalidad de likes (simplificada - requiere backend completo)
        document.querySelectorAll('.like-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const postId = this.dataset.postId;
                // Aquí iría la llamada AJAX para dar like
                console.log('Like en post:', postId);
            });
        });
    </script>
</body>
</html>

