<?php
require_once('lib/includeLibs.php');
require_once('class/index.class.php');

$class = new index;
if(isset($_GET['action']))
switch($_GET['action']) {
    case "viewPopUp" :
        echo $class->viewPopUp();
        exit(); /*solo cuando se realiza mediante ajax*/
    break;
	
	case "logear" :
        echo $class->logear();
    break;
	
	case "salir" :
        echo $class->salir();
    break;
}

echo $class->Display();
?>