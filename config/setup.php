<?php
// Instalador completo de base de datos para Respawn News
// Crea la base respawnnews y todas las tablas necesarias

header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'respawnnews';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_errno) {
  http_response_code(500);
  echo 'Error de conexión: ' . $mysqli->connect_error;
  exit;
}
$mysqli->set_charset('utf8mb4');

$errors = [];
$executed = 0;

function runSqlFile(mysqli $mysqli, string $path, array &$errors, int &$executed): void {
  if (!file_exists($path)) {
    $errors[] = 'Archivo no encontrado: ' . htmlspecialchars($path);
    return;
  }
  $sql = file_get_contents($path);
  // Separar por punto y coma fuera de comillas simples
  $statements = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql)));
  foreach ($statements as $stmt) {
    if ($stmt === '' || substr($stmt, 0, 2) === '--') continue;
    if (!$mysqli->query($stmt)) {
      $errors[] = $mysqli->error;
    } else {
      $executed++;
    }
  }
}

// 1) Crear base de datos y tablas base (usuarios, noticias)
runSqlFile($mysqli, __DIR__ . '/db.sql', $errors, $executed);

// 2) Conectar ahora explícitamente a respawnnews por si no estaba
$mysqli->select_db($dbName);

// 3) Tablas adicionales: cursos, blog, CV
runSqlFile($mysqli, __DIR__ . '/tablas_adicionales.sql', $errors, $executed);

// 4) Verificar tablas creadas
$tables = [];
if ($res = $mysqli->query('SHOW TABLES')) {
  while ($row = $res->fetch_array(MYSQLI_NUM)) { $tables[] = $row[0]; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Instalación completa - Respawn News</title>
  <style>
    body{font-family:Arial, sans-serif; margin:40px; background:#f5f7fa}
    .card{background:#fff; border-radius:12px; padding:24px; box-shadow:0 6px 18px rgba(0,0,0,.08); max-width:900px; margin:0 auto}
    h1{color:#f39c12}
    .ok{background:#d4edda; color:#155724; padding:12px; border-radius:8px; margin:12px 0}
    .err{background:#f8d7da; color:#721c24; padding:12px; border-radius:8px; margin:12px 0}
    code{background:#f5f5f5; padding:2px 6px; border-radius:4px}
    ul{margin:8px 0 0 18px}
    a{color:#1976d2; text-decoration:none}
  </style>
</head>
<body>
  <div class="card">
    <h1>Instalación de Base de Datos</h1>
    <p>Base objetivo: <strong><code><?php echo htmlspecialchars($dbName); ?></code></strong></p>
    <p>Sentencias ejecutadas: <strong><?php echo (int)$executed; ?></strong></p>

    <?php if ($errors): ?>
      <div class="err">
        <strong>Errores durante la instalación:</strong>
        <ul>
          <?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php else: ?>
      <div class="ok"><strong>¡Éxito!</strong> Se creó/actualizó la base y tablas correctamente.</div>
    <?php endif; ?>

    <h3>Tablas actuales en la base</h3>
    <ul>
      <?php foreach ($tables as $t): ?><li><?php echo htmlspecialchars($t); ?></li><?php endforeach; ?>
    </ul>

    <p style="margin-top:16px;"><a href="/ArtazaFinal/Pagina-Prin/Perfil/cv.php">Ir al CV</a> · <a href="/ArtazaFinal/Pagina-Prin/Blog/Blog.php">Ir al Blog</a> · <a href="/ArtazaFinal/Pagina-Prin/Cursos/cursos.php">Ver cursos</a></p>
  </div>
</body>
</html>
