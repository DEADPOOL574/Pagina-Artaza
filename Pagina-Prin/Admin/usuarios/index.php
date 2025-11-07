<?php require_once __DIR__ . '/../../../config/admin_guard.php';
require_once __DIR__ . '/../../../config/db.php';
$res = $mysqli->query("SELECT id, nombre, email, creado_en FROM usuarios ORDER BY creado_en DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>ABM Usuarios</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
  <style>
    :root{ --brand:#1565c0; --brand2:#0d47a1 }
    *{box-sizing:border-box}
    body{font-family:Roboto, sans-serif; margin:0; background:#f5f7fa}
    .topbar{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; padding:16px 24px; box-shadow:0 6px 20px rgba(21,101,192,.35)}
    .topbar h1{margin:0; font-family:'Bebas Neue'; font-size:44px}
    .container{max-width:1100px; margin:24px auto; background:#fff; border-radius:16px; box-shadow:0 10px 28px rgba(0,0,0,.08); overflow:hidden}
    .actionsbar{display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid #eee}
    .btn{padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700; display:inline-block}
    .btn-primary{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff}
    .btn-back{background:linear-gradient(135deg,#607d8b,#455a64); color:#fff}
    .btn:hover{opacity:.9; transform:translateY(-1px); box-shadow:0 4px 8px rgba(0,0,0,.2)}
    .table{width:100%; border-collapse:collapse}
    .table thead th{background:#fafafa; text-align:left; padding:12px; font-size:14px; color:#555}
    .table tbody td{padding:12px; border-top:1px solid #eee}
    .row-actions a{margin-right:10px}
  </style>
</head>
<body>
  <div class="topbar"><h1>Gestión de usuarios</h1></div>
  <div class="container">
    <div class="actionsbar">
      <div style="display:flex; gap:12px; align-items:center;">
        <a class="btn btn-back" href="../PaginaAdmin.html">← Volver al panel</a>
        <div>Listado de usuarios</div>
      </div>
      <a class="btn btn-primary" href="crear.php">+ Crear usuario</a>
    </div>
  <table class="table">
    <thead>
      <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Creado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td>#<?php echo (int)$row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['nombre']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
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


