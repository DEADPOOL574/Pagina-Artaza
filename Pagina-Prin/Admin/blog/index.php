<?php 
require_once __DIR__ . '/../../../config/admin_guard.php';
require_once __DIR__ . '/../../../config/db.php';

// Asegurar que la tabla existe
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

// Obtener todas las publicaciones del blog con información del autor
$res = $mysqli->query("
  SELECT bp.id, bp.titulo, bp.contenido, bp.imagen_url, bp.creado_en,
         u.nombre as autor_nombre, u.email as autor_email,
         (SELECT COUNT(*) FROM blog_comentarios WHERE post_id = bp.id) as comentarios_count,
         (SELECT COUNT(*) FROM blog_likes WHERE post_id = bp.id) as likes_count
  FROM blog_posts bp
  INNER JOIN usuarios u ON bp.usuario_id = u.id
  ORDER BY bp.creado_en DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión del Blog</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
  <style>
    :root{ --brand:#f39c12; --brand2:#e67e22; --ok:#2e7d32; --danger:#e74c3c }
    *{box-sizing:border-box}
    body{font-family:Roboto, sans-serif; margin:0; background:#f5f7fa}
    .topbar{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; padding:16px 24px; box-shadow:0 6px 20px rgba(243,156,18,.35)}
    .topbar h1{margin:0; font-family:'Bebas Neue'; font-size:44px; letter-spacing:1px}
    .container{max-width:1200px; margin:24px auto; background:#fff; border-radius:16px; box-shadow:0 10px 28px rgba(0,0,0,.08); overflow:hidden}
    .actionsbar{display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid #eee; flex-wrap:wrap; gap:12px}
    .btn{padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700; display:inline-block; border:none; cursor:pointer}
    .btn-primary{background:linear-gradient(135deg,var(--ok),#1b5e20); color:#fff}
    .btn-back{background:linear-gradient(135deg,#607d8b,#455a64); color:#fff}
    .btn-danger{background:linear-gradient(135deg,var(--danger),#c0392b); color:#fff}
    .btn:hover{opacity:.9; transform:translateY(-1px); box-shadow:0 4px 8px rgba(0,0,0,.2)}
    .table{width:100%; border-collapse:collapse}
    .table thead th{background:#fafafa; text-align:left; padding:12px; font-size:14px; color:#555; font-weight:700}
    .table tbody td{padding:12px; border-top:1px solid #eee}
    .table tbody tr:hover{background:#f9f9f9}
    .pill{padding:4px 10px; border-radius:999px; font-size:12px; background:#fff3e0; color:#e65100; border:1px solid #ffe0b2; display:inline-block}
    .row-actions{display:flex; gap:8px; flex-wrap:wrap}
    .row-actions a, .row-actions button{padding:6px 12px; border-radius:6px; text-decoration:none; font-size:13px; font-weight:600; border:none; cursor:pointer}
    .row-actions .btn-edit{background:#3498db; color:#fff}
    .row-actions .btn-delete{background:var(--danger); color:#fff}
    .row-actions .btn-view{background:#95a5a6; color:#fff}
    .row-actions a:hover, .row-actions button:hover{opacity:.85; transform:scale(1.05)}
    .post-preview{max-width:300px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:#666; font-size:13px}
    .stats-badge{display:inline-block; padding:2px 8px; background:#ecf0f1; border-radius:4px; font-size:11px; margin-left:5px; color:#7f8c8d}
    .empty-state{text-align:center; padding:60px 20px; color:#999}
    .empty-state h2{margin-bottom:10px; color:#666}
    .imagen-preview{width:60px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #ddd}
  </style>
</head>
<body>
  <div class="topbar"><h1>Gestión del Blog</h1></div>
  <div class="container">
    <div class="actionsbar">
      <div style="display:flex; gap:12px; align-items:center;">
        <a class="btn btn-back" href="../PaginaAdmin.php">← Volver al panel</a>
        <div>Listado de publicaciones del blog</div>
      </div>
      <div style="display:flex; gap:12px; align-items:center;">
        <a class="btn btn-primary" href="../../Blog/Blog.php" target="_blank">👁️ Ver blog público</a>
      </div>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
      <div style="background:#d4edda; color:#155724; padding:12px 20px; border-left:4px solid #28a745; margin:16px 20px; border-radius:4px;">
        ✓ <?php echo htmlspecialchars($_GET['success']); ?>
      </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
      <div style="background:#f8d7da; color:#721c24; padding:12px 20px; border-left:4px solid #dc3545; margin:16px 20px; border-radius:4px;">
        ✗ <?php echo htmlspecialchars($_GET['error']); ?>
      </div>
    <?php endif; ?>
    
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Imagen</th>
          <th>Título</th>
          <th>Autor</th>
          <th>Contenido</th>
          <th>Estadísticas</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($res->num_rows === 0): ?>
          <tr>
            <td colspan="8" class="empty-state">
              <h2>No hay publicaciones en el blog</h2>
              <p>Las publicaciones del blog aparecerán aquí cuando los usuarios las creen.</p>
            </td>
          </tr>
        <?php else: ?>
          <?php while($post = $res->fetch_assoc()): 
            $fecha = date('d/m/Y H:i', strtotime($post['creado_en']));
            $contenido_preview = mb_substr(strip_tags($post['contenido']), 0, 80) . '...';
          ?>
            <tr>
              <td><span class="pill">#<?php echo (int)$post['id']; ?></span></td>
              <td>
                <?php if (!empty($post['imagen_url'])): ?>
                  <img src="<?php echo htmlspecialchars($post['imagen_url']); ?>" alt="Preview" class="imagen-preview" onerror="this.style.display='none'">
                <?php else: ?>
                  <span style="color:#ccc; font-size:11px;">Sin imagen</span>
                <?php endif; ?>
              </td>
              <td><strong><?php echo htmlspecialchars($post['titulo']); ?></strong></td>
              <td>
                <div><?php echo htmlspecialchars($post['autor_nombre']); ?></div>
                <small style="color:#999; font-size:11px;"><?php echo htmlspecialchars($post['autor_email']); ?></small>
              </td>
              <td>
                <div class="post-preview" title="<?php echo htmlspecialchars($post['contenido']); ?>">
                  <?php echo htmlspecialchars($contenido_preview); ?>
                </div>
              </td>
              <td>
                <span class="stats-badge">❤️ <?php echo (int)$post['likes_count']; ?></span>
                <span class="stats-badge">💬 <?php echo (int)$post['comentarios_count']; ?></span>
              </td>
              <td><small style="color:#666;"><?php echo htmlspecialchars($fecha); ?></small></td>
              <td class="row-actions">
                <a href="../../Blog/ver_post.php?id=<?php echo (int)$post['id']; ?>" target="_blank" class="btn-view">Ver</a>
                <a href="eliminar.php?id=<?php echo (int)$post['id']; ?>" 
                   class="btn-delete" 
                   onclick="return confirm('¿Estás seguro de eliminar esta publicación del blog?\n\nTítulo: <?php echo addslashes($post['titulo']); ?>\n\nEsta acción también eliminará todos los comentarios y likes asociados. Esta acción no se puede deshacer.');">
                  Eliminar
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

