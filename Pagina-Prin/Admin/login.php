<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

$error = $_GET['err'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  
  if ($email === '' || $password === '') {
    $error = 'Completa email y contraseña';
  } else {
    $stmt = $mysqli->prepare('SELECT id, nombre, email, password_hash, is_admin FROM usuarios WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if (!$user) {
      $error = 'Usuario no encontrado';
    } elseif (!password_verify($password, $user['password_hash'])) {
      $error = 'Contraseña incorrecta';
    } elseif (!$user['is_admin']) {
      $error = 'Este usuario no es administrador';
    } else {
      // Login OK - es admin
      $_SESSION['is_admin'] = true;
      $_SESSION['admin_id'] = (int)$user['id'];
      $_SESSION['admin_name'] = $user['nombre'];
      header('Location: /ArtazaFinal/Pagina-Prin/Admin/PaginaAdmin.html');
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <style>
    body{font-family:Arial, sans-serif; display:grid; place-items:center; height:100vh; margin:0; background:#f7f7f7}
    .card{background:#fff; padding:24px; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,.1); width:320px}
    h1{margin:0 0 12px 0; font-size:22px}
    input{width:100%; padding:10px; margin-top:8px}
    button{margin-top:12px; padding:10px 12px; width:100%}
    .err{color:#c62828; margin-top:8px}
  </style>
</head>
<body>
  <div class="card">
    <h1>Acceso administrador</h1>
    <form method="post">
      <label>Email de administrador
        <input type="email" name="email" placeholder="admin@respawnnews.com" required>
      </label>
      <label>Contraseña
        <input type="password" name="password" placeholder="admin123" required>
      </label>
      <button type="submit">Entrar</button>
      <?php if ($error): ?><div class="err"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    </form>
    <p style="margin-top:16px; font-size:12px; color:#666;">
      ¿No tienes cuenta admin? <a href="../../config/agregar_admin.php">Crear/Configurar admin</a>
    </p>
  </div>
</body>
</html>


