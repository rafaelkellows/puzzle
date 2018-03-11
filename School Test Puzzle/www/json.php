<?php 
  require_once "connector.php"; 
  header("Content-Type: text/plan; charset=ISO-8859-1");

  $oConn = New Conn();
  $oSlctMatters = $oConn->SQLselector("*","tbl_matters","","tag"); 
  $dataJson = '';
  if (mysql_num_rows($oSlctMatters)) {
    $matterCount = 0;
    $matterLength = mysql_num_rows($oSlctMatters);
    
    //1. OPEN JSON
    $dataJson .= '[';
    // ------------- Select Matters ------------- //
    while ( $row = mysql_fetch_array($oSlctMatters) ) {
      $matterCount++;
      //2. OPEN MATTERS
      $dataJson .= '{';
      $dataJson .=  '"id":"'.$row['id'].'",';
      $dataJson .=  '"matter":"'.$row['tag'].'",';
      $dataJson .=  '"title":"'.$row['title'].'",';
      $dataJson .=  '"img":"'.$row['icon'].'"';
      
      // ------------- Select Questions ------------- //
      $oSlctQuestions = $oConn->SQLselector("*","tbl_questions","id_matter='".$row['id']."' AND status = 1","level");
      if (mysql_num_rows($oSlctQuestions)) {
        $questionCount = 0;
        $questionLength = mysql_num_rows($oSlctQuestions);
        $questioncurrLevel;
        //3. OPEN QUESTIONS
        $dataJson .=  ',"questions": {';

        // ------------- Select Levels  ------------- //
        while ( $rowQ = mysql_fetch_array($oSlctQuestions) ) {
          $questionCount++;
          if($questioncurrLevel != $rowQ['level']){
            $questioncurrLevel = $rowQ['level'];
            //4. OPEN LEVEL
            $dataJson .= '"'.$questioncurrLevel.'" : [' ;
              // ------------- Select Options ------------- //
              $oSlctOptions = $oConn->SQLselector("*","tbl_questions","id_matter='".$row['id']."' AND status = 1 AND level = '".$questioncurrLevel."'","id");
              if (mysql_num_rows($oSlctOptions)) {
                $optionCount = 0;
                $optionLength = mysql_num_rows($oSlctOptions);
                // ------------- Select Questions  ------------- //
                while ( $rowO = mysql_fetch_array($oSlctOptions) ) {
                  $optionCount++;
                  //5. OPEN QUESTION                
                  $dataJson .=  '[';
                  $dataJson .=  '["'.$rowO['question'].'"]';



                  // ------------- Select Answers  ------------- //
                  $oSlctAnswers = $oConn->SQLselector("*","tbl_options","id_question='".$rowO['id']."' AND status = 1","");
                  if (mysql_num_rows($oSlctAnswers)) {
                    $answerCount = 0;
                    $answerLength = mysql_num_rows($oSlctAnswers);
                    //6. OPEN ANSWER                
                    $dataJson .=  ',[';
                    while ( $rowA = mysql_fetch_array($oSlctAnswers) ) {
                      $answerCount++;
                      if($answerCount<$answerLength){
                        $dataJson .= '"'.$rowA['answer'].'",';
                      }else{
                        $dataJson .= '"'.$rowA['answer'].'"';
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
            ($questionCount<$questionLength) ? $dataJson .= '],' : $dataJson .= ']';
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