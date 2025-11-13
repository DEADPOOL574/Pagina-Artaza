<?php require_once __DIR__ . '/../../../config/admin_guard.php';
require_once __DIR__ . '/../../../config/db.php';
$res = $mysqli->query("SELECT id, titulo, categoria, creado_en FROM noticias ORDER BY creado_en DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>ABM Noticias</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
  <style>
    :root{ --brand:#f39c12; --brand2:#e67e22; --ok:#2e7d32 }
    *{box-sizing:border-box}
    body{font-family:Roboto, sans-serif; margin:0; background:#f5f7fa}
    .topbar{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; padding:16px 24px; box-shadow:0 6px 20px rgba(243,156,18,.35)}
    .topbar h1{margin:0; font-family:'Bebas Neue'; font-size:44px; letter-spacing:1px}
    .container{max-width:1100px; margin:24px auto; background:#fff; border-radius:16px; box-shadow:0 10px 28px rgba(0,0,0,.08); overflow:hidden}
    .actionsbar{display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid #eee}
    .btn{padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700; display:inline-block}
    .btn-primary{background:linear-gradient(135deg,var(--ok),#1b5e20); color:#fff}
    .btn-back{background:linear-gradient(135deg,#607d8b,#455a64); color:#fff}
    .btn:hover{opacity:.9; transform:translateY(-1px); box-shadow:0 4px 8px rgba(0,0,0,.2)}
    .table{width:100%; border-collapse:collapse}
    .table thead th{background:#fafafa; text-align:left; padding:12px; font-size:14px; color:#555}
    .table tbody td{padding:12px; border-top:1px solid #eee}
    .pill{padding:4px 10px; border-radius:999px; font-size:12px; background:#fff3e0; color:#e65100; border:1px solid #ffe0b2}
    .row-actions a{margin-right:10px}
  </style>
  </head>
<body>
  <div class="topbar"><h1>Gestión de noticias</h1></div>
  <div class="container">
    <div class="actionsbar">
      <div style="display:flex; gap:12px; align-items:center;">
        <a class="btn btn-back" href="../PaginaAdmin.php">← Volver al panel</a>
        <div>Listado de noticias</div>
      </div>
      <div style="display:flex; gap:12px; align-items:center;">
        <a class="btn btn-secondary" href="galeria.php" style="background:linear-gradient(135deg,#9c27b0,#7b1fa2); color:#fff;">📷 Galería</a>
        <a class="btn btn-primary" href="crear.php">+ Crear noticia</a>
      </div>
    </div>
  <table class="table">
    <thead>
      <tr><th>ID</th><th>Título</th><th>Categoría</th><th>Creado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><span class="pill">#<?php echo (int)$row['id']; ?></span></td>
          <td><?php echo htmlspecialchars($row['titulo']); ?></td>
          <td><?php echo htmlspecialchars($row['categoria']); ?></td>
          <td><?php echo htmlspecialchars($row['creado_en']); ?></td>
          <td class="row-actions">
            <a href="editar.php?id=<?php echo (int)$row['id']; ?>">Editar</a>
            <a href="eliminar.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('¿Eliminar?');">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  </div>
</body>
</html>


