<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user_id'])) {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php');
  exit;
}

// Por ahora muestra noticias recientes. Luego puedes agregar tabla de actividad.
$res = $mysqli->query("SELECT id, titulo, categoria, creado_en FROM noticias ORDER BY creado_en DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actividad Reciente</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body{font-family:Roboto, sans-serif; margin:20px; background:#f5f5f5}
    .container{max-width:800px; margin:0 auto; background:#fff; padding:24px; border-radius:8px}
    h1{color:#333}
    .actividad-item{padding:12px; border-bottom:1px solid #eee}
    .actividad-item:last-child{border-bottom:none}
    a{color:#1976d2; text-decoration:none}
  </style>
</head>
<body>
  <div class="container">
    <h1>Actividad Reciente</h1>
    <p>Últimas noticias publicadas:</p>
    <?php while($row = $res->fetch_assoc()): ?>
      <div class="actividad-item">
        <strong><?php echo htmlspecialchars($row['titulo']); ?></strong>
        <br>
        <small><?php echo htmlspecialchars($row['categoria']); ?> - <?php echo htmlspecialchars($row['creado_en']); ?></small>
      </div>
    <?php endwhile; ?>
    <p><a href="../PaginaPrin.php">← Volver al inicio</a></p>
  </div>
</body>
</html>

