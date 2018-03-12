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
      <form class="puzzle login" action="syslogin/controle.php" method="post" target="_self">
        <input type="hidden" name="formType" value="login">
        <div class="box _alert">
          <strong>Aten?o!</strong>
          <div>
            <p><!-- --></p>
            <input type="button" class="btnNo" value="Não"> <input class="btnYes" type="button" value="SIM">
          </div>
        </div>
        <?php
          require_once 'inc/profile.php';
          $submitReturn = (!isset($_GET["msg"]) ? -1 : $_GET["msg"]);
        ?>
          <p><strong>LISTAR ITENS</strong></p>
          <?php
              switch ($submitReturn) {
                  case 1:
                      echo '<em class="success">Informações adicionadas com sucesso.<br></em>';
                      break;
                  default:
                      break;
              }
          ?>    
          <nav class="list">

          <?php
            $oConn = New Conn();
            $oSlctMatters = $oConn->SQLselector("*","tbl_matters","","tag");
            if($oSlctMatters->rowCount() > 0) {
              echo "<ul>";
              while ( $row = $oSlctMatters->fetch(PDO::FETCH_ASSOC) ) {

                echo "<li><img src='".utf8_encode($row['icon'])."' /><a href='javascript:void(0)'>".utf8_encode($row['title'])."</a></li>";

                $oSlctQuestions = $oConn->SQLselector("*","tbl_questions","id_matter='".$row['id']."'","level");
                if($oSlctQuestions->rowCount() > 0) {
                  echo "<ul class='hide'>";
                    $questioncurrLevel='';
                    while ( $rowQ = $oSlctQuestions->fetch(PDO::FETCH_ASSOC) ) {
                      if($questioncurrLevel != $rowQ['level']){
                        $questioncurrLevel = $rowQ['level'];
                        switch ($questioncurrLevel) {
                            case 0:
                                echo "<li>• Nível Fácil</li>";
                                break;
                            case 1:
                                echo "<li>• Nível Médio</li>";
                                break;
                            case 2:
                                echo "<li>• Nível Difícil</li>";
                                break;
                        }                        

                        $oSlctOptions = $oConn->SQLselector("*","tbl_questions","id_matter='".$row['id']."' AND level = '".$questioncurrLevel."'","id");
                        if($oSlctOptions->rowCount() > 0) {
                          echo "<ul class='hide'>";
                          $optionCount = 0;
                          while ( $rowO = $oSlctOptions->fetch(PDO::FETCH_ASSOC) ) {
                            $optionCount++;
                            echo "<li>".$optionCount." - ".$rowO['question']."</li>";
                            $oSlctAnswers = $oConn->SQLselector("*","tbl_options","id_question='".$rowO['id']."'","id");
                            if($oSlctAnswers->rowCount() > 0) {
                              echo "<ul class='hide'>";
                              $answerCount = 0;
                              if( !empty($rowO["img_src"]) ){
                                echo "<li>Fonte da imagem: '".$rowO['img_title']."'<img src='".$rowO['img_src']."' /></li>";
                              }
                              while ( $rowA = $oSlctAnswers->fetch(PDO::FETCH_ASSOC) ) {
                                $answerCount++;
                                echo "<li>".$answerCount.". ".$rowA['answer']."</li>";
                              }
                              echo "<li>Resposta correta: ".($rowO['answer']+1)."</li>";

                            }else{

                              echo "<ul class='hide'>";
                              echo "  <li>";
                              if( !empty($rowO["img_src"]) ){
                                echo "Fonte da imagem: ".$rowO['img_title']."<img src='".$rowO['img_src']."' /><br>";
                              }
                              echo "  Resposta correta: ".$rowO['answer']."</li>";
                            }
                            echo "<nav>";
                            echo "  <a class='btn' href='edit_item.php?m=".$row['id']."&l=".$rowQ['level']."&q=".$rowO['id']."' title='EDITAR ITEM'><i class='fa fa-edit'></i></a>";
                            $status = ($rowO['status']!='0') ? 'fa-unlock' : 'fa-lock' ;
                            $stitle = ($rowO['status']!='0') ? 'BLOQUEAR (clique para desabilitar este item)' : 'DESBLOQUEAR (clique para habilitar este item)' ;
                            echo "  <a class='btn' href='#' title='".$stitle."'><i class='fa ".$status."'></i></a>";
                            echo "</nav>";
                              echo "</ul>"; 

                          }
                          echo "</ul>";
                        }

                      }
                    }
                  echo "</ul>";
                }

              }
              echo "</ul>";
            }
          ?>
          </nav>
          <nav>
          <a class="btn home d-middle" href="./home.php" title="VOLTAR PARA HOME"><i class="fa fa-home"></i></a>
          <a class="btn d-middle" href="add_item.php" title="ADICIONAR ITEM"><i class="fa fa-plus"></i></a>
          </nav>
        </div>
      </form>

    </main>

    <script src="js/puzzle-adm.js"></script>
  </body>
</html>