<?php 
    require_once 'connector.php';
    $nvg  = $_REQUEST["formType"];
    $matter = $_REQUEST["matter"];
    $level = $_REQUEST["level"];
    $question = $_REQUEST["question"];
    $ilustration = $_REQUEST["filePath"];
    $answers = ( !empty($_REQUEST["answer"][0]) ) ? $_REQUEST["answer"] : 0 ;
    $answersID = ( $answers ) ? $_REQUEST["answerID"] : 0 ;
    $correctans = $_REQUEST["correctans"];
    $txtcorrectans = $_REQUEST["txtcorrectans"];
    $status = $_REQUEST["status"];
    $oConn = New Conn();
    $id;

    switch ($nvg) {
      case "new_item":
        
        if( !empty($txtcorrectans) ){
          $sqlInsert = $oConn->SQLinserter("tbl_questions", "id_matter,level,question,img_src,answer,status,inserted,modified", "'$matter','$level','$question','$ilustration','$txtcorrectans','$status',now(),now()");
          if($sqlInsert){
            header('location: list.php?msg=1');
          }
          else{
            header('location: add_item.php?msg=0');
          }
          return;
        }

        $sqlInsert = $oConn->SQLinserter("tbl_questions", "id_matter,level,question,img_src,answer,status,inserted,modified", "'$matter','$level','$question','$ilustration','$correctans','$status',now(),now()");
        if($sqlInsert){
          //Get last id inserted
          $sqlSct = $oConn->SQLselector("*","tbl_questions","","id DESC");
          if ($sqlSct->rowCount() > 0) {
            $rowQ = $sqlSct->fetch(PDO::FETCH_ASSOC);
            $id = $rowQ['id'];
          }
          //Answers
          if($answers){
            //For each Answers from Form
            foreach($answers as $c => $c_name ){
              $sqlInsertQ = $oConn->SQLinserter("tbl_options","id_question, answer","'$id','$answers[$c]'");
            }
            if($sqlInsertQ){
              header('location: list.php?msg=1');
            }
            else{
              header('location: add_item.php?msg=0');
            }
          }
        }else{
          header('location: add_item.php?msg=0');
        }
      break;
      case "update_item":
        $qID  = $_REQUEST["qID"];

        //Get IMG SRC
        $sqlSct = $oConn->SQLselector("img_src","tbl_questions","id='".$qID."'","");
        if ($sqlSct->rowCount() > 0) {
          while ( $rowIMG = $sqlSct->fetch(PDO::FETCH_ASSOC) ) {
            $img_src = $rowIMG['img_src'];
            
            if($ilustration != $img_src){
              $ilustration = $img_src;
            }

          }
        }

        if( !empty($txtcorrectans) ){
          $sqlUpdate = $oConn->SQLupdater("tbl_questions","modified = now(),id_matter='$matter',level='$level',question='$question',img_src='$ilustration',answer='$txtcorrectans',status='$status'","id='".$qID."'");
        }else{
          $sqlUpdate = $oConn->SQLupdater("tbl_questions","modified = now(),id_matter='$matter',level='$level',question='$question',img_src='$ilustration',answer='$correctans',status='$status'","id='".$qID."'");
        }
        //$row = $sqlUpdate->fetch(PDO::FETCH_ASSOC);

        if($sqlUpdate){
          //Answers
          if($answers){
            //For each Answers from Form
            foreach($answers as $c => $c_name ){
              if(!empty($answersID[$c])){
                $oSlctOptions = $oConn->SQLselector("*","tbl_options","id_question='".$answersID[$c]."'","id");
                if ($oSlctOptions) {
                  $sqlUpdate = $oConn->SQLupdater("tbl_options","answer='$answers[$c]'","id='".$answersID[$c]."'");
                }
              }else{
                $sqlInsert = $oConn->SQLinserter("tbl_options","id_question, answer","'$qID','$answers[$c]'");
              }
            }

            //For each Answers from Database
            //echo ' answersID : ' . $answersID[$c].' answers : ' . $answers[$c]. '<br>';
            $sqlSctA = $oConn->SQLselector("*","tbl_options","id_question='".$qID."'","");
            while ( $rowO = $sqlSctA->fetch(PDO::FETCH_ASSOC) ) {
              $currItem = $rowO["answer"];
              $currItemID = $rowO["id"];
              $exist = false;
              foreach($answers as $c => $c_name ){
                if($currItem == $answers[$c]){
                  $exist = true;
                  break;
                }else{
                  $exist = false;
                }
              }
              if(!$exist){
                $sqlDel = $oConn->SQLdeleter("tbl_options","id='".$currItemID."'");
              }
            }
            if($sqlUpdate || $sqlInsert){
              header('location: edit_item.php?m='.$matter.'&l='.$level.'&q='.$qID.'&msg=1');
            }
            else{
              header('location: edit_item.php?m='.$matter.'&l='.$level.'&q='.$qID.'&msg=0');
            }
          }else{
            header('location: edit_item.php?m='.$matter.'&l='.$level.'&q='.$qID.'&msg=1');
          }


          //header('location: edit_item.php?m='.$matter.'&l='.$level.'&q='.$qID);
        }
        else{
          //echo $qID;
          ///header('location: ../page.php?nvg=item&pid='.$id.'&msg=0');
        }       

      break;
    }


?>