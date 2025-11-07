<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /ArtazaFinal/Registrarse/Registro.php');
  exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$telefono = trim($_POST['telefono'] ?? ''); // no se guarda, pero podrías crear la columna luego

if ($nombre === '' || $email === '' || $password === '') {
  header('Location: /ArtazaFinal/Registrarse/Registro.php?err=Completa+todos+los+campos&email=' . urlencode($email));
  exit;
}

// Verificar si ya existe
$chk = $mysqli->prepare('SELECT id FROM usuarios WHERE email=?');
$chk->bind_param('s', $email);
$chk->execute();
if ($chk->get_result()->fetch_assoc()) {
  header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php?err=El+usuario+ya+existe,+inicia+sesión&email=' . urlencode($email));
  exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);
$ins = $mysqli->prepare('INSERT INTO usuarios (nombre, email, password_hash) VALUES (?,?,?)');
$ins->bind_param('sss', $nombre, $email, $hash);
$ins->execute();

$_SESSION['user_id'] = $ins->insert_id;
$_SESSION['user_name'] = $nombre;
$_SESSION['user_email'] = $email;

header('Location: /ArtazaFinal/Pagina-Prin/PaginaPrin.php');
exit;



