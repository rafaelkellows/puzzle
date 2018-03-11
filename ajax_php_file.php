<?php
	//header("Content-Type: text/plain, charset=ISO-8859-1");
    $servername = 'http://'.$_SERVER['SERVER_NAME'].':8080/edsa-puzzle/';
	
	if(isset($_FILES["file"]["type"])){
		$validextensions = array("jpeg", "jpg", "png");
		$temporary = explode(".", $_FILES["file"]["name"]);
		$file_extension = end($temporary);
		if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
		) && ($_FILES["file"]["size"] < 300000)//Approx. 300kb files can be uploaded.
		&& in_array($file_extension, $validextensions)) {
		if ($_FILES["file"]["error"] > 0){
			echo '0';	//;"Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
		}else{
			if (file_exists("upload/" . $_FILES["file"]["name"])) {
				echo '2';//;$_FILES["file"]["name"] . " Esse arquivo já existe!";
			}else{
				$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
				$targetPath = "upload/".$_FILES['file']['name']; // Target path where file is to be stored
				
				$file_name = $_FILES['file']['name'];
				$File_Name          = strtolower($file_name);
				$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
				$Random_Number      = rand(0, 9999999999); //Random number to be added to name.

			    $now = new DateTime();
			    $dateSR = str_replace('-','_',$now->format('d-m-Y H:i:s'));
			    $dateSR = str_replace(' ','_',$dateSR);
			    $dateSR = str_replace(':','_',$dateSR);

				$NewFileName = $Random_Number.'_'.$dateSR.$File_Ext; //new file name

				if ( move_uploaded_file($sourcePath,"upload/".$NewFileName) ) {
				  //echo "<P>FILE UPLOADED TO: $target_file</P>";
					//echo $servername."upload/".$NewFileName;
					echo "upload/".$NewFileName;
				} else {
				  	echo "0";
				  	//print_r(error_get_last());
				}

   				//move_uploaded_file($sourcePath,"upload/".$NewFileName) ; // Moving Uploaded file
				//echo "Imagem carregada com sucesso!><br/>";
				//echo "<br/><b>Nome do Arquivo:</b> " . $_FILES["file"]["name"] . "<br>";
				//echo "<b>Tipo do Arquivo:</b> " . $_FILES["file"]["type"] . "<br>";
				//echo "<b>Tamanho:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				//echo "upload/".$NewFileName; //'http://'.$_SERVER['SERVER_NAME'].'/'.$targetPath;
			}
		}
		}else{
			echo '1';	//;"Tipo ou Tamanho inválido. Porfavor, siga as instruções.";
		}
	}else{
		echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
	}
?>