<?php
require 'class/base.class.php';
class usuario
{

	
	function editar(){
		
		
		if ( isset($_GET['id']))
		{
		$query = new query;
		
		
			
		$usuario = $query -> getRow('*','usuario','where id_usuario = '.$_GET['id']);
		$template -> SetParameter('nombre',$usuario['nombre_usuario']);
		$template -> SetParameter('apellido',$usuario['apellido_usuario']);
		$template -> SetParameter('tipo',$listatipo);
	
		$template -> SetParameter('telefono',$usuario['telefono_usuario']);
		$template -> SetParameter('email',$usuario['email_usuario']);
		$template -> SetParameter('login',$usuario['login_usuario']);
		$template -> SetParameter('password',$usuario['password_usuario']);
		$template -> SetParameter('direccion',$usuario['direccion_usuario']);
		
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
		}
	}
	
	
	function guardar(){
		
		$query = new query;
		$arreglo['id_rol'] = 2;
		$arreglo['id_colegio'] = $_POST['id_colegio'];
		$arreglo['nombre_usuario'] = $_POST['nombre'];
		$arreglo['apellido_paterno'] = $_POST['apellidop'];
		$arreglo['apellido_materno'] = $_POST['apellidom'];
		$arreglo['fecha_nacimiento'] = $_POST['fechanacimiento'];
		$arreglo['ci_usuario'] = $_POST['ci'];
		$arreglo['email_usuario'] = $_POST['email'];
		$arreglo['login'] = $_POST['login'];
		$arreglo['password'] = $_POST['pasword'];
		if($query -> dbInsert($arreglo,"usuario")){
		
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
		echo "<script>window.location.href='index.php'</script>";
	}
	
	
	function guardarEdit(){
		$query = new query;
		$arreglo['nombre_usuario'] = $_POST['nombre'];
		$arreglo['apellido_usuario'] = $_POST['apellido'];
		$arreglo['direccion_usuario'] = $_POST['direccion'];
		$arreglo['telefono_usuario'] = $_POST['telefono'];
		$arreglo['email_usuario'] = $_POST['email'];
		$arreglo['login_usuario'] = $_POST['login'];
		$arreglo['password_usuario'] = $_POST['password'];

			if($query -> dbUpdate($arreglo,"usuario","where id_usuario = ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		
		echo "<script>window.location.href='usuario.php'</script>";
	}
	
	function eliminar(){
		$query = new query;
			if($query -> dbDelete('usuario','where id_usuario='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		
		
		echo "<script>window.location.href='usuario.php'</script>";
	}

	function lista(){
		
		
		$query = new query;
		$area=$query->getRows('id_colegio,nombre_colegio','colegio');
		$listanivel = '<select name="id_colegio"><option value="">--seleccione colegio--</option>';
		foreach($area as $key=>$valor){
			$listanivel.= '<option value="'.$valor['id_colegio'].'">'.$valor['nombre_colegio'].'</option>';
		}
		$listanivel.= '</select>';
		
		$template = new template;
		$template -> SetTemplate('tpl/form_usuario.html');
		$template -> SetParameter('nombre','');
		$template -> SetParameter('apellidopaterno','');
		$template -> SetParameter('apellidomaterno','');
		$template -> SetParameter('fechanacimiento','');
		$template -> SetParameter('ci','');
	
		$template -> SetParameter('email','');
		$template -> SetParameter('colegio',$listanivel);
		$template -> SetParameter('login','');
		$template -> SetParameter('password','');
		
		$template -> SetParameter('accion','guardar');
		return $template -> Display();
	
		
	}

	function Display()
	{
		
		$base= new base();
		$template = new template;
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		$template->SetParameter('titulo','');//sets the parameters that uses the template
		$template->SetParameter('menu',$base -> menuAdmin());
		$template->SetParameter('contenido',$this -> lista());
		$template -> SetParameter('formlogin',$base -> formLogin());
		
		
		return $template->Display();
	}
}
?>