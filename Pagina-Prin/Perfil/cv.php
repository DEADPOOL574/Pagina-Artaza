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

// Obtener información del CV
$stmt = $mysqli->prepare('SELECT * FROM usuario_cv WHERE usuario_id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$cv = $stmt->get_result()->fetch_assoc();

// Obtener experiencia
$stmt = $mysqli->prepare('SELECT * FROM cv_experiencia WHERE usuario_id = ? ORDER BY fecha_inicio DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$experiencias = $stmt->get_result();

// Obtener educación
$stmt = $mysqli->prepare('SELECT * FROM cv_educacion WHERE usuario_id = ? ORDER BY fecha_inicio DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$educaciones = $stmt->get_result();

// Obtener habilidades
$stmt = $mysqli->prepare('SELECT * FROM cv_habilidades WHERE usuario_id = ? ORDER BY nivel DESC, habilidad ASC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$habilidades = $stmt->get_result();

// Obtener información básica del usuario
$stmt = $mysqli->prepare('SELECT nombre, email FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi CV - Respawn News</title>
  <link rel="stylesheet" href="Perfil.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    .cv-container { max-width: 900px; margin: 0 auto; padding: 40px 20px; }
    .cv-header { background: linear-gradient(135deg, #3498db, #2980b9); padding: 40px; border-radius: 16px; color: #fff; margin-bottom: 30px; box-shadow: 0 8px 24px rgba(52,152,219,.3); }
    .cv-header h1 { margin: 0 0 10px 0; font-size: 48px; font-family: 'Bebas Neue', sans-serif; }
    .cv-header p { margin: 5px 0; opacity: 0.9; }
    .cv-section { background: #fff; padding: 30px; border-radius: 16px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,.1); }
    .cv-section h2 { margin: 0 0 20px 0; font-size: 28px; color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; font-family: 'Bebas Neue', sans-serif; }
    .cv-item { margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #eee; }
    .cv-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .cv-item h3 { margin: 0 0 8px 0; font-size: 20px; color: #2c3e50; }
    .cv-item .meta { color: #7f8c8d; font-size: 14px; margin-bottom: 8px; }
    .cv-item p { color: #555; line-height: 1.6; margin: 8px 0 0 0; }
    .habilidades-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
    .habilidad-item { padding: 12px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #3498db; }
    .habilidad-item .nivel { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; margin-left: 8px; }
    .nivel.basico { background: #e3f2fd; color: #1976d2; }
    .nivel.intermedio { background: #fff3e0; color: #f57c00; }
    .nivel.avanzado { background: #fce4ec; color: #c2185b; }
    .nivel.experto { background: #e8f5e9; color: #388e3c; }
    .btn-cv { display: inline-block; margin-top: 20px; padding: 12px 24px; background: linear-gradient(135deg, #3498db, #2980b9); color: #fff; text-decoration: none; border-radius: 8px; font-weight: 700; }
    .btn-cv:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(52,152,219,.3); }
    .empty-cv { text-align: center; padding: 40px; color: #999; }
  </style>
</head>
<body>
  <header class="perfil-header">
    <div class="header-content">
      <h1>Mi CV</h1>
    </div>
  </header>
  
  <div class="cv-container">
    <?php if (!$cv && $experiencias->num_rows === 0 && $educaciones->num_rows === 0 && $habilidades->num_rows === 0): ?>
      <div class="empty-cv">
        <h2>Tu CV está vacío</h2>
        <p>Completa tu información para crear un CV profesional.</p>
        <a href="editar_cv.php" class="btn-cv">+ Completar CV</a>
      </div>
    <?php else: ?>
      <div class="cv-header">
        <h1><?php echo htmlspecialchars($user['nombre']); ?></h1>
        <p>📧 <?php echo htmlspecialchars($user['email']); ?></p>
        <?php if ($cv): ?>
          <?php if (!empty($cv['telefono'])): ?>
            <p>📱 <?php echo htmlspecialchars($cv['telefono']); ?></p>
          <?php endif; ?>
          <?php if (!empty($cv['profesion'])): ?>
            <p>💼 <?php echo htmlspecialchars($cv['profesion']); ?></p>
          <?php endif; ?>
          <?php if (!empty($cv['linkedin'])): ?>
            <p><a href="<?php echo htmlspecialchars($cv['linkedin']); ?>" target="_blank" style="color:#fff;">🔗 LinkedIn</a></p>
          <?php endif; ?>
          <?php if (!empty($cv['github'])): ?>
            <p><a href="<?php echo htmlspecialchars($cv['github']); ?>" target="_blank" style="color:#fff;">💻 GitHub</a></p>
          <?php endif; ?>
        <?php endif; ?>
        <a href="editar_cv.php" class="btn-cv" style="margin-top:20px;">✏️ Editar CV</a>
      </div>

      <?php if ($cv && !empty($cv['biografia'])): ?>
        <div class="cv-section">
          <h2>📝 Biografía</h2>
          <p style="line-height:1.8; color:#555;"><?php echo nl2br(htmlspecialchars($cv['biografia'])); ?></p>
        </div>
      <?php endif; ?>

      <?php if ($experiencias->num_rows > 0): ?>
        <div class="cv-section">
          <h2>💼 Experiencia Laboral</h2>
          <?php while($exp = $experiencias->fetch_assoc()): ?>
            <div class="cv-item">
              <h3><?php echo htmlspecialchars($exp['puesto']); ?> - <?php echo htmlspecialchars($exp['empresa']); ?></h3>
              <div class="meta">
                <?php echo date('M Y', strtotime($exp['fecha_inicio'])); ?> - 
                <?php echo $exp['actualmente'] ? 'Actual' : ($exp['fecha_fin'] ? date('M Y', strtotime($exp['fecha_fin'])) : 'Presente'); ?>
              </div>
              <?php if (!empty($exp['descripcion'])): ?>
                <p><?php echo nl2br(htmlspecialchars($exp['descripcion'])); ?></p>
              <?php endif; ?>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>

      <?php if ($educaciones->num_rows > 0): ?>
        <div class="cv-section">
          <h2>🎓 Educación</h2>
          <?php while($edu = $educaciones->fetch_assoc()): ?>
            <div class="cv-item">
              <h3><?php echo htmlspecialchars($edu['titulo']); ?> - <?php echo htmlspecialchars($edu['institucion']); ?></h3>
              <div class="meta">
                <?php echo date('Y', strtotime($edu['fecha_inicio'])); ?> - 
                <?php echo $edu['actualmente'] ? 'Actual' : ($edu['fecha_fin'] ? date('Y', strtotime($edu['fecha_fin'])) : 'Presente'); ?>
              </div>
              <?php if (!empty($edu['descripcion'])): ?>
                <p><?php echo nl2br(htmlspecialchars($edu['descripcion'])); ?></p>
              <?php endif; ?>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>

      <?php if ($habilidades->num_rows > 0): ?>
        <div class="cv-section">
          <h2>⚡ Habilidades</h2>
          <div class="habilidades-grid">
            <?php while($hab = $habilidades->fetch_assoc()): ?>
              <div class="habilidad-item">
                <strong><?php echo htmlspecialchars($hab['habilidad']); ?></strong>
                <span class="nivel <?php echo htmlspecialchars($hab['nivel']); ?>"><?php echo htmlspecialchars($hab['nivel']); ?></span>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      <?php endif; ?>

      <div style="text-align:center; margin-top:30px;">
        <a href="editar_cv.php" class="btn-cv">✏️ Editar CV</a>
        <a href="perfil.php" class="btn-cv" style="background:linear-gradient(135deg,#95a5a6,#7f8c8d); margin-left:12px;">← Volver al perfil</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

