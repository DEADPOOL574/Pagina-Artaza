<?php
session_start();
session_destroy();
// Redirigir siempre al login principal
header('Location: /ArtazaFinal/InicioDeSesion/InicioSesion.php');
exit;


