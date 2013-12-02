<?php
require 'class/base.class.php';
class rol
{

	const estado='AC';
	
function nuevo()
	{	
		
		$listactivo = '<select name="id_activo"><option value="NA">No activo</option>';
			$listactivo.= '<option value="AC"> Activo'.'</option>';
			
		
		$listactivo.= '</select>';
		
		$template = new template;
		$template -> SetTemplate('tpl/form_rol.html');
		$template -> SetParameter('nombre','');
		$template -> SetParameter('activo',$listactivo);
		$template -> SetParameter('descripcion','');
		$template -> SetParameter('accion','guardar');
		return $template -> Display();
	}
	
	function editar(){
		
		$listactivo = '<select name="id_activo"><option value="NA">No activo</option>';
		
			$listactivo.= '<option value="AC"> Activo'.'</option>';
			
		$listactivo.= '</select>';
		
		$template = new template;
		$template -> SetTemplate('tpl/form_rol.html');
		$query = new query;
		$rol = $query -> getRow('*','rol','where id_rol = '.$_GET['id']);
		$template -> SetParameter('nombre',$rol['nombre_rol']);
		$template -> SetParameter('activo',$listactivo);
		$template -> SetParameter('descripcion',$rol['descripcion_rol']);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
function guardar(){
		
		$permitidos = '/^[A-Z ��������������]{1,50}$/i';
		$nombretipo=$_POST['nombre'];
if(preg_match($permitidos,$_POST['nombre']))
{

$consulta= "SELECT * FROM rol  WHERE nombre_rol='".$_POST['nombre']."'";
$resultado= mysql_query($consulta);
$fila = mysql_fetch_array($resultado);
$valores="";
do {
$valores=$fila["nombre_rol"];
} while ($fila = mysql_fetch_array($resultado));

if($valores=="")
{
		$query = new query;
		$arreglo['nombre_rol'] = $_POST['nombre'];
		
			
		
		
	    if($_POST['id_activo']=="AC")
		   {
			$arreglo['activo'] ="AC";
			$arreglo['descripcion_rol'] = $_POST['descripcion'];
			$querys = new query;
			$listarol = $querys -> getRows("*","rol","");
			foreach($listarol  as $key => $valor)
			{
				if($valor['activo']=='AC')
				{
					$queryr = new query;
					$arreglos['activo']='NA';
					$queryr -> dbUpdate($arreglos,"rol","where id_rol = ".$valor['id_rol']);
				}
			}
			if($query -> dbInsert($arreglo,"rol")){
				echo "<script>alert('Datos registrados exitosamente');</script>";
			} else {
				echo "<script>alert('Error al registrar los datos');</script>";
			}
			
			echo "<script>window.location.href='rol.php'</script>";
			
		}else {
		
			$arreglo['activo'] ='NA';
			$arreglo['descripcion_rol'] = $_POST['descripcion'];
		
			if($query -> dbInsert($arreglo,"rol")){
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
		
		echo "<script>window.location.href='rol.php'</script>";
		}
		}else{
			
			echo "<script>alert('Error ya existe un registro con este nombre');</script>";
		}
		}else {
			echo "<script>alert('Error El nombre de tipo de Usuario tiene caracteres no permitidos:$nombretipo');</script>";
		}
	}


	
function guardarEdit(){
		$query = new query;
		$permitidos = '/^[A-Z ��������������]{1,50}$/i';
		$nombretipo=$_POST['nombre'];
		if(preg_match($permitidos,$_POST['nombre']))
		{
        $nombrevar=$_POST['nombre'];
        $idvar=$_GET['id'];
		$consulta = "SELECT * FROM rol WHERE id_rol=$idvar and nombre_rol='".$_POST['nombre']."'";
       // $consulta = "SELECT * FROM tipo_usuario WHERE nombre_tipousuario='.$nombrevar.'";
		$resultado= mysql_query($consulta);
		$fila = mysql_fetch_array($resultado);
		$valores="";
		do {
			$valores=$fila["nombre_rol"];
		} while ($fila = mysql_fetch_array($resultado));
		
		if($valores=="")
		{
			
			$consult= "SELECT * FROM rol  WHERE nombre_rol='".$_POST['nombre']."'";
			$resultad= mysql_query($consult);
			$fila = mysql_fetch_array($resultad);
			$valore="";
			do {
				$valore=$fila["nombre_rol"];
			} while ($fila = mysql_fetch_array($resultad));
			
			if($valore=="")
			{
				
				if($_POST['id_activo']=='AC')
				{
					
					
					$arreglo['activo'] ='AC';
						
					$querys = new query;
					$listaroles = $querys -> getRows("*","rol","");
					foreach($listaroles as $key => $valor)
					{
						if($valor['activo']=="AC")
						{
							$queryr = new query;
							$arreglos['activo']='NA';
							$queryr -> dbUpdate($arreglos,"rol","where id_rol = ".$valor['id_rol']);
						}
					}
					
		
		
		$arreglo['nombre_rol'] = $_POST['nombre'];
		$arreglo['descripcion_rol'] = $_POST['descripcion'];
		
		
		$queryu=  new query();
		
			if($queryu -> dbUpdate($arreglo,"rol","where id_rol = ".$_GET['id']))
			{
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		
		echo "<script>window.location.href='rol.php'</script>";
				}else {
					echo "holsa";
					  $arreglo['nombre_rol'] = $_POST['nombre'];
					$arreglo['activo'] ='NA';
                   
					if($query -> dbUpdate($arreglo,"rol","where id_rol = ".$_GET['id'])){
						echo "<script>alert('Datos actualizados exitosamente');</script>";
					} else {
						echo "<script>alert('Error al actualizar los datos');</script>";
					}
					
					echo "<script>window.location.href='rol.php'</script>";
				}
		
			}else {
				
				echo "<script>alert('Error al actualizar los datos, ya existe u registro con estos datos');</script>";
			}
		
		
	}else{
		
		
		
		if($_POST['id_activo']=='AC')
		{
				
			$arreglo['activo'] ='AC';
		
			$querys = new query;
			$listaCliente1 = $querys -> getRows("*","rol","");
			foreach($listaCliente1 as $key => $valor)
			{
				if($valor['activo']=='AC')
				{
					$queryr = new query;
					$arreglos['activo']='NA';
					$queryr -> dbUpdate($arreglos,"rol","where id_rol = ".$valor['id_rol']);
				}
			}
				
		
			$query = new query;
			$arreglo['nombre_rol'] = $_POST['nombre'];
			$arreglo['descripcion_rol'] = $_POST['descripcion'];
		
		
		
		
			if($query -> dbUpdate($arreglo,"rol","where id_rol = ".$_GET['id']))
			{
				echo "<script>alert('Datos actualizados exitosamente');</script>";
			} else {
				echo "<script>alert('Error al actualizar los datos');</script>";
			}
		
			echo "<script>window.location.href='rol.php'</script>";
		}else {
			echo "Holas";
			$query = new query;
			$arreglo['nombre_rol'] = $_POST['nombre'];
			$arreglo['descripcion_rol'] = $_POST['descripcion'];
				
			$arreglo['activo'] ='NA';
		
			if($query -> dbUpdate($arreglo,"rol","where id_rol = ".$_GET['id'])){
				echo "<script>alert('Datos actualizados exitosamente');</script>";
			} else {
				echo "<script>alert('Error al actualizar los datos');</script>";
			}
				
			echo "<script>window.location.href='rol.php'</script>";
		}
		
	}
		}else {
			echo "<script>alert('Error El nombre de Tipo usuario tiene caracteres no permitidos: $nombretipo');</script>";
		}
	}
	
	function eliminar(){
		  
		
		$consulta= "SELECT * FROM rol  WHERE id_rol='".$_GET['id']."'";
		$resultado= mysql_query($consulta);
		//$fila = mysql_fetch_array($resultado);
		$valores="NA";
		do {
			$valores=$fila["activo"];
			echo $valores;
		} while ($fila = mysql_fetch_array($resultado));
		
		if($valores=="NA")
		{
		$query = new query;
		
		
			if($query -> dbDelete('rol','where id_rol='.$_GET['id']))
			{
			echo "<script>alert('Datos eliminados exitosamente');</script>";
			
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		
		
		echo "<script>window.location.href='rol.php'</script>";
		}else{
			echo "<script>alert('No puedes eliminar el rigistro por que  esta activo');</script>";
		}
	}

	function lista(){
		$template = new template;
		$template -> SetTemplate('tpl/lista.html');
	    $template -> SetParameter('titulo','Lista de Rol');
		$lista = "";
		/*Recuperar de la BD*/
		$query = new query;
		$listaproveedor = $query -> getRows("*","rol","");
		$init = ((isset($_GET['page'])== "" ? 1 :isset($_GET['page'])) - 1) * 1000;
        $listaproveedor1 = $query -> getRows("*","rol","LIMIT $init,1000");
		if(count($listaproveedor1)>0){
			/*<tr (class) --> referencia CSS (.) = 'nombre cabecera css (cabeza_lista)'>*/
			$lista = "<table  class=art-article border=1 cellspacing=0 cellpadding=0>
						<tr class = 'cabeza_lista'>
							<td>Nombre</td>
							<td>Estado</td>
							<td>Descripcion</td>
							<td>Editar</td>
							<td>Eliminar</td>
						</tr>";
			foreach($listaproveedor1 as $key => $valor){
				$lista .= '<tr>
							<td>'.$valor['nombre_rol'].'</td>
							<td>'.$valor['activo'].'</td>
							<td>'.$valor['descripcion_rol'].'</td>
						
							<td><a href="#" onclick="ajax(\'formulario_nuevo\',\'rol.php?action=editar&id='.$valor['id_rol'].'\',\'\'); return false;"><center><img src="images/edit.gif" /></center></a></td>
							<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="rol.php?action=eliminar&id='.$valor['id_rol'].'"><center><img src="images/delete.gif" /></center></a></td>
						   </tr>';
			}
			$lista .= "</table>";
			//$lista .= paging::navigation(count($listaproveedor),"rol.php",20);
		} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','rol.php');
		return $template -> Display();
	}

	
	function Display()
	{
		$base= new base();
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