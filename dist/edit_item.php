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

    <link rel="stylesheet" href="css/styles.min.css"/>

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
      <form class="puzzle login edit" action="item_action.php" method="post" target="_self">
        <input type="hidden" name="formType" value="update_item">
        <input type="hidden" name="qID" value="<?php echo $_REQUEST["q"] ?>">
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
          <p><strong>EDITAR ITEM</strong></p>
          <?php
              switch ($submitReturn) {
                  case 0:
                      echo '<em class="error">Algo de errado aconteceu. Tente novamente.<br></em>';
                      break;
                  case 1:
                      echo '<em class="success">Dados atualizados.<br></em>';
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
                $selected = ($row['id']==$_REQUEST["m"]) ? 'selected' : ''; 
                echo "<option value='".$row['id']."' ".$selected.">".utf8_encode($row['title'])."</option>";
              }
              echo "</select>";
            }
          ?>
          <label>Nível</label>
          <select name='level'>
            <option <?php if($_REQUEST["l"]=='0') { echo 'selected'; } ?> value="0">Nível Fácil</option>
            <option <?php if($_REQUEST["l"]=='1') { echo 'selected'; } ?> value="1">Nível Médio</option>
            <option <?php if($_REQUEST["l"]=='2') { echo 'selected'; } ?> value="2">Nível Difícil</option>
          </select>
          <?php
            $oSlctOptions = $oConn->SQLselector("*","tbl_questions","id='".$_REQUEST["q"]."'","id");
            if ($oSlctOptions->rowCount() > 0) {
              echo "<label>Pergunta</label>";
              $rowQ = $oSlctOptions->fetch(PDO::FETCH_ASSOC);
              echo "<textarea name='question'>".$rowQ["question"]."</textarea>";
              if( !empty($rowQ["img_src"]) ){
                echo "<div class='file'>";
                echo "  <label>Arquivo</label>";
                echo "  <input type='file' name='file' id='file' class='hide' />";
                echo "  <input type='hidden' name='filePath' value='".$rowQ["img_src"]."' />";

                echo "  <figure>";
                echo "    <img id='previewing' src='".$rowQ["img_src"]."' />";
                echo "    <figcaption></figcaption>";
                echo "  </figure>";
                echo "  <a class='btn upload home d-middle' href='javascript:void(0);' title='CARREGAR ARQUIVO'><i class='fa fa-upload'></i></a>";
                echo "</div>";
              }else{
                echo "<div class='file'>";
                echo "  <label>Arquivo</label>";
                echo "  <input type='file' name='file' id='file' class='hide' />";
                echo "  <input type='hidden' name='filePath' value='' />";
                echo "  <figure>";
                echo "    <img id='previewing' src='images/icons/puzzleIcon.png' />";
                echo "    <figcaption>sem imagem</figcaption>";
                echo "  </figure>";
                echo "  <a class='btn upload home d-middle' href='javascript:void(0);' title='CARREGAR ARQUIVO'><i class='fa fa-upload'></i></a>";
                echo "</div>";
              }
            }else{
              echo "<textarea name='question'></textarea>";
            }

            $oSlctAnswers = $oConn->SQLselector("*","tbl_options","id_question='".$_REQUEST["q"]."'","id");            
            $answerCount = 0;
            if ($oSlctAnswers->rowCount() > 0) {
              echo "<div class='answers'>";
              while ( $rowA = $oSlctAnswers->fetch(PDO::FETCH_ASSOC) ) {
                $answerCount++;
                echo "<label>Resposta ".$answerCount."</label>";
                echo "<input type='hidden' name='answerID[]' value='".$rowA['id']."' />";
                echo "<input type='text' id='".$rowA['id']."' name='answer[]' value='".$rowA['answer']."' />";
              }
              echo "</div>";
            }else{
              $answerCount=1;
              echo "<div class='answers'>";
              echo "    <label>Resposta 1</label>";
              echo "    <input type='hidden' name='answerID[]' value='' />";
              echo "    <input type='text' id='' name='answer[]' value='' />";
              echo "</div>";
            }
          ?>
          <a class="btn remove" href="javascript:void(0);" title="REMOVER ÚLTIMO ITEM"><i class="fa fa-minus"></i></a>
          <a class="btn add" href="javascript:void(0);" title="ADICIONAR ITEM"><i class="fa fa-plus"></i></a>
          <label>Resposta correta</label>
          <?php
            echo "<select name='correctans'>";
            for($x = 0; $x < $answerCount; $x++){
              $selected = ($rowQ['answer']==$x) ? ' selected' : ''; 
              echo "<option value='".$x."' ".$selected.">".($x+1)."</option>";
            }
            echo "</select>";
            if( $rowQ['answer'] ){
              echo '&nbsp;<input type="text" placeholder="resposta escrita, digite aqui" name="txtcorrectans" value="'.$rowQ['answer'].'" />';
            }
          ?>
          
          <label>Status</label>
          <input type="radio" name="status" value="1" <?php if( $rowQ["status"] == '1' ) { echo ' checked'; } ?> /> Ativo
          <input type="radio" name="status" value="0" <?php if( $rowQ["status"] == '0' ) { echo ' checked'; } ?> /> Inativo
          <p>&nbsp;</p>
          <a class="btn home d-middle" href="./home.php" title="VOLTAR PARA HOME"><i class="fa fa-home"></i></a>
          <a class="btn save" href="javascript:void(0);" title="SALVAR DADOS"><i class="fa fa-save"></i></a>

        </div>
      </form>

    </main>

    <script src="js/puzzle-adm.js"></script>
  </body>
</html>