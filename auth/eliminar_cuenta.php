<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (empty($_SESSION['user_id'])) {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php');
  exit;
}

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
  $stmt = $mysqli->prepare('DELETE FROM usuarios WHERE id = ?');
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  session_destroy();
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php?msg=Cuenta+eliminada');
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Eliminar cuenta</title>
  <style>
    body{font-family:Arial, sans-serif; margin:40px; text-align:center}
    .warning{background:#ffebee; border:2px solid #c62828; padding:20px; border-radius:8px; max-width:500px; margin:0 auto}
    button{margin:10px; padding:10px 20px}
    .btn-danger{background:#c62828; color:#fff; border:none; border-radius:6px}
    .btn-cancel{background:#757575; color:#fff; border:none; border-radius:6px; text-decoration:none; display:inline-block}
  </style>
</head>
<body>
  <div class="warning">
    <h2>⚠️ Eliminar cuenta</h2>
    <p>¿Estás seguro? Esta acción no se puede deshacer.</p>
    <form method="post">
      <button type="submit" name="confirmar" class="btn-danger">Sí, eliminar cuenta</button>
      <a href="/ArtazaFinal/Pagina-Prin/PaginaPrin.php" class="btn-cancel">Cancelar</a>
    </form>
  </div>
</body>
</html>

