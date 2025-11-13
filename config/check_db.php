<?php
// Diagnóstico de base de datos
require_once __DIR__ . '/db.php';

header('Content-Type: text/html; charset=utf-8');

$dbName = $mysqli->query('SELECT DATABASE() as db')->fetch_assoc()['db'] ?? '(desconocida)';
$tables = [];
$res = $mysqli->query('SHOW TABLES');
if ($res) {
  while ($row = $res->fetch_array(MYSQLI_NUM)) {
    $tables[] = $row[0];
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Diagnóstico DB</title>
  <style>
    body{font-family:Arial, sans-serif; margin:40px}
    .ok{color:#2e7d32}
    .warn{color:#e65100}
    .err{color:#c62828}
    code{background:#f5f5f5; padding:2px 6px; border-radius:4px}
  </style>
</head>
<body>
  <h1>Diagnóstico de Base de Datos</h1>
  <p>Conectado a la base: <strong><code><?php echo htmlspecialchars($dbName); ?></code></strong></p>
  <h2>Tablas encontradas (<?php echo count($tables); ?>)</h2>
  <ul>
    <?php foreach ($tables as $t): ?>
      <li><?php echo htmlspecialchars($t); ?></li>
    <?php endforeach; ?>
  </ul>
  <p>Si no ves las tablas esperadas, asegurate de que <code>config/db.php</code> use <strong>respawnnews</strong> y ejecutá:
    <br><code>/ArtazaFinal/config/instalar_tablas_adicionales.php</code> y luego importá <code>/ArtazaFinal/config/db.sql</code> si hace falta.
  </p>
</body>
</html>
