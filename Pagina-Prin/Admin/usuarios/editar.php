<?php require_once __DIR__ . '/../../../config/admin_guard.php';
require_once __DIR__ . '/../../../config/db.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT nombre, email FROM usuarios WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) { http_response_code(404); echo 'Usuario no encontrado'; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if ($nombre && $email) {
    if ($password) {
      $hash = password_hash($password, PASSWORD_BCRYPT);
      $up = $mysqli->prepare("UPDATE usuarios SET nombre=?, email=?, password_hash=? WHERE id=?");
      $up->bind_param('sssi', $nombre, $email, $hash, $id);
    } else {
      $up = $mysqli->prepare("UPDATE usuarios SET nombre=?, email=? WHERE id=?");
      $up->bind_param('ssi', $nombre, $email, $id);
    }
    $up->execute();
    header('Location: index.php');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar usuario</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
  <style>
    :root{ --brand:#1565c0; --brand2:#0d47a1 }
    *{box-sizing:border-box}
    body{font-family:Roboto, sans-serif; margin:0; background:#f5f7fa}
    .top{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; padding:18px 24px; box-shadow:0 6px 20px rgba(21,101,192,.35)}
    .top h1{margin:0; font-family:'Bebas Neue'; font-size:44px; flex:1}
    .container{max-width:700px; margin:24px auto; background:#fff; padding:24px; border-radius:16px; box-shadow:0 10px 28px rgba(0,0,0,.08)}
    label{display:block; margin:10px 0 6px; font-weight:700}
    input[type=text], input[type=email], input[type=password]{width:100%; padding:12px 14px; border:1px solid #ddd; border-radius:10px; outline:none}
    input:focus{border-color:var(--brand); box-shadow:0 0 0 3px rgba(21,101,192,.15)}
    .actions{display:flex; gap:12px; margin-top:18px}
    .btn{padding:12px 18px; border:none; border-radius:10px; cursor:pointer; font-weight:700}
    .btn-primary{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff}
    .btn-secondary{background:#eee; color:#333; text-decoration:none; display:inline-block}
    .btn-back{background:linear-gradient(135deg,#607d8b,#455a64); color:#fff; text-decoration:none; display:inline-block}
    .btn:hover{opacity:.9; transform:translateY(-1px); box-shadow:0 4px 8px rgba(0,0,0,.2)}
    .top-header{display:flex; justify-content:space-between; align-items:center; width:100%}
    .top-actions{display:flex; gap:8px; align-items:center}
  </style>
</head>
<body>
  <div class="top">
    <div class="top-header">
      <h1>Editar usuario</h1>
      <div class="top-actions">
        <a class="btn btn-back" href="../PaginaAdmin.html">← Panel</a>
        <a class="btn btn-secondary" href="index.php">Listado</a>
      </div>
    </div>
  </div>
  <div class="container">
  <form method="post">
    <label>Nombre<input type="text" name="nombre" required value="<?= htmlspecialchars($user['nombre']) ?>"></label>
    <label>Email<input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>"></label>
    <label>Nueva contraseña (opcional)<input type="password" name="password" placeholder="Dejar vacío para no cambiar"></label>
    <div class="actions">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a class="btn btn-secondary" href="index.php">Cancelar</a>
    </div>
  </form>
  </div>
</body>
</html>


