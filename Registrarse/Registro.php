<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="Registro.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Fuente títulos -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <!-- Fuente cursiva -->
    <link href="https://fonts.googleapis.com/css2?family=Playball&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Sección izquierda -->
        <div class="welcome-section">
            <h1>Bienvenido</h1>
            <p><i>
                Bienvenido, en esta sección<br>
                se va a poder registrar<br>
                para tener una cuenta<br>
                y ver las noticias de muchos juegos.<br>
                Si no le gustan los juegos,<br>
                esta no es su página.
            </i></p>
                <a href="../InicioDeSesion/InicioSesion.php">
                <button class="btn-iniciar">Iniciar sesión</button>
                </a>
        </div>

        <!-- Sección derecha -->
        <div class="register-section">
            <h1>Registrarse</h1>
            <form method="post" action="../auth/register.php">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ingrese su usuario" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Ingrese su email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>

                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Ingrese su teléfono">

                <button type="submit" class="btn-registrarse">Registrarse</button>
                <?php if (!empty($_GET['msg'])): ?>
                  <p style="color:#2e7d32; font-weight:bold;">¿No tenías cuenta? <?php echo htmlspecialchars($_GET['msg']); ?></p>
                <?php endif; ?>
                <?php if (!empty($_GET['err'])): ?>
                  <p style="color:#b71c1c; font-weight:bold;"><?php echo htmlspecialchars($_GET['err']); ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>


