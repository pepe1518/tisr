<?php
require 'class/base.class.php';
class colegio
{
	function nuevo(){
		$template = new template;
		
		$template -> SetTemplate('tpl/from_colegio.html');
		$template -> SetParameter('nombre','');
	   $template -> SetParameter('accion','guardar');
		return $template -> Display();
	}
	
	function editar(){
		$template = new template;
		$template -> SetTemplate('tpl/form_colegio.html');
		$query = new query;
		$proveedor = $query -> getRow('*','colegio','where id_colegio = '.$_GET['id']);
		$template -> SetParameter('nombre',$proveedor['nombre_colegio']);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardar(){
		$query = new query;
		$arreglo['nombre_colegio'] = $_POST['nombre']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
		
			if($query -> dbInsert($arreglo,"colegio")){
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
		echo "<script>window.location.href='colegio.php'</script>";
	}

	function guardarEdit(){
		$query = new query;
		$arreglo['nombre_colegio'] = $_POST['nombre'];
		if($query -> dbUpdate($arreglo,"colegio","where id_colegio= ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		echo "<script>window.location.href='colegio.php'</script>";
	}
	
	function eliminar(){
		$query = new query;
		if($query -> dbDelete('colegio','where id_colegio='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		echo "<script>window.location.href='colegio.php'</script>";
	}

	function lista(){
		$paginador= new paging();
		$template = new template;
		$template -> SetTemplate('tpl/lista.html');
	$template -> SetParameter('titulo','Lista de Colegios');
		$lista = "";
		/*Recuperar de la BD*/
		$query = new query;
		$listaproveedor = $query -> getRows("*","colegio","");
		$init = ((isset($_GET['page'])== "" ? 1 :isset($_GET['page'])) - 1) * 1000;
        $listaproveedor1 = $query -> getRows("*","colegio","LIMIT $init,1000");
		if(count($listaproveedor1)>0){
			/*<tr (class) --> referencia CSS (.) = 'nombre cabecera css (cabeza_lista)'>*/
			$lista = "<table class=art-article border=1 cellspacing=0 cellpadding=0>
						<tr class = 'cabeza_lista'>
							<td>Nombre</td>
							<td>Editar</td>
							<td>Eliminar</td>
						</tr>";
			foreach($listaproveedor1 as $key => $valor){
				$lista .= '<tr>
							<td>'.$valor['nombre_colegio'].'</td>
						
							<td><a href="colegio.php?action=nuevo" target="_self"><center><img src="images/edit.gif" /></center></a></td>
						
							<td><a href="#" onclick="ajax(\'formulario_nuevo\',\'colegio.php?action=editar&id='.$valor['id_colegio'].'\',\'\'); return false;"><center><img src="images/edit.gif" /></center></a></td>
							<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="colegio.php?action=eliminar&id='.$valor['id_colegio'].'"><center><img src="images/delete.gif" /></center></a></td>
						   </tr>';
			}
			$lista .= "</table>";
				} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','colegio.php');
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
			  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			$template -> SetParameter('menu',$base->menuAdmin());
				$template->SetParameter('contenido',$this -> lista());
			
		}
		return $template->Display();
	}
}
?>