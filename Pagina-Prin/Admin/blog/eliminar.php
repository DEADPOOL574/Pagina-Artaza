<?php 
require_once __DIR__ . '/../../../config/admin_guard.php';
require_once __DIR__ . '/../../../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header('Location: index.php?error=ID de publicación no válido');
    exit;
}

// Verificar que el post existe
$stmt = $mysqli->prepare("SELECT id, titulo FROM blog_posts WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php?error=La publicación no existe');
    exit;
}

// Eliminar el post (los comentarios y likes se eliminarán automáticamente por CASCADE)
$stmt = $mysqli->prepare("DELETE FROM blog_posts WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: index.php?success=Publicación eliminada correctamente');
} else {
    header('Location: index.php?error=Error al eliminar la publicación');
}
exit;
?>

