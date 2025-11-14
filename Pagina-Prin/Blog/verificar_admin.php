<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .info { color: #3498db; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Verificación de Sesión de Admin</h1>
    
    <div class="info-box">
        <h2>Estado de la Sesión:</h2>
        <p><strong>User ID:</strong> <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'No definido'; ?></p>
        <p><strong>User Name:</strong> <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'No definido'; ?></p>
        <p><strong>is_admin (sesión):</strong> 
            <?php 
            if (isset($_SESSION['is_admin'])) {
                echo $_SESSION['is_admin'] === true ? '<span class="success">TRUE</span>' : '<span class="error">FALSE o no es true</span>';
            } else {
                echo '<span class="error">NO DEFINIDO</span>';
            }
            ?>
        </p>
        <p><strong>Verificación completa:</strong> 
            <?php 
            $es_admin = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
            echo $es_admin ? '<span class="success">SÍ ES ADMIN</span>' : '<span class="error">NO ES ADMIN</span>';
            ?>
        </p>
    </div>

    <div class="info-box">
        <h2>Información de la Base de Datos:</h2>
        <?php
        require_once __DIR__ . '/../../config/db.php';
        if (isset($_SESSION['user_id'])) {
            $user_id = (int)$_SESSION['user_id'];
            $stmt = $mysqli->prepare("SELECT id, nombre, email, is_admin FROM usuarios WHERE id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($user = $result->fetch_assoc()) {
                echo "<p><strong>ID en BD:</strong> " . $user['id'] . "</p>";
                echo "<p><strong>Nombre en BD:</strong> " . htmlspecialchars($user['nombre']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";
                echo "<p><strong>is_admin en BD:</strong> ";
                if ($user['is_admin'] == 1 || $user['is_admin'] === '1' || $user['is_admin'] === true) {
                    echo '<span class="success">SÍ (1 o true)</span>';
                } else {
                    echo '<span class="error">NO (0 o false)</span>';
                }
                echo "</p>";
            } else {
                echo "<p class='error'>Usuario no encontrado en la base de datos</p>";
            }
        } else {
            echo "<p class='error'>No hay user_id en la sesión</p>";
        }
        ?>
    </div>

    <div class="info-box">
        <h2>Acciones:</h2>
        <p><a href="Blog.php">← Volver al Blog</a></p>
        <p><a href="/ArtazaFinal/auth/login.php">Iniciar Sesión</a></p>
        <p><a href="/ArtazaFinal/Pagina-Prin/Admin/login.php">Login de Admin</a></p>
    </div>

    <div class="info-box">
        <h2>Nota:</h2>
        <p class="info">Si eres admin pero no aparece el botón de eliminar, asegúrate de:</p>
        <ol>
            <li>Haber iniciado sesión como administrador</li>
            <li>Que tu cuenta tenga <code>is_admin = 1</code> en la base de datos</li>
            <li>Que la sesión tenga <code>$_SESSION['is_admin'] = true</code></li>
        </ol>
    </div>
</body>
</html>

