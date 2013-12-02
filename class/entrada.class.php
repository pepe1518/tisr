<?php
require 'class/base.class.php';

class entrada
{
	
	function nuevo(){
		$template = new template;
		$template -> SetTemplate('tpl/form_entrada.html');
		$template -> SetParameter('entrada','');
	    $template -> SetParameter('accion','guardar');
	    return $template -> Display();
	}
	
	function editar(){
		$template = new template;
		$template -> SetTemplate('tpl/form_entrada.html');
		$query = new query;
		$dato = $query -> getRow('*','entrada','where id_entrada= '.$_GET['id']);
		$template -> SetParameter('entrada',$dato['valor_entrada']);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardar(){
		$query = new query;
		$idsalida=$_SESSION["idsalida"];
		$arreglo['id_salida']=$_SESSION["idsalida"];
		$arreglo['valor_entrada'] = $_POST['entrada']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
	
			if($query -> dbInsert($arreglo,"entrada")){
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
		echo "<script>window.location.href='entrada.php?id=$idsalida'</script>";
	}

	function guardarEdit(){
		$query = new query;
		$idsalida=$_SESSION["idsalida"];
		$arreglo['id_salida'] = $idsalida;
		$arreglo['valor_entrada'] = $_POST['entrada']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
		if($query -> dbUpdate($arreglo,"entrada","where id_entrada= ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		echo "<script>window.location.href='entrada.php?id=$idsalida'</script>";
	}
	
	function eliminar(){
		$query = new query;
		$idsalida=$_SESSION["idsalida"];
		if($query -> dbDelete('entrada','where id_entrada='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		echo "<script>window.location.href='entrada.php?id=$idsalida'</script>";
	}

	function lista(){
		$_SESSION["idsalida"]= $_GET['id'];
		$idsalida=$_SESSION["idsalida"];
		$idproblema= $_SESSION["idproblema"];
		$template = new template;
		$query = new query;
		$template -> SetTemplate('tpl/lista.html');
     	$problema= $query->getRow('nombre_problema','problema','where id_problema = '.$idproblema);
			
	     $template -> SetParameter('titulo',"Nombre Problema  :".$problema['nombre_problema']     ."     Dato salida  :" .$_SESSION["idsalida"]);
		$lista = "";
		/*Recuperar de la BD*/
		
		$listaproveedor = $query -> getRows("*","entrada","");
		$init = ((isset($_GET['page'])== "" ? 1 :isset($_GET['page'])) - 1) * 1000;
        $listaproveedor1 = $query -> getRows("*","entrada");
		if(count($listaproveedor1)>0){
			/*<tr (class) --> referencia CSS (.) = 'nombre cabecera css (cabeza_lista)'>*/
			$lista = "<table  class=art-article border=1 cellspacing=0 cellpadding=0>
						<tr class = 'cabeza_lista'>
							<td>Entrada</td>
							<td>Editar</td>
							<td>Eliminar</td>
						</tr>";
			foreach($listaproveedor1 as $key => $valor){
				if($_SESSION["idsalida"]==$valor['id_salida'])
				{
				$lista .= '<tr>
							<td>'.$valor['valor_entrada'].'</td>
							<td><a href="#" onclick="ajax(\'formulario_nuevo\',\'entrada.php?action=editar&id='.$valor['id_entrada'].'\',\'\'); return false;"><center><img src="images/edit.gif" /></center></a></td>
							<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="entrada.php?action=eliminar&id='.$valor['id_entrada'].'"><center><img src="images/delete.gif" /></center></a></td>
						   </tr>';
				}
			}
			$lista .= "</table>";
			} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','entrada.php');
		return $template -> Display();
	}

	
	function Display()
	{
		$template = new template;
		$base= new base();
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		
		$template->SetParameter('titulo','Juez Virtual');//sets the parameters that uses the template
      
		if(isset($_SESSION['logged'])==0){
			$template -> SetParameter('formlogin',$base -> formLogin());
			  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			$template->SetParameter('contenido','Ud. no tiene privilegios de acceso, inicie sesion');
		} elseif(isset($_SESSION['logged'])==1)
		{
			$template -> SetParameter('formlogin',"<h2>Usuario: ".$_SESSION['nombre']."</h2>");
			
			$template -> SetParameter('menu',$base->menuAdmin());
				$template->SetParameter('contenido',$this -> lista());
				  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			
		}
		return $template->Display();
	}
}
?>