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
      <!--form id="checkout" action="/" method="post" target="_blank" enctype="multipart/form-data"-->
      <form class="puzzle login edit" action="item_action.php" method="post" target="_self">
        <input type="hidden" name="formType" value="new_item">
        <div class="box _alert">
          <strong>Atenção!</strong>
          <div>
            <p><!-- --></p>
            <input type="button" class="btnNo" value="Não"> <input class="btnYes" type="button" value="SIM">
          </div>
        </div>
        <?php
          require_once 'inc/profile.php';
          $submitReturn = (!isset($_GET["msg"]) ? -1 : $_GET["msg"]);
        ?>
          <p><strong>ADICIONAR ITEM</strong></p>
          <?php
              switch ($submitReturn) {
                  case 0:
                      echo '<em class="error">Algo de errado aconteceu. Tente novamente.<br></em>';
                      break;
                  default:
                      break;
              }

            $oConn = New Conn();
            $oSlctMatters = $oConn->SQLselector("*","tbl_matters","","tag");
            if ($oSlctMatters->rowCount() > 0) {
              echo "<label>Matéria</label>";
              echo "<select name='matter'>";
              while ( $row = $oSlctMatters->fetch(PDO::FETCH_ASSOC) ) {
                echo "<option value='".$row['id']."'>".utf8_encode($row['title'])."</option>";
              }
              echo "</select>";
            }
          ?>
          <label>Nível</label>
          <select name="level">
            <option value="0">Nível Fácil</option>
            <option value="1">Nível Médio</option>
            <option value="2">Nível Difícil</option>
          </select>
          <label>Pergunta</label>
          <textarea name="question"></textarea>
          <div class="file">
            <label>Arquivo<br><em>Selecione um arquivo de até 100MB.</em></label>
            <input type="file" name="file" id="file" class="hide" />
            <input type="hidden" name='filePath' value="" />
            <div id="image_preview">
              <input type="text" name='figlabel' placeholder="Difina a fonte a imagem" value="" />
              <figure>
                <img id="previewing" src="images/icons/puzzleIcon.png" />  
                <figcaption>sem imagem</figcaption>
              </figure>
            </div>
            <a class="btn upload home d-middle" href="javascript:void(0);" title="CARREGAR ARQUIVO"><i class="fa fa-upload"></i></a>
          </div>
          <div class="answers">
            <label>Resposta 1</label>
            <input type="text" name='answer[]' value="" />
            <input type='hidden' name='answerID[]' value="" />
          </div>
          <a class="btn remove" href="javascript:void(0);" title="REMOVER ÚLTIMO ITEM"><i class="fa fa-minus"></i></a>
          <a class="btn add" href="javascript:void(0);" title="ADICIONAR ITEM"><i class="fa fa-plus"></i></a>
          <label>Resposta correta <br><em>ATENÇÂO: o campo abaixo define uma resposta de texto ignorando a multipla escolha.</em></label>   
          <select name='correctans'>
            <option value='0'>1</option>
          </select>
          <input type="text" placeholder="resposta escrita, digite aqui" name='txtcorrectans' value="" />
          <label>Status</label>
          <input type="radio" name="status" value="1" checked="checked" /> Ativo
          <input type="radio" name="status" value="0" /> Inativo

          <p>&nbsp;</p>
          <a class="btn home d-middle" href="./home.php" title="VOLTAR PARA HOME"><i class="fa fa-home"></i></a>
          <a class="btn save" href="javascript:void(0);" title="SALVAR DADOS"><i class="fa fa-save"></i></a>

        </div>
      </form>

    </main>

    <!-- build:js js/puzzle-adm.js -->
    <script type="text/javascript" src="js/puzzle-adm.js"></script>
    <!-- endbuild -->
  </body>
</html>