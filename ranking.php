<?php
require_once('lib/includeLibs.php');
require_once('class/ranking.class.php');
$class = new ranking;
if(isset($_GET['action']))
{
switch($_GET['action']) 
{
    case "nuevo" :
        echo $class->nuevo();
        exit(); /*solo cuando se realiza mediante ajax*/
    break;
    case "editar" :
        echo $class->editar();
        exit(); /*solo cuando se realiza mediante ajax*/
    break;
	case "guardar" :
        echo $class->guardar();
    break;
	case "guardarEdit" :
        echo $class->guardarEdit();
    break;
	case "eliminar" :
        echo $class->eliminar();
    break;
}
}

echo $class->Display();