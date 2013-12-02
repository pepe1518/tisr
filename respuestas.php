<?php
//include db configuration file
include_once 'lib/includeLibs.php';;
$var=$_SESSION["idproblema"];
if(isset($_POST["content_txts"]) && strlen($_POST["content_txts"])>0) 
{	//check $_POST["content_txt"] is not empty

	//sanitize post value, PHP filter FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH Strip tags, encode special characters.
	$contentToSave = filter_var($_POST["content_txts"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
	
	if(mysql_query("INSERT INTO datos_salidarespuesta(id_datosalidarespuesta,id_respuesta,valor_datossalidarespuesta) VALUES (null, $var, $contentToSave);"))
	{
		 //Record was successfully inserted, respond result back to index page
		  $my_id = mysql_insert_id(); //Get ID of last inserted row from MySQL
		  echo '<li id="item_'.$my_id.'">';
		  echo '<div class="del_wrapper"><a href="#" class="del_button" id="del-'.$my_id.'">';
		  echo '<img src="images/icon_del.gif" border="0" />';
		  echo '</a></div>';
		  echo $contentToSave.'</li>';
		//  mysql_close($connecDB); //close db connection

	}else{
		
		//header('HTTP/1.1 500 '.mysql_error()); //display sql errors.. must not output sql errors in live mode.
		header('HTTP/1.1 500 Looks like mysql error, could not insert record!');
		exit();
	}

}
elseif(isset($_POST["recordToDelete"]) && strlen($_POST["recordToDelete"])>0 && is_numeric($_POST["recordToDelete"]))
{	//do we have a delete request? $_POST["recordToDelete"]

	//sanitize post value, PHP filter FILTER_SANITIZE_NUMBER_INT removes all characters except digits, plus and minus sign.
	$idToDelete = filter_var($_POST["recordToDelete"],FILTER_SANITIZE_NUMBER_INT); 
	
	//try deleting record using the record ID we received from POST
	if(!mysql_query("DELETE FROM datos_salidarespuesta WHERE id_datosalidarespuesta=".$idToDelete))
	{    
		//If mysql delete query was unsuccessful, output error 
		header('HTTP/1.1 500 Could not delete record!');
		exit();
	}
	//mysql_close($connecDB); //close db connection
}
else
{
	//Output error
	//header('HTTP/1.1 500 Error occurred, Could not process request!');
    exit();
}
?>