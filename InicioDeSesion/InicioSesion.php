<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="inicioDeSesion.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Fuente para títulos -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <!-- Fuente cursiva del mensaje -->
    <link href="https://fonts.googleapis.com/css2?family=Playball&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Sección izquierda -->
        <div class="login-section">
            <h1>Iniciar sesion</h1>
            <form method="post" action="../auth/login.php">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Ingrese su email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>

                <button type="submit" class="btn-ingresar">Ingresar</button>
                <?php if (!empty($_GET['err'])): ?>
                  <p style="color:#b71c1c; font-weight:bold;"><?php echo htmlspecialchars($_GET['err']); ?></p>
                <?php endif; ?>
            </form>
        </div>

        <!-- Sección derecha -->
        <div class="welcome-section">
            <h1>Bienvenido</h1>
            <p><i>Bienvenido de vuelta esperemos de que te guste la experiencia del <b>PHONKKK</b></i></p>
            <a href="../Registrarse/Registro.php">
                <button class="btn-registrarse">Registrarse</button>
            </a>
        </div>
    </div>
</body>
</html>


