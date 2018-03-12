<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
require_once 'usuario.php';
require_once 'autenticador.php';
require_once 'sessao.php';
$aut = AutenticadorPuzzle::instanciar();
if ($aut->esta_logado()) {
    session_write_close();
    session_destroy();
    $aut->expulsar();
    //header('location: ../');
  }
?>
