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

if (empty($_SESSION['user_id'])) {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php');
  exit;
}

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = trim($_POST['titulo'] ?? '');
  $contenido = trim($_POST['contenido'] ?? '');
  $imagen_url = trim($_POST['imagen_url'] ?? '');
  
  if (empty($titulo) || empty($contenido)) {
    $mensaje = 'El título y el contenido son obligatorios';
    $tipo_mensaje = 'error';
  } else {
    $stmt = $mysqli->prepare("INSERT INTO blog_posts (usuario_id, titulo, contenido, imagen_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('isss', $_SESSION['user_id'], $titulo, $contenido, $imagen_url);
    if ($stmt->execute()) {
      header('Location: Blog.php');
      exit;
    } else {
      $mensaje = 'Error al crear la publicación: ' . $mysqli->error;
      $tipo_mensaje = 'error';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Publicación - Blog</title>
    <link rel="stylesheet" href="Blog.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .form-container { max-width: 800px; margin: 24px auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,.1); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 700; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 16px; }
        .form-group textarea { min-height: 200px; resize: vertical; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #f39c12; box-shadow: 0 0 0 3px rgba(243,156,18,.1); }
        .form-actions { display: flex; gap: 12px; margin-top: 24px; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #f39c12; color: #fff; }
        .btn-secondary { background: #eee; color: #333; }
        .mensaje { padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        .mensaje.error { background: #f8d7da; color: #721c24; }
        .hint { font-size: 12px; color: #666; margin-top: 4px; }
    </style>
</head>
<body>
    <header class="blog-header">
        <div class="header-bar">
            <a class="back" href="Blog.php" aria-label="Volver">←</a>
            <h1>Crear Publicación</h1>
        </div>
    </header>
    
    <div class="form-container">
        <?php if ($mensaje): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" required placeholder="Título de tu publicación">
            </div>
            
            <div class="form-group">
                <label for="contenido">Contenido</label>
                <textarea id="contenido" name="contenido" required placeholder="Escribe tu publicación aquí..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="imagen_url">URL de imagen (opcional)</label>
                <input type="text" id="imagen_url" name="imagen_url" placeholder="https://ejemplo.com/imagen.jpg">
                <span class="hint">Pega una URL de imagen para ilustrar tu publicación</span>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Publicar</button>
                <a href="Blog.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

