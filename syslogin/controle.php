<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
require_once 'usuario.php';
require_once 'autenticador.php';
require_once 'sessao.php';

 
switch($_REQUEST["formType"]) {
 
    case 'login': {
 
        # Uso do singleton para instanciar
        # apenas um objeto de autentica��o
        # e esconder a classe real de autentica��o
        $aut = AutenticadorPuzzle::instanciar();
 
        # efetua o processo de autentica��o
        if ($aut->logar($_REQUEST["email"], $_REQUEST["password"])) {
            # redireciona o usu�rio para dentro do sistema
            echo $aut->logar($_REQUEST["email"], $_REQUEST["password"]);
            //header('location: ../home.php');
        }
        else {
            # envia o usu�rio de volta para 
            # o form de login
            echo "error";
            //header('location: ../login.php?msg=0');
        }
 
    } break;
 
    case 'sair': {
 
        # envia o usu�rio para fora do sistema
        # o form de login
        session_destroy();
        header('location: ../login.php');
 
    } break;

    default: {
        session_destroy(); 
        header('location: ../login.php');
    }
 
}