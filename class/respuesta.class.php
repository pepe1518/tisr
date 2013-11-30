<?php
require 'class/base.class.php';
class respuesta
{
	function nuevo(){
		$query = new query;
		$problema=$query->getRows('id_problema,nombre_problema','problema');
		$listaproblema= '<select name="id_problema"><option value="">--seleccione  problema--</option>';
		foreach($problema as $key=>$valor){
			$listaproblema.= '<option value="'.$valor['id_problema'].'">'.$valor['nombre_problema'].'</option>';
		}
			$listaproblema.= '</select>';
				$lenguaje=$query->getRows('id_lenguaje,nombre_lenguaje','lenguaje');
		$listalenguaje= '<select name="id_lenguaje"><option value="">--seleccione lenguaje--</option>';
		foreach($lenguaje as $key=>$valor){
			$listalenguaje.= '<option value="'.$valor['id_lenguaje'].'">'.$valor['nombre_lenguaje'].'</option>';
		}
			$listalenguaje.= '</select>';
			
		$template = new template;
		$template -> SetTemplate('tpl/form_respuesta.html');
		$template -> SetParameter('problema',$listaproblema);
			$template -> SetParameter('lenguaje',$listalenguaje);
	   $template -> SetParameter('accion','guardar');
		return $template -> Display();
	}
	
	function editar(){
		$template = new template;
		$template -> SetTemplate('tpl/form_usuariorol.html');
		$query = new query;
		$proveedor = $query -> getRow('*','nivel','where id_nivel = '.$_GET['id']);
		$template -> SetParameter('nombre',$proveedor['nombre_carrera']);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardar(){
		$query = new query;
		$arreglo['id_problema'] = $_POST['id_problema'];
		$arreglo['id_lenguaje'] = $_POST['id_lenguaje']; /*$arreglo['campos bd tabla proveedor'] = $_POST['nombre obtenido formulario_proveedor']*/
		
		$arreglo['id_usuario'] = $_SESSION['idusuario'];
		
		
		$todoOK = true;
		$max_length = (1024*1024)*10;
		$upload = new upload; // upload
		$upload -> SetDirectory("uploads/doc");
		$file = $_FILES['imagen_path']['name'];
		$arreglo['respuesta_archivo'] = "";
		if ($_FILES['imagen_path']['name'] != "")
		{
			$tipo_archivo = $_FILES['imagen_path']['type'];
			if (!(strpos($tipo_archivo, "pdf"))) {
				$todoOK = false;
				echo "<script>alert('solo archivos pdf. Por favor verifique e intente de nuevo, tipo: ".$tipo_archivo."');</script>";
			} else {
				$tamanio = $_FILES['imagen_path']['size'];
				if ($tamanio > $max_length) {
					$todoOK = false;
					echo "<script>alert('el archivo de imagen es demasiado grande');</script>";
				} else {
					$name = "imagen_".time();
					$upload -> SetFile("imagen_path");
					if ($upload -> UploadFile( $name )){
						$arreglo['respuesta_archivo'] = "uploads/doc/".$name.".".$upload->ext;
					}
				}
			}
		}
		if($todoOK) {
			//$arreglo['archivo_problema']= $urldocumento;
			if($query -> dbInsert($arreglo,"respuesta")){
				
				echo "<script>alert('Datos registrados exitosamente');</script>";
			} else {
				echo "<script>alert('Error al registrar los datos');</script>";
			}
		}
		echo "<script>window.location.href='respuesta.php'</script>";
		
		
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
		if($query -> dbDelete('respuesta','where id_respuesta='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		echo "<script>window.location.href='respuesta.php'</script>";
	}

	function lista(){
		$template = new template;
		$template -> SetTemplate('tpl/lista.html');
	$template -> SetParameter('titulo','Lista de soluciones');
		$lista = "";
		/*Recuperar de la BD*/
		$query = new query;
		$listaproveedor = $query -> getRows("*","respuesta","");
		$init = ((isset($_GET['page'])== "" ? 1 :isset($_GET['page'])) - 1) * 1000;
        $listaproveedor1 = $query -> getRows("*","respuesta","LIMIT $init,1000");
		if(count($listaproveedor1)>0){
				$lista = "<table  class=art-article border=0 cellspacing=0 cellpadding=0>
						<tr class = 'cabeza_lista'>
							<td>Problema</td>
							<td>Lenguaje</td>
							<td>Autor</td>
							<td>Archivo</td>
							<td>Editar</td>
							
						</tr>";
			foreach($listaproveedor1 as $key => $valor){
				
			$problema= $query->getRow('nombre_problema','problema','where id_problema= '.$valor['id_problema']);
			 $lenguaje = $query->getRow('nombre_lenguaje','lenguaje','where id_lenguaje= '.$valor['id_lenguaje']);
			 $responsable = $query->getRow('nombre_usuario','usuario','where id_usuario= '.$valor['id_usuario']);
				if ($valor['id_usuario']==$_SESSION['idusuario'])
				{
			 $lista .= '<tr>
							<td>'.$problema['nombre_problema'].'</td>
							<td>'.$lenguaje['nombre_lenguaje'].'</td>
							<td>'.$responsable['nombre_usuario'].'</td>
						  <td><a  href="'.$valor['archivo_solucionario'].'"  TARGET="_blank" ><center><img src="images/pdf.png" /></center></a></td>
						 <td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="respuesta.php?action=eliminar&id='.$valor['id_respuesta'].'"><center><img src="images/delete.gif" /></center></a></td>
						   
						 </tr>';
			}
			}
			$lista .= "</table>";
			
			
			//$lista .= paging::navigation(count($listaproveedor),"respuesta.php",20);
		} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','respuesta.php');
		return $template -> Display();
	}

	
	function Display()
	{
		$base = new base();
		$template = new template;
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		
		$template->SetParameter('titulo','');//sets the parameters that uses the template
      
		if(isset($_SESSION['logged'])==0){
			$template -> SetParameter('formlogin',$base -> formLogin());
			  $template -> SetParameter('izquierdo',$base->menuizquierdo());
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
