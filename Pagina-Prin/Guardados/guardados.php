<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user_id'])) {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php');
  exit;
}

// Por ahora muestra mensaje. Luego puedes crear tabla publicaciones_guardadas.
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publicaciones Guardadas</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body{font-family:Roboto, sans-serif; margin:20px; background:#f5f5f5}
    .container{max-width:800px; margin:0 auto; background:#fff; padding:24px; border-radius:8px; text-align:center}
    h1{color:#333}
    a{color:#1976d2; text-decoration:none}
  </style>
</head>
<body>
  <div class="container">
    <h1>Publicaciones Guardadas</h1>
    <p>No tienes publicaciones guardadas aún.</p>
    <p><a href="../PaginaPrin.php">← Volver al inicio</a></p>
  </div>
</body>
</html>

