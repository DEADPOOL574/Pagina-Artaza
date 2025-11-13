<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'respawnnews';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
  http_response_code(500);
  echo 'Error de conexión a la base de datos: ' . $mysqli->connect_error;
  exit;
}
$mysqli->set_charset('utf8mb4');


