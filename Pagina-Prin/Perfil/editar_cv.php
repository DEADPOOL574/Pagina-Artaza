<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// Asegurar tablas de CV (autocreación si faltan)
$mysqli->query("CREATE TABLE IF NOT EXISTS usuario_cv (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL UNIQUE,
  telefono VARCHAR(20) NULL,
  direccion TEXT NULL,
  fecha_nacimiento DATE NULL,
  profesion VARCHAR(100) NULL,
  biografia TEXT NULL,
  linkedin VARCHAR(200) NULL,
  github VARCHAR(200) NULL,
  portfolio VARCHAR(200) NULL,
  actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$mysqli->query("CREATE TABLE IF NOT EXISTS cv_experiencia (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  empresa VARCHAR(200) NOT NULL,
  puesto VARCHAR(200) NOT NULL,
  descripcion TEXT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NULL,
  actualmente BOOLEAN DEFAULT FALSE,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$mysqli->query("CREATE TABLE IF NOT EXISTS cv_educacion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  institucion VARCHAR(200) NOT NULL,
  titulo VARCHAR(200) NOT NULL,
  descripcion TEXT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NULL,
  actualmente BOOLEAN DEFAULT FALSE,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$mysqli->query("CREATE TABLE IF NOT EXISTS cv_habilidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  habilidad VARCHAR(100) NOT NULL,
  nivel ENUM('basico','intermedio','avanzado','experto') DEFAULT 'basico',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

if (empty($_SESSION['user_id'])) {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php');
  exit;
}

$user_id = (int)$_SESSION['user_id'];
$mensaje = '';
$tipo_mensaje = '';

// Procesar formulario de datos personales
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo'])) {
  if ($_POST['tipo'] === 'datos_personales') {
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $profesion = trim($_POST['profesion'] ?? '');
    $biografia = trim($_POST['biografia'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $github = trim($_POST['github'] ?? '');
    $portfolio = trim($_POST['portfolio'] ?? '');
    
    // Verificar si existe registro
    $stmt = $mysqli->prepare('SELECT id FROM usuario_cv WHERE usuario_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $exists = $stmt->get_result()->fetch_assoc();
    
    if ($exists) {
      $stmt = $mysqli->prepare('UPDATE usuario_cv SET telefono=?, direccion=?, fecha_nacimiento=?, profesion=?, biografia=?, linkedin=?, github=?, portfolio=? WHERE usuario_id=?');
      $stmt->bind_param('ssssssssi', $telefono, $direccion, $fecha_nacimiento, $profesion, $biografia, $linkedin, $github, $portfolio, $user_id);
    } else {
      $stmt = $mysqli->prepare('INSERT INTO usuario_cv (usuario_id, telefono, direccion, fecha_nacimiento, profesion, biografia, linkedin, github, portfolio) VALUES (?,?,?,?,?,?,?,?,?)');
      $stmt->bind_param('issssssss', $user_id, $telefono, $direccion, $fecha_nacimiento, $profesion, $biografia, $linkedin, $github, $portfolio);
    }
    if ($stmt->execute()) {
      $mensaje = 'Datos personales actualizados correctamente';
      $tipo_mensaje = 'exito';
    }
  } elseif ($_POST['tipo'] === 'experiencia') {
    $empresa = trim($_POST['empresa'] ?? '');
    $puesto = trim($_POST['puesto'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $actualmente = isset($_POST['actualmente']) ? 1 : 0;
    
    if ($empresa && $puesto && $fecha_inicio) {
      $stmt = $mysqli->prepare('INSERT INTO cv_experiencia (usuario_id, empresa, puesto, descripcion, fecha_inicio, fecha_fin, actualmente) VALUES (?,?,?,?,?,?,?)');
      $stmt->bind_param('isssssi', $user_id, $empresa, $puesto, $descripcion, $fecha_inicio, $fecha_fin, $actualmente);
      if ($stmt->execute()) {
        $mensaje = 'Experiencia agregada correctamente';
        $tipo_mensaje = 'exito';
      }
    }
  } elseif ($_POST['tipo'] === 'educacion') {
    $institucion = trim($_POST['institucion'] ?? '');
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $actualmente = isset($_POST['actualmente']) ? 1 : 0;
    
    if ($institucion && $titulo && $fecha_inicio) {
      $stmt = $mysqli->prepare('INSERT INTO cv_educacion (usuario_id, institucion, titulo, descripcion, fecha_inicio, fecha_fin, actualmente) VALUES (?,?,?,?,?,?,?)');
      $stmt->bind_param('isssssi', $user_id, $institucion, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $actualmente);
      if ($stmt->execute()) {
        $mensaje = 'Educación agregada correctamente';
        $tipo_mensaje = 'exito';
      }
    }
  } elseif ($_POST['tipo'] === 'habilidad') {
    $habilidad = trim($_POST['habilidad'] ?? '');
    $nivel = $_POST['nivel'] ?? 'basico';
    
    if ($habilidad) {
      $stmt = $mysqli->prepare('INSERT INTO cv_habilidades (usuario_id, habilidad, nivel) VALUES (?,?,?)');
      $stmt->bind_param('iss', $user_id, $habilidad, $nivel);
      if ($stmt->execute()) {
        $mensaje = 'Habilidad agregada correctamente';
        $tipo_mensaje = 'exito';
      }
    }
  } elseif ($_POST['tipo'] === 'eliminar_exp') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $mysqli->prepare('DELETE FROM cv_experiencia WHERE id = ? AND usuario_id = ?');
    $stmt->bind_param('ii', $id, $user_id);
    $stmt->execute();
    $mensaje = 'Experiencia eliminada';
    $tipo_mensaje = 'exito';
  } elseif ($_POST['tipo'] === 'eliminar_edu') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $mysqli->prepare('DELETE FROM cv_educacion WHERE id = ? AND usuario_id = ?');
    $stmt->bind_param('ii', $id, $user_id);
    $stmt->execute();
    $mensaje = 'Educación eliminada';
    $tipo_mensaje = 'exito';
  } elseif ($_POST['tipo'] === 'eliminar_hab') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $mysqli->prepare('DELETE FROM cv_habilidades WHERE id = ? AND usuario_id = ?');
    $stmt->bind_param('ii', $id, $user_id);
    $stmt->execute();
    $mensaje = 'Habilidad eliminada';
    $tipo_mensaje = 'exito';
  }
}

// Obtener datos actuales
$stmt = $mysqli->prepare('SELECT * FROM usuario_cv WHERE usuario_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$cv = $stmt->get_result()->fetch_assoc();

// Obtener experiencias
$stmt = $mysqli->prepare('SELECT * FROM cv_experiencia WHERE usuario_id = ? ORDER BY fecha_inicio DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$experiencias = $stmt->get_result();

// Obtener educaciones
$stmt = $mysqli->prepare('SELECT * FROM cv_educacion WHERE usuario_id = ? ORDER BY fecha_inicio DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$educaciones = $stmt->get_result();

// Obtener habilidades
$stmt = $mysqli->prepare('SELECT * FROM cv_habilidades WHERE usuario_id = ? ORDER BY nivel DESC, habilidad ASC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$habilidades = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar CV - Respawn News</title>
  <link rel="stylesheet" href="Perfil.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
  <header class="perfil-header">
    <div class="header-content">
      <h1>Editar CV</h1>
    </div>
  </header>
  
  <div class="container">
    <?php if ($mensaje): ?>
      <div class="mensaje <?php echo $tipo_mensaje; ?>" style="margin-bottom:24px;">
        <?php echo htmlspecialchars($mensaje); ?>
      </div>
    <?php endif; ?>

    <!-- Datos Personales -->
    <section class="edit-section">
      <div class="section-header">
        <span class="icon">👤</span>
        <h2>Datos Personales</h2>
      </div>
      <form method="post" class="edit-form">
        <input type="hidden" name="tipo" value="datos_personales">
        <div class="form-group">
          <label for="telefono">Teléfono</label>
          <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($cv['telefono'] ?? ''); ?>" class="form-input">
        </div>
        <div class="form-group">
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($cv['direccion'] ?? ''); ?>" class="form-input">
        </div>
        <div class="form-group">
          <label for="fecha_nacimiento">Fecha de Nacimiento</label>
          <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $cv['fecha_nacimiento'] ?? ''; ?>" class="form-input">
        </div>
        <div class="form-group">
          <label for="profesion">Profesión</label>
          <input type="text" id="profesion" name="profesion" value="<?php echo htmlspecialchars($cv['profesion'] ?? ''); ?>" class="form-input">
        </div>
        <div class="form-group">
          <label for="biografia">Biografía</label>
          <textarea id="biografia" name="biografia" rows="4" class="form-input"><?php echo htmlspecialchars($cv['biografia'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
          <label for="linkedin">LinkedIn</label>
          <input type="url" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($cv['linkedin'] ?? ''); ?>" class="form-input" placeholder="https://linkedin.com/in/tu-perfil">
        </div>
        <div class="form-group">
          <label for="github">GitHub</label>
          <input type="url" id="github" name="github" value="<?php echo htmlspecialchars($cv['github'] ?? ''); ?>" class="form-input" placeholder="https://github.com/tu-usuario">
        </div>
        <div class="form-group">
          <label for="portfolio">Portfolio</label>
          <input type="url" id="portfolio" name="portfolio" value="<?php echo htmlspecialchars($cv['portfolio'] ?? ''); ?>" class="form-input" placeholder="https://tu-portfolio.com">
        </div>
        <div class="form-buttons">
          <button type="submit" class="btn btn-success"><span>💾 Guardar</span></button>
        </div>
      </form>
    </section>

    <!-- Experiencia -->
    <section class="edit-section">
      <div class="section-header">
        <span class="icon">💼</span>
        <h2>Experiencia Laboral</h2>
      </div>
      <?php if ($experiencias->num_rows > 0): ?>
        <?php while($exp = $experiencias->fetch_assoc()): ?>
          <div style="background:#f8f9fa; padding:16px; border-radius:8px; margin-bottom:12px;">
            <strong><?php echo htmlspecialchars($exp['puesto']); ?> - <?php echo htmlspecialchars($exp['empresa']); ?></strong>
            <form method="post" style="display:inline; margin-left:12px;">
              <input type="hidden" name="tipo" value="eliminar_exp">
              <input type="hidden" name="id" value="<?php echo (int)$exp['id']; ?>">
              <button type="submit" onclick="return confirm('¿Eliminar esta experiencia?');" style="background:#e74c3c; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:12px;">Eliminar</button>
            </form>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
      <form method="post" class="edit-form">
        <input type="hidden" name="tipo" value="experiencia">
        <div class="form-group">
          <label for="empresa">Empresa</label>
          <input type="text" id="empresa" name="empresa" required class="form-input">
        </div>
        <div class="form-group">
          <label for="puesto">Puesto</label>
          <input type="text" id="puesto" name="puesto" required class="form-input">
        </div>
        <div class="form-group">
          <label for="descripcion_exp">Descripción</label>
          <textarea id="descripcion_exp" name="descripcion" rows="3" class="form-input"></textarea>
        </div>
        <div class="form-group">
          <label for="fecha_inicio_exp">Fecha de Inicio</label>
          <input type="date" id="fecha_inicio_exp" name="fecha_inicio" required class="form-input">
        </div>
        <div class="form-group">
          <label>
            <input type="checkbox" id="actualmente_exp" name="actualmente" onchange="document.getElementById('fecha_fin_exp').disabled=this.checked">
            Actualmente trabajo aquí
          </label>
        </div>
        <div class="form-group">
          <label for="fecha_fin_exp">Fecha de Fin</label>
          <input type="date" id="fecha_fin_exp" name="fecha_fin" class="form-input">
        </div>
        <div class="form-buttons">
          <button type="submit" class="btn btn-success"><span>➕ Agregar</span></button>
        </div>
      </form>
    </section>

    <!-- Educación -->
    <section class="edit-section">
      <div class="section-header">
        <span class="icon">🎓</span>
        <h2>Educación</h2>
      </div>
      <?php if ($educaciones->num_rows > 0): ?>
        <?php while($edu = $educaciones->fetch_assoc()): ?>
          <div style="background:#f8f9fa; padding:16px; border-radius:8px; margin-bottom:12px;">
            <strong><?php echo htmlspecialchars($edu['titulo']); ?> - <?php echo htmlspecialchars($edu['institucion']); ?></strong>
            <form method="post" style="display:inline; margin-left:12px;">
              <input type="hidden" name="tipo" value="eliminar_edu">
              <input type="hidden" name="id" value="<?php echo (int)$edu['id']; ?>">
              <button type="submit" onclick="return confirm('¿Eliminar esta educación?');" style="background:#e74c3c; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:12px;">Eliminar</button>
            </form>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
      <form method="post" class="edit-form">
        <input type="hidden" name="tipo" value="educacion">
        <div class="form-group">
          <label for="institucion">Institución</label>
          <input type="text" id="institucion" name="institucion" required class="form-input">
        </div>
        <div class="form-group">
          <label for="titulo_edu">Título</label>
          <input type="text" id="titulo_edu" name="titulo" required class="form-input">
        </div>
        <div class="form-group">
          <label for="descripcion_edu">Descripción</label>
          <textarea id="descripcion_edu" name="descripcion" rows="3" class="form-input"></textarea>
        </div>
        <div class="form-group">
          <label for="fecha_inicio_edu">Fecha de Inicio</label>
          <input type="date" id="fecha_inicio_edu" name="fecha_inicio" required class="form-input">
        </div>
        <div class="form-group">
          <label>
            <input type="checkbox" id="actualmente_edu" name="actualmente" onchange="document.getElementById('fecha_fin_edu').disabled=this.checked">
            Actualmente estudio aquí
          </label>
        </div>
        <div class="form-group">
          <label for="fecha_fin_edu">Fecha de Fin</label>
          <input type="date" id="fecha_fin_edu" name="fecha_fin" class="form-input">
        </div>
        <div class="form-buttons">
          <button type="submit" class="btn btn-success"><span>➕ Agregar</span></button>
        </div>
      </form>
    </section>

    <!-- Habilidades -->
    <section class="edit-section">
      <div class="section-header">
        <span class="icon">⚡</span>
        <h2>Habilidades</h2>
      </div>
      <?php if ($habilidades->num_rows > 0): ?>
        <?php while($hab = $habilidades->fetch_assoc()): ?>
          <div style="background:#f8f9fa; padding:16px; border-radius:8px; margin-bottom:12px; display:flex; justify-content:space-between; align-items:center;">
            <div>
              <strong><?php echo htmlspecialchars($hab['habilidad']); ?></strong>
              <span class="nivel <?php echo htmlspecialchars($hab['nivel']); ?>" style="margin-left:12px;"><?php echo htmlspecialchars($hab['nivel']); ?></span>
            </div>
            <form method="post" style="display:inline;">
              <input type="hidden" name="tipo" value="eliminar_hab">
              <input type="hidden" name="id" value="<?php echo (int)$hab['id']; ?>">
              <button type="submit" onclick="return confirm('¿Eliminar esta habilidad?');" style="background:#e74c3c; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:12px;">Eliminar</button>
            </form>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
      <form method="post" class="edit-form">
        <input type="hidden" name="tipo" value="habilidad">
        <div class="form-group">
          <label for="habilidad">Habilidad</label>
          <input type="text" id="habilidad" name="habilidad" required class="form-input" placeholder="Ej: PHP, JavaScript, Diseño UX">
        </div>
        <div class="form-group">
          <label for="nivel_hab">Nivel</label>
          <select id="nivel_hab" name="nivel" class="form-input">
            <option value="basico">Básico</option>
            <option value="intermedio">Intermedio</option>
            <option value="avanzado">Avanzado</option>
            <option value="experto">Experto</option>
          </select>
        </div>
        <div class="form-buttons">
          <button type="submit" class="btn btn-success"><span>➕ Agregar</span></button>
        </div>
      </form>
    </section>

    <div style="text-align:center; margin-top:30px;">
      <a href="cv.php" class="btn btn-primary"><span>👁️ Ver CV</span></a>
      <a href="perfil.php" class="btn btn-secondary" style="margin-left:12px;"><span>← Volver al perfil</span></a>
    </div>
  </div>
</body>
</html>

