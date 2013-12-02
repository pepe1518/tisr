<?php
require 'class/base.class.php';
class participante
{
	function nuevo(){
	
		$query = new query;
	
		$colegio=$query->getRows('id_colegio,nombre_colegio','colegio');
		$listacolegio= '<select name="id_colegio"><option value="">--seleccione  colegio--</option>';
		foreach($colegio as $key=>$valor){
			$listacolegio.= '<option value="'.$valor['id_colegio'].'">'.$valor['nombre_colegio'].'</option>';
		}
			$listacolegio.= '</select>';
			
		$template = new template;
		$template -> SetTemplate('tpl/form_participante.html');
		$template -> SetParameter('colegio',$listacolegio);
		 $template -> SetParameter('accion','guardar');
		return $template -> Display();
		
	}
	
	function editar(){
		$template = new template;
		$template -> SetTemplate('tpl/form_participante.html');
		$query = new query;
		$colegio=$query->getRows('id_colegio,nombre_colegio','colegio');
		$listacolegio= '<select name="id_colegio"><option value="">--seleccione  colegio--</option>';
		foreach($colegio as $key=>$valor){
			$listacolegio.= '<option value="'.$valor['id_colegio'].'">'.$valor['nombre_colegio'].'</option>';
		}
			$listacolegio.= '</select>';
		$template -> SetParameter('colegio',$listacolegio);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardar(){
		  $query = new query;
		$particcipo = $query->getRow('id_participante','participante','where id_usuario= '.$_SESSION['idusuario']);
		
		if($particcipo['id_participante']==0)
		{
			$arreglo['id_usuario'] = $_SESSION['idusuario'];
			$arreglo['id_colegio'] = $_POST['id_colegio'];
			if($query -> dbInsert($arreglo,"participante")){
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
			
		}else {
				echo "<script>alert('Error  ya estas inscrito en un colegio');</script>";
		
		}
		  
		
		echo "<script>window.location.href='participante.php'</script>";
	}

	function guardarEdit(){
		$query = new query;
		$arreglo['id_colegio'] = $_POST['id_colegio'];
		if($query -> dbUpdate($arreglo,"participante","where id_participante = ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		echo "<script>window.location.href='participante.php'</script>";
	}
	
	function eliminar(){
		$query = new query;
		if($query -> dbDelete('participante','where id_participante='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		echo "<script>window.location.href='participante.php'</script>";
	}

	function lista(){
		$template = new template;
		$template -> SetTemplate('tpl/lista.html');
	$template -> SetParameter('titulo','Permisos para el sistema');
		$lista = "";
		/*Recuperar de la BD*/
		$query = new query;
		$listaproveedor = $query -> getRows("*","participante","");
		$init = ((isset($_GET['page'])== "" ? 1 :isset($_GET['page'])) - 1) * 1000;
        $listaproveedor1 = $query -> getRows("*","participante","LIMIT $init,1000");
		if(count($listaproveedor1)>0){
				$lista = "<table  class=art-article border=0 cellspacing=0 cellpadding=0>
						<tr class = 'cabeza_lista'>
							<td>Colegio</td>
								<td>Editar</td>
							<td>Eliminar</td>
						</tr>";
			foreach($listaproveedor1 as $key => $valor){
				if($valor['id_usuario']=$_SESSION['idusuario'])
				{
			$colegio = $query->getRow('nombre_colegio','colegio','where id_colegio= '.$valor['id_colegio']);
				$lista .= '<tr>
					     	<td>'.$colegio['nombre_colegio'].'</td>
						
							<td><a href="#" onclick="ajax(\'formulario_nuevo\',\'participante.php?action=editar&id='.$valor['id_participante'].'\',\'\'); return false;"><center><img src="images/edit.gif" /></center></a></td>
							<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="participante.php?action=eliminar&id='.$valor['id_participante'].'"><center><img src="images/delete.gif" /></center></a></td>
						   </tr>';
				}
			}
			$lista .= "</table>";
			//$lista .= paging::navigation(count($listaproveedor),"participante.php",1000);
		} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','participante.php');
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
			  $template -> SetParameter('izquierdo',$bases ->menuizquierdo());
			$template->SetParameter('contenido','Ud. no tiene privilegios de acceso, inicie sesion');
		} elseif(isset($_SESSION['logged'])==1)
		{
			$template -> SetParameter('formlogin',"<h2>Usuario: ".$_SESSION['nombre']."</h2>");
			
			$template -> SetParameter('menu',$base->menuAdmin());
				$template->SetParameter('contenido',$this -> lista());
				  $template -> SetParameter('izquierdo',$bases ->menuizquierdo());
			
		}
		return $template->Display();
	}
}
?>