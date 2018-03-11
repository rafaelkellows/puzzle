<?php 
  require_once "connector.php"; 
  header("Content-Type: text/plan; charset=ISO-8859-1");

  $oConn = New Conn();
  $oSlctMatters = $oConn->SQLselector("*","tbl_matters","","tag"); 
  $dataJson = '';
  if($oSlctMatters->rowCount() > 0) {
    $matterCount = 0;
    $matterLength = $oSlctMatters->rowCount();
    
    //1. OPEN JSON
    $dataJson .= '[';
    // ------------- Select Matters ------------- //
    while ( $row = $oSlctMatters->fetch(PDO::FETCH_ASSOC) ) {
      $matterCount++;
      //2. OPEN MATTERS
      $dataJson .= '{';
      $dataJson .=  '"id":"'.$row['id'].'",';
      $dataJson .=  '"matter":"'.$row['tag'].'",';
      $dataJson .=  '"title":"'.utf8_decode($row['title']).'",';
      $dataJson .=  '"img":"'.$row['icon'].'"';
      
      // ------------- Select Questions ------------- //
      $oSlctQuestions = $oConn->SQLselector("*","tbl_questions","id_matter='".$row['id']."' AND status = 1","level");
      if ($oSlctQuestions->rowCount() > 0) {
        $questionCount = 0;
        $questionLength = $oSlctQuestions->rowCount();
        $questioncurrLevel='';
        //3. OPEN QUESTIONS
        $dataJson .=  ',"questions": {';

        // ------------- Select Levels  ------------- //
        while ( $rowQ = $oSlctQuestions->fetch(PDO::FETCH_ASSOC) ) {
          $questionCount++;
          if($questioncurrLevel != $rowQ['level']){
            $questioncurrLevel = $rowQ['level'];
            //4. OPEN LEVEL
            $dataJson .= '"'.$questioncurrLevel.'" : [' ;
              // ------------- Select Options ------------- //
              $oSlctOptions = $oConn->SQLselector("*","tbl_questions","id_matter='".$row['id']."' AND status = 1 AND level = '".$questioncurrLevel."'","id");
              if ($oSlctOptions->rowCount() > 0) {
                $optionCount = 0;
                $optionLength = $oSlctOptions->rowCount();
                // ------------- Select Questions  ------------- //
                while ( $rowO = $oSlctOptions->fetch(PDO::FETCH_ASSOC) ) {
                  $optionCount++;
                  //5. OPEN QUESTION                
                  $dataJson .=  '[';
                  $dataJson .=  '["'.utf8_decode($rowO['question']);
                  
                  if ( !empty($rowO['img_src']) ){
                    $dataJson .=  '","'.utf8_decode($rowO['img_src']).'"]';
                    //$dataJson .=  '"]';
                  }else{
                    $dataJson .=  '"]';
                  }


                  // ------------- Select Answers  ------------- //
                  $oSlctAnswers = $oConn->SQLselector("*","tbl_options","id_question='".$rowO['id']."' AND status = 1","id");
                  if ($oSlctAnswers->rowCount() > 0) {
                    $answerCount = 0;
                    $answerLength = $oSlctAnswers->rowCount();
                    //6. OPEN ANSWER                
                    $dataJson .=  ',[';
                    while ( $rowA = $oSlctAnswers->fetch(PDO::FETCH_ASSOC) ) {
                      $answerCount++;
                      if($answerCount<$answerLength){
                        $dataJson .= '"'.utf8_decode($rowA['answer']).'",';
                      }else{
                        $dataJson .= '"'.utf8_decode($rowA['answer']).'"';
                      }

                    }
                    //6. CLOSE ANSWER                
                    ($answerCount<$answerLength) ? $dataJson .= ']' : $dataJson .= ']';
                  }else{
                    $dataJson .=  ',[]';
                  }



                  $dataJson .=  ',["'.$rowO['answer'].'"]';
                  //5. CLOSE QUESTION
                  ($optionCount<$optionLength) ? $dataJson .= '],' : $dataJson .= ']';
                }
              }


            //4. CLOSE LEVEL
            ($questionCount<($questionLength-1)) ? $dataJson .= ',' : $dataJson .= '';
            // REMovi a vírgula do primeira variavel
            //
            //
            //
            //
          }
        }
        //3. CLOSE QUESTIONS
        $dataJson .= '}';

      }

      /*
      if (mysql_num_rows($oSlctQuestions)) {
        $qCount = 0;
        $qLength = mysql_num_rows($oSlctQuestions);
        $currLevel;
        $dataJson .=  ',';
        $dataJson .=  '"questions": {';

        while ( $rowQ = mysql_fetch_array($oSlctQuestions) ) {
          $qCount++;
          if($currLevel != $rowQ['level']){
            $currLevel = $rowQ['level'];
            $dataJson .= '"'.$currLevel.'" : [' ;
          }

          $dataJson .= '[["'.$rowQ['question'].'"],';
         
          //Select Options
          $oSlctOptions = $oConn->SQLselector("*","tbl_options","id_question='".$rowQ['id']."'","");
          if ($oSlctOptions) {
            $oCount = 0;
            $oLength = mysql_num_rows($oSlctOptions);
            $dataJson .= '[';
            while ( $rowO = mysql_fetch_array($oSlctOptions) ) {
              $oCount++;
              if($oCount<$oLength){
                $dataJson .= '"'.$rowO['answer'].'",';
              }else{
                $dataJson .= '"'.$rowO['answer'].'"';
              }
            }
            $dataJson .= '],';
          }

          $dataJson .= '["'.$rowQ['answers'].'"]';
          if($qCount<$qLength){
            $dataJson .= '],';
          }else{
            $dataJson .= ']]';
          }

        }

        $dataJson .= '}';
      }*/

      //2. CLOSE MATTERS
      ($matterCount<$matterLength) ? $dataJson .= '},' : $dataJson .= '}';
    }
    //1. CLOSE JSON
    $dataJson .= ']';
    
    echo $dataJson;
  }else{
    $dataJson .= '[{}]';
  }

?>