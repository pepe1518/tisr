<?php
require 'class/base.class.php';

class salida
{
	
	function nuevo(){
		$template = new template;
		$template -> SetTemplate('tpl/form_salida.html');
		$template -> SetParameter('salida','');
		
	    $template -> SetParameter('accion','guardar');
	    return $template -> Display();
	}
	
	function editar(){
		$template = new template;
		$template -> SetTemplate('tpl/form_salida.html');
		$query = new query;
		$dato = $query -> getRow('*','salida','where id_salida= '.$_GET['id']);
		$template -> SetParameter('salida',$dato['valor_salida']);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardar(){
		$query = new query;
		$idproblema=$_SESSION["idproblema"];
		$arreglo['id_problema']=$_SESSION["idproblema"];
		$arreglo['valor_salida'] = $_POST['salida']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
				if($query -> dbInsert($arreglo,"salida")){
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
		echo "<script>window.location.href='salida.php?id=$idproblema'</script>";
	}

	function guardarEdit(){
		$query = new query;
		$idproblema=$_SESSION["idproblema"];
		$arreglo['id_problema'] = $_SESSION["idproblema"];
		$arreglo['valor_salida'] = $_POST['salida']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
		if($query -> dbUpdate($arreglo,"salida","where id_salida= ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		echo "<script>window.location.href='salida.php?id=$idproblema'</script>";
	}
	
	function eliminar(){
		$query = new query;
		$idproblema=$_SESSION["idproblema"];
		if($query -> dbDelete('salida','where id_salida='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		echo "<script>window.location.href='salida.php?id=$idproblema'</script>";
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
		
		$listaproveedor = $query -> getRows("*","salida","");
		$init = ((isset($_GET['page'])== "" ? 1 :isset($_GET['page'])) - 1) * 1000;
        $listaproveedor1 = $query -> getRows("*","salida");
		if(count($listaproveedor1)>0){
			/*<tr (class) --> referencia CSS (.) = 'nombre cabecera css (cabeza_lista)'>*/
			$lista = "<table  class=art-article border=1 cellspacing=0 cellpadding=0>
						<tr class = 'cabeza_lista'>
							<td>Salida</td>
							<td>Editar</td>
							<td>Eliminar</td>
							<td>Agregar las Salidas</td>
						</tr>";
			foreach($listaproveedor1 as $key => $valor){
				if($idproblema==$valor['id_problema'])
				{
				$lista .= '<tr>
							<td>'.$valor['valor_salida'].'</td>
							<td><a href="#" onclick="ajax(\'formulario_nuevo\',\'dato.php?action=editar&id='.$valor['id_salida'].'\',\'\'); return false;"><center><img src="images/edit.gif" /></center></a></td>
							<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="dato.php?action=eliminar&id='.$valor['id_salida'].'"><center><img src="images/delete.gif" /></center></a></td>
                        <td><a  href="entrada.php?id='.$valor['id_salida'].'"  TARGET="_self" ><center><img src="images/agregarboton.png" /></center></a></td>
					  
							</tr>';
				}
			}
			$lista .= "</table>";
			} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','salida.php');
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