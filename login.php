<?php 
  session_start();
  session_destroy();
  $_d = (isset($_REQUEST["d"]) && !empty($_REQUEST["d"])) ? $_REQUEST["d"] : '' ; //down
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
        <div class="intro">
          <p style="font-size: .7em; font-weight: bold"><img src="images/icons/puzzleIcon.png" /><br><!--Perguntas baseadas na Coleção Eu gosto m@ais<br>Editora IBEP - 2ª Edição 2012<br>3º ano - Ensino Fundamental--></p>
          <p>ACESSO RESTRITO</p><br>
          <em class="error hide">verifique a informação e tente novamente<br></em>
          <label>Digite seu e-mail:</label>
          <input type="text" name="email" value="" />
          <label>Digite sua senha:</label>
          <input type="password" name="password" value="" />
          <!--input type="submit" id="submit" name="submit" value="OK" /-->
          <p>&nbsp;</p>
          <a class="btn home d-middle" href="./" title="IR PARA O JOGO"><i class="fa fa-gamepad"></i></a>
          <a class="btn signin" href="javascript:void(0);" value="COMEÇAR"><i class="fa fa-sign-in"></i></a>
        </div>
      </form>

    </main>

    <!-- build:js js/puzzle-adm.js -->
    <script type="text/javascript" src="js/puzzle-adm.js"></script>
    <!-- endbuild -->
  </body>
</html>