<?php 
require_once __DIR__ . '/../../config/admin_guard.php';
require_once __DIR__ . '/../../config/db.php';

// Obtener estadísticas
$noticias_count = $mysqli->query("SELECT COUNT(*) as total FROM noticias")->fetch_assoc()['total'];
$usuarios_count = $mysqli->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
// Visitas del mes (simulado - podrías crear una tabla de visitas si quieres)
$visitas_mes = 14209; // Por ahora estático, puedes implementar tracking después

// Obtener últimas noticias
$ultimas_noticias = $mysqli->query("SELECT id, titulo, creado_en FROM noticias ORDER BY creado_en DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel de administración de Respawn News: dashboard, noticias, usuarios y configuración.">
    <title>Panel de Administración - Respawn News</title>
    <link rel="stylesheet" href="PaginaAdmin.css">
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2 class="logo">Admin</h2>

        <nav>
            <a href="PaginaAdmin.php" class="active">📊 Dashboard</a>
            <a href="noticias/index.php">📰 Noticias</a>
            <a href="noticias/crear.php">➕ Crear noticia</a>
            <a href="usuarios/index.php">👤 Usuarios</a>
            <a href="cursos/index.php">🎓 Cursos</a>
            <a href="cursos/crear.php">➕ Crear curso</a>
        </nav>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="main">

        <!-- BARRA SUPERIOR -->
        <header class="topbar">
            <h1>Panel de Administración</h1>
            <div class="user">
                <span>Administrador</span> 🔒
            </div>
        </header>

        <!-- ESTADÍSTICAS -->
        <section class="stats">

            <div class="card">
                <h3>Noticias publicadas</h3>
                <p class="numero"><?php echo number_format($noticias_count); ?></p>
            </div>

            <div class="card">
                <h3>Usuarios registrados</h3>
                <p class="numero"><?php echo number_format($usuarios_count); ?></p>
            </div>

            <div class="card">
                <h3>Visitas del mes</h3>
                <p class="numero"><?php echo number_format($visitas_mes); ?></p>
            </div>

        </section>

        <!-- ACCIONES RÁPIDAS (ENLACES AL ABM) -->
        <div class="crear" style="display:flex; gap:12px; flex-wrap:wrap;">
            <a href="noticias/crear.php" class="btn-crear">+ Crear nueva noticia</a>
            <a href="noticias/index.php" class="btn-crear" style="background:#1976d2">Gestionar noticias</a>
            <a href="usuarios/index.php" class="btn-crear" style="background:#6a1b9a">Gestionar usuarios</a>
            <a href="cursos/index.php" class="btn-crear" style="background:#9b59b6">Gestionar cursos</a>
            <a href="cursos/crear.php" class="btn-crear" style="background:#8e44ad">+ Crear curso</a>
            <a href="logout.php" class="btn-crear" style="background:#b71c1c">Cerrar sesión</a>
        </div>

        <!-- TABLA DE NOTICIAS -->
        <section class="tabla">
            <h2>Últimas publicaciones</h2>

            <table>
                <thead>
                    <tr class="head">
                        <th>ID</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($ultimas_noticias->num_rows === 0): ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding:40px; color:#999;">
                                No hay noticias publicadas aún. <a href="noticias/crear.php" style="color:#f39c12;">Crear primera noticia</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while($noticia = $ultimas_noticias->fetch_assoc()): 
                            $fecha = date('d/m/Y', strtotime($noticia['creado_en']));
                        ?>
                            <tr>
                                <td><?php echo str_pad($noticia['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($noticia['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($fecha); ?></td>
                                <td>
                                    <button class="editar" onclick="window.location.href='noticias/editar.php?id=<?php echo (int)$noticia['id']; ?>'">Editar</button>
                                    <button class="eliminar" onclick="if(confirm('¿Estás seguro de eliminar esta noticia?')) window.location.href='noticias/eliminar.php?id=<?php echo (int)$noticia['id']; ?>'">Eliminar</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </main>

</body>
</html>

