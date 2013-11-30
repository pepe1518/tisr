<?php

require 'class/base.class.php';
class index
{

    
    function viewPopUp()
    {
        $viewer = "<div>I'm a ajax popup <br /> click <a href=\"#\" onclick=\"closeajax('popup'); return false;\" >HERE</a> to close</div>";
        return "<div style=\"position: absolute; background-color: #ffffcc;\">".$viewer."</div>";
    }
	
	function logear(){
		$login = $_POST['login'];
		$password = $_POST['password'];
		$libLogin = new login;
		$idusuario = $libLogin -> validate($login,$password);
		if($idusuario == false){
			$_SESSION['logged'] = 0;
			echo "<script>window.location.href='index.php?login=error'</script>";
		} else {
			$libLogin -> loginUser($idusuario);
			echo "<script>window.location.href='index.php?login=ok'</script>";
		}
	}
	
	function salir(){
	   session_destroy();
	   echo"<script>window.location.href='index.php'</script>";
	}
    
	
	function Display()
	{
		$bases= new base();
		$template = new template;
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		$template->SetParameter('titulo','Sistema');//sets the parameters that uses the template
       
        $template->SetParameter('contenido',$bases->showContent());
        if(isset($_SESSION['logged']))
        {
		if($_SESSION['logged']==0){
		     $template -> setParameter('formlogin',$bases -> formLogin()); /*set parameter unicamente para corchetes*/
		     $template -> SetParameter('menu',$bases ->menuAdmin());
		     $template -> SetParameter('izquierdo',$bases ->menuizquierdo());
		    
		} elseif($_SESSION['logged']==1){
			$template -> SetParameter('menu',$bases->menuAdmin());
		    $template -> SetParameter('formlogin',"<h2> Usuario: ".$_SESSION['nombre']."<h2>"); /* variable de sesion nombre usuario*/
		   $template -> SetParameter('izquierdo',$bases ->menuizquierdo()); 
		}
        }else
        {
            $template -> setParameter('formlogin',$bases -> formLogin()); /*set parameter unicamente para corchetes*/
		     $template -> SetParameter('menu',$bases ->menuAdmin());
		     $template -> SetParameter('izquierdo',$bases ->menuizquierdo());
		 
        }
		return $template->Display();
	}
}
?>