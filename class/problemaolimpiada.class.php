<?php
require 'class/base.class.php';
class problemaolimpiada
{

	function guardar(){
	
		$idolimpiada=$_SESSION['idolimpiada'];
			
		$sqlss= "DELETE FROM olimpiada_problema WHERE 	id_olimpiada=".$idolimpiada;
        mysql_query( $sqlss);
		
		$query = new query;
	  if (isset($_POST['myselect']))
     foreach ($_POST['myselect'] as $id)
     {
     	$arreglo['id_problema'] = $id;
     	$arreglo['id_olimpiada'] = $idolimpiada;
     
		if(!$query -> dbInsert($arreglo,"olimpiada_problema")){
		echo "<script>alert('Error al registrar los datos');</script>";
				
		}
          	
     }
     echo "<script>alert('Datos registrados exitosamente');</script>";
		
		echo "<script>window.location.href='problemaolimpiada.php?id=$idolimpiada'</script>";
	}
	


	function lista(){
		
		$_SESSION['idolimpiada']=$_GET['id'];
			
		$query = new query;
			
		$area=$query->getRows('id_problema,nombre_problema','problema');
		
		$listaproblema = ' <select id=custom-headers multiple=multiple name="myselect[]">';
		foreach($area as $key=>$valor){
			
			$problemaolimpiada=$query->getRows('id_problema,id_olimpiada','olimpiada_problema');
			$proveedor = $query -> getRow('*','olimpiada_problema','where id_problema = '.$valor['id_problema']);
			echo $proveedor['id_problema']."gsdfg";
			if($proveedor['id_problema']!=0)
			{
			$listaproblema.= '<option value="'.$valor['id_problema'].'"selected>'.$valor['nombre_problema'].'</option>';
			
			}else {
			$listaproblema.= '<option value="'.$valor['id_problema'].'">'.$valor['nombre_problema'].'</option>';
			}
		}
		
		$listaproblema.= '</select>';
		
		$template = new template;
		$template -> SetTemplate('tpl/form_problemaolimpiada.html');
		$template -> SetParameter('problema',$listaproblema);
				
		$template -> SetParameter('accion','guardar');
		return $template -> Display();
	
		
	}

	function Display()
	{
		$base = new base();
		$template = new template;
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		
		$template->SetParameter('titulo','');//sets the parameters that uses the template
      
		if(isset($_SESSION['logged'])==0){
			$template -> SetParameter('formlogin',$base -> formLogin());
			  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			$template->SetParameter('contenido','Ud. no tiene privilegios de acceso, inicie sesion');
		} elseif(isset($_SESSION['logged'])==1)
		{
			$template -> SetParameter('formlogin',"<h2>Usuario: ".$_SESSION['nombre']."</h2>");
			  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			$template -> SetParameter('menu',$base->menuAdmin());
				$template->SetParameter('contenido',$this -> lista());
			
		}
		return $template->Display();
	}
}
?>