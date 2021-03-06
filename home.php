<?php 
  require_once 'syslogin/usuario.php';
  require_once 'syslogin/autenticador.php'; 
  require_once 'syslogin/sessao.php';
  require_once 'connector.php'; 
   
  $aut = AutenticadorPuzzle::instanciar();
   
  $usuario = null;
  if ($aut->esta_logado()) {
    $usuario = $aut->pegar_usuario();
  }
  else {
    $aut->expulsar();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
  <title>School Tests Puzzle by Rafael Kellows</title>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="author" content="Rafael Kellows - rafaelkellows@hotmail.com">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    
    <link href="css/font-awesome/font-awesome.min.css" rel="stylesheet" media="screen">
    <link href="css/personal/stylesheet.css" rel="stylesheet" media="screen">

    <!-- Owl Carousel Assets -->
    <link href="css/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="css/owl-carousel/owl.theme.css" rel="stylesheet">

    <!-- build:css css/styles.min.css-->
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <!-- endbuild-->

    <link href="css/addtohomescreen.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="57x57" href="images/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="images/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="images/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="images/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="images/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="images/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="images/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="images/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/icons/favicon-16x16.png">
    <link rel="manifest" href="images/icons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="shortcut icon" href="images/icons/favicon.ico" type="image/x-icon">
    <link rel="icon" href="images/icons/favicon.ico" type="image/x-icon">

    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>

  </head>
  <body>
    <main>
      <form class="puzzle login" action="syslogin/controle.php" method="post" target="_self">
        <input type="hidden" name="formType" value="login">
        <div class="box _alert">
          <strong>Atenção!</strong>
          <div>
            <p><!-- --></p>
            <input type="button" class="btnNo" value="NÃO"> <input class="btnYes" type="button" value="SIM">
          </div>
        </div>
        <?php
          require_once 'inc/profile.php';
        ?>
          <p>BEM-VINDO, <strong><?php print $usuario->getName(); ?></strong>!<br>Navegue pelos ícones abaixo.</p>
          <p>&nbsp;</p>   
          <nav>
          <?php
            if( $usuario->getType() == 1 ){
          ?>
            <a class="btn" href="add_item.php" title="ADICIONAR ITEM"><i class="fa fa-plus"></i></a>
            <a class="btn" href="list.php" title="LISTAR ITENS"><i class="fa fa-list"></i></a>
            <a class="btn" href="javascript:void(0);" title="ADICIONAR USUÁRIO"><i class="fa fa-user"></i></a>
            <a class="btn" href="javascript:void(0);" title="LISTAR USUÁRIOS"><i class="fa fa-users"></i></a>
          <?php
            }else{
          ?>
            <a href="usr_create.php?nvg=user&uid=<?php print $usuario->getId(); ?>" title="Cadastrar Usuário"><span><i class="fa fa-user" aria-hidden="true"></i><font>Meu Perfil</font></span></a>
            <a href="usr_downloads.php" title="Arquivos Enviados"><span><i class="fa fa-download" aria-hidden="true"></i><font>Downloads</font></span></a>
          <?php
            }
          ?>
          </nav>
        </div>
      </form>

    </main>

    <!-- build:js js/puzzle-adm.js -->
    <script type="text/javascript" src="js/puzzle-adm.js"></script>
    <!-- endbuild -->
  </body>
</html>