<?php
require  'lib/includeLibs.php';

function comprobar_disponibilidad($name)		
//Esta pequeña funcion usa la clase mysql.php para conectarse a la base de datos y realizar la consulta
{
	
	$query = "SELECT u.id_usuario FROM usuario  u WHERE login='".$name."'";
    $results = mysql_query( $query) or die('ok');

    if(mysql_num_rows(@$results) > 0)
           return false;
        else
           return true;
                
       
}

function comprobar_ci($name)		
//Esta pequeña funcion usa la clase mysql.php para conectarse a la base de datos y realizar la consulta
{
	
	$query = "SELECT u.id_usuario FROM usuario  u WHERE ci_usuario='".$name."'";
    $results = mysql_query( $query) or die('ok');

    if(mysql_num_rows(@$results) > 0)
           return false;
        else
           return true;
                
       
}

function comprobar_email($name)		
//Esta pequeña funcion usa la clase mysql.php para conectarse a la base de datos y realizar la consulta
{
	
	$query = "SELECT u.id_usuario FROM usuario  u WHERE email_usuario='".$name."'";
    $results = mysql_query( $query) or die('ok');

    if(mysql_num_rows(@$results) > 0)
           return false;
        else
           return true;
                
       
}


if ($_POST['login'] != "")						   //Si el campo usuario tiene algo
{
    if (!comprobar_disponibilidad($_POST['login'])) // Usuario resgistrado
    {
        echo "0";
    }
    else											  // Usuario No registrado
    {
        echo "1";
    }
}

if ($_POST['email'] != "")						   //Si el campo usuario tiene algo
{
    if (!comprobar_email($_POST['email'])) // Usuario resgistrado
    {
        echo "0";
    }
    else											  // Usuario No registrado
    {
        echo "1";
    }
}

if ($_POST['ci'] != "")						   //Si el campo usuario tiene algo
{
    if (!comprobar_ci($_POST['ci'])) // Usuario resgistrado
    {
        echo "0";
    }
    else											  // Usuario No registrado
    {
        echo "1";
    }
}
?>
