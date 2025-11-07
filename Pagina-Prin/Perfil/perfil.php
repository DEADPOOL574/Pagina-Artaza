<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user_id'])) {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php');
  exit;
}

$mensaje = '';
$tipo_mensaje = '';

// Procesar actualización si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nuevo_nombre = trim($_POST['nombre'] ?? '');
  $password_actual = $_POST['password_actual'] ?? '';
  $nueva_password = $_POST['nueva_password'] ?? '';
  $confirmar_password = $_POST['confirmar_password'] ?? '';
  
  if (empty($nuevo_nombre)) {
    $mensaje = 'El nombre no puede estar vacío';
    $tipo_mensaje = 'error';
  } elseif (!empty($nueva_password)) {
    // Si se quiere cambiar la contraseña, validar la actual
    if (empty($password_actual)) {
      $mensaje = 'Debes ingresar tu contraseña actual para cambiarla';
      $tipo_mensaje = 'error';
    } else {
      // Verificar contraseña actual
      $stmt = $mysqli->prepare('SELECT password_hash FROM usuarios WHERE id = ?');
      $stmt->bind_param('i', $_SESSION['user_id']);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_assoc();
      
      if (!$result || !password_verify($password_actual, $result['password_hash'])) {
        $mensaje = 'La contraseña actual es incorrecta';
        $tipo_mensaje = 'error';
      } elseif ($nueva_password !== $confirmar_password) {
        $mensaje = 'Las contraseñas nuevas no coinciden';
        $tipo_mensaje = 'error';
      } elseif (strlen($nueva_password) < 6) {
        $mensaje = 'La nueva contraseña debe tener al menos 6 caracteres';
        $tipo_mensaje = 'error';
      } else {
        // Actualizar nombre y contraseña
        $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('UPDATE usuarios SET nombre = ?, password_hash = ? WHERE id = ?');
        $stmt->bind_param('ssi', $nuevo_nombre, $password_hash, $_SESSION['user_id']);
        if ($stmt->execute()) {
          $_SESSION['user_name'] = $nuevo_nombre;
          $mensaje = 'Nombre y contraseña actualizados correctamente';
          $tipo_mensaje = 'exito';
        } else {
          $mensaje = 'Error al actualizar: ' . $mysqli->error;
          $tipo_mensaje = 'error';
        }
      }
    }
  } else {
    // Solo actualizar nombre
    $stmt = $mysqli->prepare('UPDATE usuarios SET nombre = ? WHERE id = ?');
    $stmt->bind_param('si', $nuevo_nombre, $_SESSION['user_id']);
    if ($stmt->execute()) {
      $_SESSION['user_name'] = $nuevo_nombre;
      $mensaje = 'Nombre actualizado correctamente';
      $tipo_mensaje = 'exito';
    } else {
      $mensaje = 'Error al actualizar: ' . $mysqli->error;
      $tipo_mensaje = 'error';
    }
  }
}

$stmt = $mysqli->prepare('SELECT id, nombre, email, creado_en FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="Perfil.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
  <header class="perfil-header">
    <div class="header-content">
      <h1>Mi Perfil</h1>
    </div>
  </header>
  
  <div class="container">
    <section class="profile-section">
      <div class="section-header">
        <span class="icon">👤</span>
        <h2>Información Personal</h2>
      </div>
      <div class="profile-info">
        <div class="info-item">
          <span class="label">Nombre:</span>
          <span class="value"><?php echo htmlspecialchars($user['nombre']); ?></span>
        </div>
        <div class="info-item">
          <span class="label">Email:</span>
          <span class="value"><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
        <div class="info-item">
          <span class="label">Miembro desde:</span>
          <span class="value"><?php echo htmlspecialchars($user['creado_en']); ?></span>
        </div>
      </div>
    </section>
    
    <section class="edit-section">
      <div class="section-header">
        <span class="icon">✏️</span>
        <h2>Editar Información</h2>
      </div>
      
      <?php if ($mensaje): ?>
        <div class="mensaje <?php echo $tipo_mensaje; ?>">
          <?php echo htmlspecialchars($mensaje); ?>
        </div>
      <?php endif; ?>
      
      <form method="post" class="edit-form">
        <div class="form-group">
          <label for="nombre">Nombre de usuario</label>
          <input 
            type="text" 
            id="nombre" 
            name="nombre" 
            value="<?php echo htmlspecialchars($user['nombre']); ?>" 
            required
            class="form-input"
          >
        </div>
        
        <div class="form-group">
          <label for="password_actual">Contraseña actual (solo si quieres cambiar la contraseña)</label>
          <input 
            type="password" 
            id="password_actual" 
            name="password_actual" 
            placeholder="Deja vacío si no quieres cambiar la contraseña"
            class="form-input"
          >
        </div>
        
        <div class="form-group">
          <label for="nueva_password">Nueva contraseña (opcional)</label>
          <input 
            type="password" 
            id="nueva_password" 
            name="nueva_password" 
            placeholder="Mínimo 6 caracteres"
            class="form-input"
          >
        </div>
        
        <div class="form-group">
          <label for="confirmar_password">Confirmar nueva contraseña</label>
          <input 
            type="password" 
            id="confirmar_password" 
            name="confirmar_password" 
            placeholder="Repite la nueva contraseña"
            class="form-input"
          >
        </div>
        
        <div class="form-buttons">
          <button type="submit" class="btn btn-success">
            <span>💾 Guardar cambios</span>
          </button>
          <button type="reset" class="btn btn-secondary">
            <span>🔄 Cancelar</span>
          </button>
        </div>
      </form>
    </section>
    
    <section class="actions-section">
      <div class="section-header">
        <span class="icon">⚙️</span>
        <h2>Acciones</h2>
      </div>
      <div class="actions-buttons">
        <a href="../PaginaPrin.php" class="btn btn-primary"><span>🏠 Volver al inicio</span></a>
        <a href="/ArtazaFinal/auth/eliminar_cuenta.php" class="btn btn-danger"><span>🗑️ Eliminar cuenta</span></a>
      </div>
    </section>
  </div>
</body>
</html>

