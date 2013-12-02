<?php
require 'class/base.class.php';

class dato
{
	
	function nuevo(){
		$template = new template;
		$template -> SetTemplate('tpl/form_dato.html');
		$template -> SetParameter('entrada','');
		$template -> SetParameter('salida','');
	    $template -> SetParameter('accion','guardar');
	    return $template -> Display();
	}
	
	function editar(){
		$template = new template;
		$template -> SetTemplate('tpl/form_dato.html');
		$query = new query;
		$dato = $query -> getRow('*','dato','where id_dato = '.$_GET['id']);
		$template -> SetParameter('entrada',$dato['dato_entrada']);
		$template -> SetParameter('salida',$dato['dato_salida']);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardar(){
		$query = new query;
		$idproblema=$_SESSION["idproblema"];
		$arreglo['id_problema']=$_SESSION["idproblema"];
		$arreglo['dato_entrada'] = $_POST['entrada']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
		$arreglo['dato_salida'] = $_POST['salida'];
	
			if($query -> dbInsert($arreglo,"dato")){
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
		echo "<script>window.location.href='dato.php?id=$idproblema'</script>";
	}

	function guardarEdit(){
		$query = new query;
		$idproblema=$_SESSION["idproblema"];
		$arreglo['id_problema'] = $_SESSION["idproblema"];
		$arreglo['dato_entrada'] = $_POST['entrada']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
		$arreglo['dato_salida'] = $_POST['salida'];
		if($query -> dbUpdate($arreglo,"dato","where id_dato= ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		echo "<script>window.location.href='dato.php?id=$idproblema'</script>";
	}
	
	function eliminar(){
		$query = new query;
		$idproblema=$_SESSION["idproblema"];
		if($query -> dbDelete('dato','where id_dato='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		echo "<script>window.location.href='dato.php?id=$idproblema'</script>";
	}

	function lista(){
		$_SESSION["idproblema"]= $_GET['id'];
		$idproblema=$_SESSION["idproblema"];
		$template = new template;
		$query = new query;
		$template -> SetTemplate('tpl/lista.html');
     	$problema= $query->getRow('nombre_problema','problema','where id_problema = '.$idproblema);
			
	     $template -> SetParameter('titulo',$problema['nombre_problema']);
		$lista = "";
		/*Recuperar de la BD*/
		
		$listaproveedor = $query -> getRows("*","dato","");
		$init = ((isset($_GET['page'])== "" ? 1 :isset($_GET['page'])) - 1) * 1000;
        $listaproveedor1 = $query -> getRows("*","dato");
		if(count($listaproveedor1)>0){
			/*<tr (class) --> referencia CSS (.) = 'nombre cabecera css (cabeza_lista)'>*/
			$lista = "<table  class=art-article border=1 cellspacing=0 cellpadding=0>
						<tr class = 'cabeza_lista'>
							<td>Entrada</td>
							<td>Salida</td>
							<td>Editar</td>
							<td>Eliminar</td>
						</tr>";
			foreach($listaproveedor1 as $key => $valor){
				if($idproblema==$valor['id_problema'])
				{
				$lista .= '<tr>
							<td>'.$valor['dato_entrada'].'</td>
							<td>'.$valor['dato_salida'].'</td>
							<td><a href="#" onclick="ajax(\'formulario_nuevo\',\'dato.php?action=editar&id='.$valor['id_dato'].'\',\'\'); return false;"><center><img src="images/edit.gif" /></center></a></td>
							<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="dato.php?action=eliminar&id='.$valor['id_dato'].'"><center><img src="images/delete.gif" /></center></a></td>
						   </tr>';
				}
			}
			$lista .= "</table>";
			} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','dato.php');
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