<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php');
  exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php?err=Completa+email+y+contraseña');
  exit;
}

$stmt = $mysqli->prepare('SELECT id, nombre, email, password_hash, is_admin FROM usuarios WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
  // No existe: sugerir registro
  header('Location: /ArtazaFinal/Registrarse/Registro.php?email=' . urlencode($email) . '&msg=No+existe+usuario,+crea+tu+cuenta');
  exit;
}

if (!password_verify($password, $user['password_hash'])) {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php?err=Contraseña+incorrecta');
  exit;
}

// Login OK
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['user_name'] = $user['nombre'];
$_SESSION['user_email'] = $user['email'];

if (!empty($user['is_admin'])) {
  // También marcar sesión de admin para las rutas protegidas
  $_SESSION['is_admin'] = true;
  $_SESSION['admin_id'] = (int)$user['id'];
  $_SESSION['admin_name'] = $user['nombre'];
  header('Location: /ArtazaFinal/Pagina-Prin/Admin/PaginaAdmin.php');
  exit;
}

header('Location: /ArtazaFinal/Pagina-Prin/PaginaPrin.php');
exit;



