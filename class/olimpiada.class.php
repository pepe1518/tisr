<?php
require 'class/base.class.php';
class olimpiada
{
	function nuevo(){
		$template = new template;
		$template -> SetTemplate('tpl/form_olimpiada.html');
		$template -> SetParameter('nombre','');
		$template -> SetParameter('fechainicio','');
		$template -> SetParameter('fechafinal','');
		$template -> SetParameter('descripcion','');
	   $template -> SetParameter('accion','guardar');
		return $template -> Display();
	}
	
	function editar(){
		$template = new template;
		$template -> SetTemplate('tpl/form_olimpiada.html');
		$query = new query;
		$proveedor = $query -> getRow('*','olimpiada','where id_olimpiada = '.$_GET['id']);
		$template -> SetParameter('nombre',$proveedor['nombre_olimpiada']);
			$template -> SetParameter('fechainicio',$proveedor['fecha_inicio']);
				$template -> SetParameter('fechafinal',$proveedor['fecha_fin']);
		$template -> SetParameter('descripcion',$proveedor['descripcion_olimpiada']);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardar(){
		$query = new query;
		$arreglo['nombre_olimpiada'] = $_POST['nombre']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
		$arreglo['fecha_inicio'] = $_POST['fechainicio'];
		$arreglo['fecha_fin'] = $_POST['fechafinal'];
		$arreglo['descripcion_olimpiada'] = $_POST['descripcion'];
			if($query -> dbInsert($arreglo,"olimpiada")){
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
		echo "<script>window.location.href='olimpiada.php'</script>";
	}

	function guardarEdit(){
		$query = new query;
		$arreglo['nombre_olimpiada'] = $_POST['nombre'];
		$arreglo['fecha_inicio'] = $_POST['fechainicio'];
		$arreglo['fecha_fin'] = $_POST['fechafinal'];
		$arreglo['descripcion_olimpiada'] = $_POST['descripcion'];
		if($query -> dbUpdate($arreglo,"olimpiada","where id_olimpiada = ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		echo "<script>window.location.href='olimpiada.php'</script>";
	}
	
	function eliminar(){
		$query = new query;
		if($query -> dbDelete('olimpiada','where id_olimpiada='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		echo "<script>window.location.href='olimpiada.php'</script>";
	}

	function lista(){
		$template = new template;
		$template -> SetTemplate('tpl/lista.html');
	$template -> SetParameter('titulo','Lista de Olimpiadas');
		$lista = "";
		/*Recuperar de la BD*/
		$query = new query;
		$listaproveedor = $query -> getRows("*","olimpiada","");
		$init = ((isset($_GET['page'])== "" ? 1 :isset($_GET['page'])) - 1) * 1000;
        $listaproveedor1 = $query -> getRows("*","olimpiada","LIMIT $init,1000");
		if(count($listaproveedor1)>0){
			/*<tr (class) --> referencia CSS (.) = 'nombre cabecera css (cabeza_lista)'>*/
			$lista = "<table  class=art-article border=1 cellspacing=0 cellpadding=0>
						<tr class = 'cabeza_lista'>
							<td>Nombre</td>
							  <td>Fecha inicio</td>
							    <td>fecha final</td>
					        <td>Descripcion</td>
							<td>Editar</td>
							<td>Eliminar</td>
						</tr>";
			foreach($listaproveedor1 as $key => $valor){
				$lista .= '<tr>
							<td>'.$valor['nombre_olimpiada'].'</td>
							<td>'.$valor['fecha_inicio'].'</td>
							<td>'.$valor['fecha_fin'].'</td>
					     	<td>'.$valor['descripcion_olimpiada'].'</td>
						
							<td><a href="#" onclick="ajax(\'formulario_nuevo\',\'olimpiada.php?action=editar&id='.$valor['id_olimpiada'].'\',\'\'); return false;"><center><img src="images/edit.gif" /></center></a></td>
							<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="olimpiada.php?action=eliminar&id='.$valor['id_olimpiada'].'"><center><img src="images/delete.gif" /></center></a></td>
						   </tr>';
			}
			$lista .= "</table>";
			//$lista .= paging::navigation(count($listaproveedor),"olimpiada.php",20);
		} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','olimpiada.php');
		return $template -> Display();
	}

	function formLogin(){
		$template = new template;
		$template -> SetTemplate('tpl/form_login.html');
		return $template -> Display();
	}
	
	
	
    function menuInicial(){
		$template = new template;
		$template -> SetTemplate('tpl/menu_admin.html');
		return $template -> Display();
	}
    /*funcion salir(){ session_destroy(); }*/
	function Display()
	{
		$base= new base();
		$template = new template;
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		
		$template->SetParameter('titulo','Juez Virtual');//sets the parameters that uses the template
      
		if(isset($_SESSION['logged'])==0){
			$template -> SetParameter('formlogin',$this -> formLogin());
			  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			$template->SetParameter('contenido','Ud. no tiene privilegios de acceso, inicie sesion');
		} elseif(isset($_SESSION['logged'])==1)
		{
			$template -> SetParameter('formlogin',"<h2>Usuario: ".$_SESSION['nombre']."</h2>");
			
			$template -> SetParameter('menu',$this->menuInicial());
				$template->SetParameter('contenido',$this -> lista());
				  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			
		}
		return $template->Display();
	}
}
?>