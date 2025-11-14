<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// Verificar que el usuario sea admin
if (empty($_SESSION['is_admin'])) {
    header('Location: Blog.php?error=No tienes permisos para eliminar publicaciones');
    exit;
}

// Obtener el ID del post a eliminar
$post_id = (int)($_GET['id'] ?? 0);

if (!$post_id) {
    header('Location: Blog.php?error=ID de publicación no válido');
    exit;
}

// Verificar que el post existe
$stmt = $mysqli->prepare("SELECT id FROM blog_posts WHERE id = ?");
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: Blog.php?error=La publicación no existe');
    exit;
}

// Eliminar el post (los comentarios y likes se eliminarán automáticamente por CASCADE)
$stmt = $mysqli->prepare("DELETE FROM blog_posts WHERE id = ?");
$stmt->bind_param('i', $post_id);

if ($stmt->execute()) {
    header('Location: Blog.php?success=Publicación eliminada correctamente');
} else {
    header('Location: Blog.php?error=Error al eliminar la publicación');
}
exit;
?>

