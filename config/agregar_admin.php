<?php
// Script para agregar campo is_admin y crear/convertir usuario admin
require_once __DIR__ . '/db.php';

// Agregar columna is_admin si no existe
$check_col = $mysqli->query("SHOW COLUMNS FROM usuarios LIKE 'is_admin'");
if ($check_col->num_rows == 0) {
  $mysqli->query("ALTER TABLE usuarios ADD COLUMN is_admin TINYINT(1) DEFAULT 0");
}

// Crear usuario admin por defecto (o convertir uno existente)
$admin_email = 'admin@respawnnews.com';
$admin_password = 'admin123';
$admin_nombre = 'Administrador';

// Verificar si ya existe
$check = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
$check->bind_param('s', $admin_email);
$check->execute();
$exists = $check->get_result()->fetch_assoc();

if ($exists) {
  // Convertir a admin
  $hash = password_hash($admin_password, PASSWORD_BCRYPT);
  $upd = $mysqli->prepare("UPDATE usuarios SET is_admin = 1, password_hash = ? WHERE email = ?");
  $upd->bind_param('ss', $hash, $admin_email);
  $upd->execute();
  $msg = "Usuario '$admin_email' convertido a administrador";
} else {
  // Crear nuevo admin
  $hash = password_hash($admin_password, PASSWORD_BCRYPT);
  $ins = $mysqli->prepare("INSERT INTO usuarios (nombre, email, password_hash, is_admin) VALUES (?, ?, ?, 1)");
  $ins->bind_param('sss', $admin_nombre, $admin_email, $hash);
  $ins->execute();
  $msg = "Usuario administrador creado";
}

header('Content-Type: text/html; charset=utf-8');
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configurar Admin</title>
  <style>
    body{font-family:Arial, sans-serif; margin:40px; background:#f5f5f5}
    .card{background:#fff; padding:24px; border-radius:8px; max-width:600px; margin:0 auto; box-shadow:0 2px 8px rgba(0,0,0,0.1)}
    .success{background:#e8f5e9; color:#2e7d32; padding:12px; border-radius:6px; margin:12px 0}
    .info{background:#e3f2fd; color:#1976d2; padding:12px; border-radius:6px; margin:12px 0}
    code{background:#f5f5f5; padding:2px 6px; border-radius:4px}
  </style>
</head>
<body>
  <div class="card">
    <h1>✅ Configuración de Administrador</h1>
    <div class="success">
      <strong><?php echo htmlspecialchars($msg); ?></strong>
    </div>
    <div class="info">
      <h3>Credenciales de acceso:</h3>
      <p><strong>Email:</strong> <code><?php echo htmlspecialchars($admin_email); ?></code></p>
      <p><strong>Contraseña:</strong> <code><?php echo htmlspecialchars($admin_password); ?></code></p>
    </div>
    <p><a href="../Pagina-Prin/Admin/login.php">→ Ir al login de administrador</a></p>
    <p><small>Nota: Cambia la contraseña después del primer acceso por seguridad.</small></p>
  </div>
</body>
</html>

