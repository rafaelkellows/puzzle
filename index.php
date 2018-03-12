<?php 
  require_once 'syslogin/usuario.php';
  require_once 'syslogin/autenticador.php'; 
  require_once 'syslogin/sessao.php';
  require_once 'connector.php'; 
   
  $aut = AutenticadorPuzzle::instanciar();
   
  $usuario = null;
  if ($aut->esta_logado()) {
    $usuario = $aut->pegar_usuario();
  }else{
    $usuario = '';

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
    <script type="text/javascript" src="js/addtohomescreen.js"></script>

  </head>
  <body>
  <script>
    addToHomescreen('http://www.spatula.com/pz/');
  </script>    
    <main>
      <form class="puzzle" action="syslogin/controle.php" method="post" target="_self">
        <input type="hidden" name="formType" value="login">

        <div class="box _alert">
          <strong>Atenção!</strong>
          <div>
            <p>Você tem certeza da sua resposta?</p>
            <input type="button" class="btnNo hide" value="NÃO"> <input class="btnYes hide" type="button" value="SIM">
            <a class="btn cancel" href="javascript:void(0);" value="NÃO"><i class="fa fa-times"></i></a>
            <a class="btn forward" href="javascript:void(0);" value="SIM"><i class="fa fa-forward"></i></a>
          </div>
        </div>

        <?php 
          $class = '';
          $usrName = '';
          $usrlogged = 0;
          if( !empty($usuario) ){
            $class = " style='display:block;'";
            $usrName = $usuario->getName();
            $usrlogged = 1;
          }
        ?>
        <input type="hidden" name="usrlogged" value="<?php echo $usrlogged; ?>" />
        <div class="profile" <?php echo $class; ?>>
          <p>Olá, <strong><?php echo $usrName; ?></strong>. <a href="syslogin/logout.php"><i class="fa fa-sign-out"></i></a></p>
          <div id="contenedor">
            <div><strong>Tempo:&nbsp;</strong></div>
            <div class="reloj" id="Horas">00</div>
            <div class="reloj" id="Minutos">:00</div>
            <div class="reloj" id="Segundos">:00</div>
            <div class="reloj" id="Centesimas">:00</div>
          </div>
        </div>
        <div class="intro">
          <p style="font-size: .7em; font-weight: bold"><img src="images/icons/puzzleIcon.png" /><br><!--Perguntas baseadas na Coleção Eu gosto m@ais<br>Editora IBEP - 2ª Edição 2012<br>3º ano - Ensino Fundamental--></p>
          <p>Olá, eu sou o <em>Profº Cloud</em>. <br>Clique em <i class="fa fa-sign-in"></i> para iniciar o seu teste ou<br> em <i class="fa fa-lock"></i> para acessar a area restrita.</p><br>
          <?php
            if( !empty($usuario) ){
              $faIcon = 'fa-gamepad';
            }else{
              $faIcon = 'fa-sign-in';
            }
          ?>
          <a class="btn" href="javascript:void(0);" value="BEGIN"><i class="fa <?php echo $faIcon; ?>"></i></a>
          <a class="btn" href="login.php" value="ACESSO RESTRITO"><i class="fa fa-lock"></i></a>
        </div>
        <div class="questions">
          <p class="theme"><!-- Passos --></p>
          <p class="steps"><!-- Passos --></p>
          <br>
          <dl>
            <dt><!-- Perguntas --></dt>
            <dd> 
              <ul>
                <!-- Respostas -->
              </ul>
          </dl>
          <nav class="qActions">
            <a class="btn cancel btnCancel" href="javascript:void(0);" value="CANCELAR"><i class="fa fa-times"></i></a>
            <a class="btn confirm btnConfirm" href="javascript:void(0);" value="CONFIRMAR"><i class="fa fa-check"></i></a>
            <a class="btn restart btnRestart" href="javascript:void(0);" value="FAZER O TESTE DE NOVO"><i class="fa fa-reply"></i></a>
          </nav>
          <!--input type="button" class="btnCancel" value="CANCELAR"> <input class="btnConfirm" type="button" value="CONFIRMAR"> <input class="btnRestart" type="button" value="FAZER O TESTE DE NOVO"-->
        </div>
      </form>

    </main>

    <!-- build:js js/puzzle.js -->
    <script type="text/javascript" src="js/puzzle.js"></script>
    <!-- endbuild -->
  </body>
</html>