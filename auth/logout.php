<?php
session_start();
session_destroy();
header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php');
exit;
?>

