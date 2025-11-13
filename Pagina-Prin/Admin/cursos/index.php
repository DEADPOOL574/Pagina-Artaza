<?php require_once __DIR__ . '/../../../config/admin_guard.php';
require_once __DIR__ . '/../../../config/db.php';
$res = $mysqli->query("SELECT id, titulo, categoria, nivel, duracion, creado_en FROM cursos ORDER BY creado_en DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>ABM Cursos</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
  <style>
    :root{ --brand:#9b59b6; --brand2:#8e44ad; --ok:#2e7d32 }
    *{box-sizing:border-box}
    body{font-family:Roboto, sans-serif; margin:0; background:#f5f7fa}
    .topbar{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; padding:16px 24px; box-shadow:0 6px 20px rgba(155,89,182,.35)}
    .topbar h1{margin:0; font-family:'Bebas Neue'; font-size:44px; letter-spacing:1px}
    .container{max-width:1100px; margin:24px auto; background:#fff; border-radius:16px; box-shadow:0 10px 28px rgba(0,0,0,.08); overflow:hidden}
    .actionsbar{display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid #eee}
    .btn{padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700; display:inline-block; transition:all 0.2s}
    .btn-primary{background:linear-gradient(135deg,var(--ok),#1b5e20); color:#fff}
    .btn-back{background:linear-gradient(135deg,#607d8b,#455a64); color:#fff}
    .btn:hover{opacity:.9; transform:translateY(-1px); box-shadow:0 4px 8px rgba(0,0,0,.2)}
    .table{width:100%; border-collapse:collapse}
    .table thead th{background:#fafafa; text-align:left; padding:12px; font-size:14px; color:#555}
    .table tbody td{padding:12px; border-top:1px solid #eee}
    .pill{padding:4px 10px; border-radius:999px; font-size:12px; background:#f3e5f5; color:#7b1fa2; border:1px solid #e1bee7}
    .nivel{display:inline-block; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700}
    .nivel.principiante{background:#e3f2fd; color:#1976d2}
    .nivel.intermedio{background:#fff3e0; color:#f57c00}
    .nivel.avanzado{background:#fce4ec; color:#c2185b}
    .row-actions a{margin-right:10px; color:#3498db; text-decoration:none}
    .row-actions a:hover{text-decoration:underline}
  </style>
  </head>
<body>
  <div class="topbar"><h1>Gestión de cursos</h1></div>
  <div class="container">
    <div class="actionsbar">
      <div style="display:flex; gap:12px; align-items:center;">
        <a class="btn btn-back" href="../PaginaAdmin.html">← Volver al panel</a>
        <div>Listado de cursos</div>
      </div>
      <a class="btn btn-primary" href="crear.php">+ Crear curso</a>
    </div>
  <table class="table">
    <thead>
      <tr><th>ID</th><th>Título</th><th>Categoría</th><th>Nivel</th><th>Duración</th><th>Creado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      <?php if ($res->num_rows === 0): ?>
        <tr>
          <td colspan="7" style="text-align:center; padding:40px; color:#999;">
            No hay cursos creados. <a href="crear.php">Crear primer curso</a>
          </td>
        </tr>
      <?php else: ?>
        <?php while($row = $res->fetch_assoc()): ?>
          <tr>
            <td><span class="pill">#<?php echo (int)$row['id']; ?></span></td>
            <td><?php echo htmlspecialchars($row['titulo']); ?></td>
            <td><?php echo htmlspecialchars($row['categoria']); ?></td>
            <td><span class="nivel <?php echo htmlspecialchars($row['nivel']); ?>"><?php echo htmlspecialchars($row['nivel']); ?></span></td>
            <td><?php echo htmlspecialchars($row['duracion'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($row['creado_en']); ?></td>
            <td class="row-actions">
              <a href="editar.php?id=<?php echo (int)$row['id']; ?>">Editar</a>
              <a href="eliminar.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('¿Eliminar este curso?');">Eliminar</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php endif; ?>
    </tbody>
  </table>
  </div>
</body>
</html>

