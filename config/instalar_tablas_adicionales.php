<?php
// Script para instalar las tablas adicionales (cursos, blog, CV)
require_once __DIR__ . '/db.php';

$sql_file = __DIR__ . '/tablas_adicionales.sql';
$sql_content = file_get_contents($sql_file);

// Dividir el contenido en sentencias individuales
$statements = array_filter(
    array_map('trim', explode(';', $sql_content)),
    function($stmt) {
        return !empty($stmt) && !preg_match('/^\s*--/', $stmt);
    }
);

$errors = [];
$success = 0;

foreach ($statements as $statement) {
    if (!empty($statement)) {
        if ($mysqli->multi_query($statement)) {
            do {
                if ($result = $mysqli->store_result()) {
                    $result->free();
                }
            } while ($mysqli->next_result());
            $success++;
        } else {
            $errors[] = $mysqli->error;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalación de Tablas Adicionales</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f7fa; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h1 { color: #f39c12; }
        .success { color: #27ae60; padding: 10px; background: #d4edda; border-radius: 6px; margin: 10px 0; }
        .error { color: #e74c3c; padding: 10px; background: #f8d7da; border-radius: 6px; margin: 10px 0; }
        .info { color: #3498db; padding: 10px; background: #d1ecf1; border-radius: 6px; margin: 10px 0; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Instalación de Tablas Adicionales</h1>
        
        <?php if (empty($errors)): ?>
            <div class="success">
                <strong>¡Éxito!</strong> Se han creado todas las tablas adicionales correctamente.
            </div>
            <div class="info">
                <strong>Tablas creadas:</strong>
                <ul>
                    <li>cursos - Para gestión de cursos</li>
                    <li>blog_posts - Para posts del blog</li>
                    <li>blog_comentarios - Para comentarios del blog</li>
                    <li>blog_likes - Para likes del blog</li>
                    <li>usuario_cv - Para información del CV</li>
                    <li>cv_experiencia - Para experiencia laboral</li>
                    <li>cv_educacion - Para educación</li>
                    <li>cv_habilidades - Para habilidades</li>
                </ul>
            </div>
        <?php else: ?>
            <div class="error">
                <strong>Errores encontrados:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <p>
            <a href="/ArtazaFinal/Pagina-Prin/Admin/PaginaAdmin.php">← Volver al panel de administración</a>
        </p>
    </div>
</body>
</html>

