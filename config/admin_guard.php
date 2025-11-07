<?php
session_start();
if (empty($_SESSION['is_admin'])) {
  header('Location: /ArtazaFinal/Pagina-Prin/Admin/login.php?err=Debes+iniciar+sesión+como+administrador');
  exit;
}


