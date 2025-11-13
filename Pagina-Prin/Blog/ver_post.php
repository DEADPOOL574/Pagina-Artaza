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
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
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

$post_id = (int)($_GET['id'] ?? 0);
if (!$post_id) {
  header('Location: Blog.php');
  exit;
}

// Obtener el post
$stmt = $mysqli->prepare("
  SELECT bp.*, u.nombre as autor_nombre,
  (SELECT COUNT(*) FROM blog_likes WHERE post_id = bp.id) as likes_count
  FROM blog_posts bp
  INNER JOIN usuarios u ON bp.usuario_id = u.id
  WHERE bp.id = ?
");
$stmt->bind_param('i', $post_id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
  header('Location: Blog.php');
  exit;
}

// Procesar comentario nuevo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['user_id'])) {
  $contenido = trim($_POST['contenido'] ?? '');
  if (!empty($contenido)) {
    $stmt = $mysqli->prepare("INSERT INTO blog_comentarios (post_id, usuario_id, contenido) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $post_id, $_SESSION['user_id'], $contenido);
    $stmt->execute();
    header('Location: ver_post.php?id=' . $post_id);
    exit;
  }
}

// Obtener comentarios
$stmt = $mysqli->prepare("
  SELECT bc.*, u.nombre as usuario_nombre
  FROM blog_comentarios bc
  INNER JOIN usuarios u ON bc.usuario_id = u.id
  WHERE bc.post_id = ?
  ORDER BY bc.creado_en ASC
");
$stmt->bind_param('i', $post_id);
$stmt->execute();
$comentarios = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['titulo']); ?> - Blog</title>
    <link rel="stylesheet" href="Blog.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="blog-header">
        <div class="header-bar">
            <a class="back" href="Blog.php" aria-label="Volver">←</a>
            <h1>Publicación</h1>
        </div>
    </header>

    <main class="container">
        <article class="post">
            <?php if (!empty($post['imagen_url'])): ?>
                <img class="post-image" src="<?php echo htmlspecialchars($post['imagen_url']); ?>" alt="<?php echo htmlspecialchars($post['titulo']); ?>">
            <?php endif; ?>
            <div class="post-content">
                <h2 class="post-author">
                    <?php echo htmlspecialchars($post['autor_nombre']); ?>
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
                    <span class="action">💬 <span><?php echo $comentarios->num_rows; ?></span></span>
                </div>
            </div>
        </article>

        <!-- Comentarios -->
        <section class="comments">
            <h3>Comentarios (<?php echo $comentarios->num_rows; ?>)</h3>
            <ul class="comment-list">
                <?php if ($comentarios->num_rows === 0): ?>
                    <li style="text-align:center; color:#999; padding:20px;">No hay comentarios aún. ¡Sé el primero en comentar!</li>
                <?php else: ?>
                    <?php while($comentario = $comentarios->fetch_assoc()): ?>
                        <li>
                            <b><?php echo htmlspecialchars($comentario['usuario_nombre']); ?>:</b>
                            <span><?php echo nl2br(htmlspecialchars($comentario['contenido'])); ?></span>
                            <small style="display:block; color:#999; margin-top:4px; font-size:12px;">
                                <?php echo date('d/m/Y H:i', strtotime($comentario['creado_en'])); ?>
                            </small>
                        </li>
                    <?php endwhile; ?>
                <?php endif; ?>
            </ul>

            <?php if (!empty($_SESSION['user_id'])): ?>
                <form class="comment-form" method="post" aria-label="Agregar comentario">
                    <label for="comentario" class="sr-only">Comentario</label>
                    <textarea id="comentario" name="contenido" placeholder="Escribe tu comentario..." required></textarea>
                    <button type="submit">Publicar</button>
                </form>
            <?php else: ?>
                <p style="padding:16px; text-align:center; color:#666;">
                    <a href="/ArtazaFinal/InicioDeSesion/InicioSesion.php" style="color:#f39c12; font-weight:700;">Inicia sesión</a> para comentar
                </p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>

