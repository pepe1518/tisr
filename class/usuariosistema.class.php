<?php
require 'class/base.class.php';
class usuariosistema
{
	function nuevo(){
		
		$query = new query;
		$area=$query->getRows('id_colegio,nombre_colegio','colegio');
		$listanivel = '<select name="id_colegio"><option value="">--seleccione colegio--</option>';
		foreach($area as $key=>$valor){
			$listanivel.= '<option value="'.$valor['id_colegio'].'">'.$valor['nombre_colegio'].'</option>';
		}
		$listanivel.= '</select>';
		
		$query = new query;
		$area=$query->getRows('id_rol,nombre_rol','rol');
		$listatipo = '<select name="id_rol"><option value="">--seleccione rol--</option>';
		foreach($area as $key=>$valor){
			$listatipo.= '<option value="'.$valor['id_rol'].'">'.$valor['nombre_rol'].'</option>';
		}
		$listatipo .= '</select>';
		$template = new template;
		$template -> SetTemplate('tpl/form_usuariosistema.html');
		$template -> SetParameter('nombre','');
		$template -> SetParameter('apellidop','');
		$template -> SetParameter('apellidom','');
		$template -> SetParameter('fechanacimiento','');
		$template -> SetParameter('ci','');
	
		$template -> SetParameter('email','');
	    $template -> SetParameter('colegio',$listanivel);
	    $template -> SetParameter('rol',$listatipo);
		$template -> SetParameter('accion','guardar');
		return $template -> Display();
	}
	
