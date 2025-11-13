<?php require_once __DIR__ . '/../../../config/admin_guard.php';
require_once __DIR__ . '/../../../config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = trim($_POST['titulo'] ?? '');
  $descripcion = trim($_POST['descripcion'] ?? '');
  $categoria = $_POST['categoria'] ?? 'otros';
  $imagen_url = trim($_POST['imagen_url'] ?? '');
  $duracion = trim($_POST['duracion'] ?? '');
  $nivel = $_POST['nivel'] ?? 'principiante';
  if ($titulo && $descripcion) {
    $stmt = $mysqli->prepare("INSERT INTO cursos (titulo, descripcion, categoria, imagen_url, duracion, nivel) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('ssssss', $titulo, $descripcion, $categoria, $imagen_url, $duracion, $nivel);
    $stmt->execute();
    header('Location: index.php');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear curso</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
  <style>
    :root{ --brand:#9b59b6; --brand2:#8e44ad; --text:#222; --bg:#f5f7fa }
    *{box-sizing:border-box}
    body{font-family:Roboto, sans-serif; margin:0; background:var(--bg)}
    .top{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; padding:18px 24px; box-shadow:0 6px 20px rgba(155,89,182,.35)}
    .top h1{margin:0; font-family:'Bebas Neue', cursive; letter-spacing:1px; font-size:44px; flex:1}
    .container{max-width:900px; margin:24px auto; background:#fff; padding:24px; border-radius:16px; box-shadow:0 10px 28px rgba(0,0,0,.08)}
    .top-header{display:flex; justify-content:space-between; align-items:center; width:100%}
    .top-actions{display:flex; gap:8px; align-items:center}
    label{display:block; margin:10px 0 6px; font-weight:700; color:var(--text)}
    input[type=text], textarea, select{width:100%; padding:12px 14px; border:1px solid #ddd; border-radius:10px; outline:none; transition:.2s}
    input[type=text]:focus, textarea:focus, select:focus{border-color:var(--brand); box-shadow:0 0 0 3px rgba(155,89,182,.15)}
    textarea{min-height:240px; resize:vertical}
    .actions{display:flex; gap:12px; margin-top:18px}
    .btn{padding:12px 18px; border:none; border-radius:10px; cursor:pointer; font-weight:700; text-decoration:none; display:inline-block}
    .btn-primary{background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff}
    .btn-secondary{background:#eee; color:#333}
    .btn-back{background:linear-gradient(135deg,#607d8b,#455a64); color:#fff}
    .btn:hover{opacity:.9; transform:translateY(-1px); box-shadow:0 4px 8px rgba(0,0,0,.2)}
    .hint{font-size:12px; color:#666}
  </style>
</head>
<body>
  <div class="top">
    <div class="top-header">
      <h1>Crear curso</h1>
      <div class="top-actions">
        <a class="btn btn-back" href="../PaginaAdmin.php">← Panel</a>
        <a class="btn btn-secondary" href="index.php">Listado</a>
      </div>
    </div>
  </div>
  <div class="container">
  <form method="post">
    <label>Título<input type="text" name="titulo" required></label>
    <label>Descripción<textarea name="descripcion" rows="8" required></textarea></label>
    <label>Categoría
      <select name="categoria">
        <option value="anime">Anime</option>
        <option value="videojuegos">Videojuegos</option>
        <option value="programacion">Programación</option>
        <option value="otros">Otros</option>
      </select>
    </label>
    <label>Nivel
      <select name="nivel">
        <option value="principiante">Principiante</option>
        <option value="intermedio">Intermedio</option>
        <option value="avanzado">Avanzado</option>
      </select>
    </label>
    <label>Duración<input type="text" name="duracion" placeholder="Ej: 4 semanas, 20 horas"></label>
    <label>URL de imagen (opcional)
      <input type="text" name="imagen_url">
      <span class="hint">Puedes pegar una URL https:// o dejar vacío.</span>
    </label>
    <div class="actions">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a class="btn btn-secondary" href="index.php">Cancelar</a>
    </div>
  </form>
  </div>
</body>
</html>

