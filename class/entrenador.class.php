<?php
require 'class/base.class.php';
class entrenador
{
	function nuevo(){
		$query = new query;
		$usuario=$query->getRows('id_usuario,nombre_usuario','usuario');
		$listausuario= '<select name="id_usuario"><option value="">--seleccione  usuario--</option>';
		foreach($usuario as $key=>$valor){
			$listausuario.= '<option value="'.$valor['id_usuario'].'">'.$valor['nombre_usuario'].'</option>';
		}
		
			$listausuario.= '</select>';
				$rol=$query->getRows('id_colegio,nombre_colegio','colegio');
		$listarol= '<select name="id_colegio"><option value="">--seleccione rol--</option>';
		foreach($rol as $key=>$valor){
			$listarol.= '<option value="'.$valor['id_colegio'].'">'.$valor['nombre_colegio'].'</option>';
		}
			$listarol.= '</select>';
			
		$template = new template;
		$template -> SetTemplate('tpl/form_entrenador.html');
		$template -> SetParameter('entrenador',$listausuario);
			$template -> SetParameter('colegio',$listarol);
	   $template -> SetParameter('accion','guardar');
		return $template -> Display();
	}
	
	function editar(){
		$template = new template;
		$template -> SetTemplate('tpl/form_entrenador.html');
		$query = new query;
		$proveedor = $query -> getRow('*','entrenador','where id_entrenador = '.$_GET['id']);
		$template -> SetParameter('nombre',$proveedor['nombre_carrera']);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardar(){
		$query = new query;
		$arreglo['id_usuario'] = $_POST['id_usuario'];
		$arreglo['id_colegio'] = $_POST['id_colegio']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
		
			if($query -> dbInsert($arreglo,"entrenador")){
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
		echo "<script>window.location.href='entrenador.php'</script>";
	}

	function guardarEdit(){
		$query = new query;
		$arreglo['nombre_nivel'] = $_POST['nombre'];
		if($query -> dbUpdate($arreglo,"nivel","where id_nivel = ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		echo "<script>window.location.href='nivel.php'</script>";
	}
	
	function eliminar(){
		$query = new query;
		if($query -> dbDelete('entrenador','where id_entrenador='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		echo "<script>window.location.href='entrenador.php'</script>";
	}

	function lista(){
		$template = new template;
		$template -> SetTemplate('tpl/lista.html');
	$template -> SetParameter('titulo','Permisos para el sistema');
		$lista = "";
		/*Recuperar de la BD*/
		$query = new query;
		$listaproveedor = $query -> getRows("*","entrenador","");
		$init = ((isset($_GET['page'])== "" ? 1 :isset($_GET['page'])) - 1) * 1000;
        $listaproveedor1 = $query -> getRows("*","entrenador","LIMIT $init,1000");
		if(count($listaproveedor1)>0){
				$lista = "<table  class=art-article border=0 cellspacing=0 cellpadding=0>
						<tr class = 'cabeza_lista'>
							<td>Nombre</td>
							<td>Apellido Paterno</td>
							<td>Colegio</td>
							<td>Editar</td>
							<td>Eliminar</td>
						</tr>";
			foreach($listaproveedor1 as $key => $valor){
				
					$usuario= $query->getRow('nombre_usuario,apellido_paterno','usuario','where id_usuario= '.$valor['id_usuario']);
			        $colegio = $query->getRow('nombre_colegio','colegio','where id_colegio= '.$valor['id_colegio']);
				$lista .= '<tr>
							<td>'.$usuario['nombre_usuario'].'</td>
							<td>'.$usuario['apellido_paterno'].'</td>
							<td>'.$colegio['nombre_colegio'].'</td>
						
							<td><a href="#" onclick="ajax(\'formulario_nuevo\',\'usuariorol.php?action=editar&id='.$valor['id_entrenador'].'\',\'\'); return false;"><center><img src="images/edit.gif" /></center></a></td>
							<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="usuariorol.php?action=eliminar&id='.$valor['id_entrenador'].'"><center><img src="images/delete.gif" /></center></a></td>
						   </tr>';
			}
			$lista .= "</table>";
		//	$lista .= paging::navigation(count($listaproveedor),"entrenador.php",1000);
		} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','entrenador.php');
		return $template -> Display();
	}

	
	function Display()
	{
		$base = new base();
		$template = new template;
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		
		$template->SetParameter('titulo','Juez Virtual');//sets the parameters that uses the template
      
		if(isset($_SESSION['logged'])==0){
			$template -> SetParameter('formlogin',$base -> formLogin());
			  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			$template->SetParameter('contenido','Ud. no tiene privilegios de acceso, inicie sesion');
		} elseif(isset($_SESSION['logged'])==1)
		{
			$template -> SetParameter('formlogin',"<h3>Usuario: ".$_SESSION['nombre']."</h3>");
			
			$template -> SetParameter('menu',$base->menuAdmin());
				$template->SetParameter('contenido',$this -> lista());
				  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			
		}
		return $template->Display();
	}
}
?>