	function editar(){
		
		
		$query = new query;
		$area=$query->getRows('id_colegio,nombre_colegio','colegio');
		$listanivel = '<select name="id_colegio"><option value="">--seleccione colegio--</option>';
		foreach($area as $key=>$valor){
			$listanivel.= '<option value="'.$valor['id_colegio'].'">'.$valor['nombre_colegio'].'</option>';
		}
		$listanivel.= '</select>';
		
		$query = new query;
		$area=$query->getRows('id_rol,nombre_rol','rol');
		$listatipo = '<select name="id_rol"><option value="">--seleccione rol--</option>';
		foreach($area as $key=>$valor){
			$listatipo.= '<option value="'.$valor['id_rol'].'">'.$valor['nombre_rol'].'</option>';
		}
		$listatipo .= '</select>';
		
		$query = new query;
		$template = new template;
		$template -> SetTemplate('tpl/form_usuariosistema.html');
			
		$usuario = $query -> getRow('*','usuario','where id_usuario ='.$_GET['id']);
		
		$template -> SetTemplate('tpl/form_usuariosistema.html');
		$template -> SetParameter('nombre',$usuario['nombre_usuario']);
		$template -> SetParameter('apellidop',$usuario['apellido_paterno']);
		$template -> SetParameter('apellidom',$usuario['apellido_materno']);
		$template -> SetParameter('fechanacimiento',$usuario['fecha_nacimiento']);
		$template -> SetParameter('ci',$usuario['ci_usuario']);
	
		$template -> SetParameter('email',$usuario['email_usuario']);
	    $template -> SetParameter('colegio',$listanivel);
	    $template -> SetParameter('rol',$listatipo);
	
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardarEdit(){
		$query = new query;
		$arreglo['id_rol'] = $_POST['id_rol'];
		$arreglo['id_colegio'] = $_POST['id_colegio'];
		$arreglo['nombre_usuario'] = $_POST['nombre'];
		$arreglo['apellido_paterno'] = $_POST['apellidop'];
		$arreglo['apellido_materno'] = $_POST['apellidom'];
		$arreglo['fecha_nacimiento'] = $_POST['fechanacimiento'];
		$arreglo['ci_usuario'] = $_POST['ci'];
		$arreglo['email_usuario'] = $_POST['email'];
	//	$arreglo['login'] =  $_POST['nombre'];
	//	$arreglo['password'] = $_POST['ci'];

			if($query -> dbUpdate($arreglo,"usuario","where id_usuario = ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		
		echo "<script>window.location.href='usuariosistema.php'</script>";
	}
	function guardar(){
		
		$query = new query;
		$arreglo['id_rol'] = $_POST['id_rol'];
		$arreglo['id_colegio'] = $_POST['id_colegio'];
		$arreglo['nombre_usuario'] = $_POST['nombre'];
		$arreglo['apellido_paterno'] = $_POST['apellidop'];
		$arreglo['apellido_materno'] = $_POST['apellidom'];
		$arreglo['fecha_nacimiento'] = $_POST['fechanacimiento'];
		$arreglo['ci_usuario'] = $_POST['ci'];
		$arreglo['email_usuario'] = $_POST['email'];
		$arreglo['login'] =  $_POST['nombre'];
		$arreglo['password'] = $_POST['ci'];
		if($query -> dbInsert($arreglo,"usuario")){
		
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
		echo "<script>window.location.href='usuariosistema.php'</script>";
	}
	
	
	function eliminar(){
		$query = new query;
				if($query -> dbDelete('usuario','where id_usuario='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		
		
		echo "<script>window.location.href='usuariosistema.php'</script>";
	}
	function lista(){
		$template = new template;
		$template -> SetTemplate('tpl/lista.html');
		$template -> SetParameter('titulo','Lista de los usuarios');
		$lista = "";
		/*Recuperar de la BD*/
		$query = new query;
		$listaCliente1 = $query -> getRows("*","usuario","");
		if(count($listaCliente1)>0){
			$lista = "<table class=art-article border=1 cellspacing=0 cellpadding=0 >
						<tr class = 'cabeza_lista'>
							<td>Nombre</td>
							<td>Apellido Paterno</td>
							<td>Apellido Materno</td>
							<td>Colegio</td>
					        <td>Rol</td>
							<td>Email</td>
					        <td>Ci</td>
					      	<td>Editar</td>
						    <td>Eliminar</td>
						</tr>";
			foreach($listaCliente1 as $key => $valor){
				$roluser = $query->getRow('nombre_rol','rol','where id_rol= '.$valor['id_rol']);
				//if($roluser['id_rol']!=0)
				//{
				//$tipouser = $query->getRow('nombre_rol','rol','where id_rol= '.$roluser['id_rol']);
				//}
				//echo $tipouser['nombre_rol'];
				$colegio = $query->getRow('nombre_colegio','colegio','where id_colegio= '.$valor['id_colegio']);
				$lista .= '<tr>
							<td>'.$valor['nombre_usuario'].'</td>
							<td>'.$valor['apellido_paterno'].'</td>
							<td>'.$valor['apellido_materno'].'</td>
								<td>'.$colegio['nombre_colegio'].'</td>
								<td>'.$roluser['nombre_rol'].'</td>
						
							<td>'.$valor['email_usuario'].'</td>
							<td>'.$valor['ci_usuario'].'</td>
						
					     			
							<td><a href="#" onclick="ajax(\'formulario_nuevo\',\'usuariosistema.php?action=editar&id='.$valor['id_usuario'].'\',\'\'); return false;"><center><img src="images/edit.gif" /></center></a></td>
							<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="usuariosistema.php?action=eliminar&id='.$valor['id_usuario'].'"><center><img src="images/delete.gif" /></center></a></td>
						   </tr>';
			}
			$lista .= "</table>";
			} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','usuariosistema.php');
		return $template -> Display();
	}

	
		
		 	

	function Display()
	{
		$bases= new base();
		$template = new template;
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		$template->SetParameter('titulo','');//sets the parameters that uses the template
      
		if(isset($_SESSION['logged'])==0){
			$template -> SetParameter('formlogin',$bases -> formLogin());
			$template -> SetParameter('menu',$bases->menuAdmin());
			  $template -> SetParameter('izquierdo',$bases ->menuizquierdo());
			$template->SetParameter('contenido','Ud. no tiene privilegios de acceso, inicie sesion');
		} elseif(isset($_SESSION['logged'])==1){
			$template -> SetParameter('formlogin',"<h2>Usuario: ".$_SESSION['nombre']."</h2>");
			$template -> SetParameter('menu',$bases ->menuAdmin());
			  $template -> SetParameter('izquierdo',$bases ->menuizquierdo());
		    $template->SetParameter('contenido',$this -> lista());
		  
		}
		return $template->Display();
	}
}
?>