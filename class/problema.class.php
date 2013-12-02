<?php
require 'class/base.class.php';
class problema
{
	function nuevo(){
		
		$query = new query;
		$area=$query->getRows('id_nivel,nombre_nivel','nivel');
		$listanivel = '<select name="id_nivel"><option value="">--seleccione nivel--</option>';
		foreach($area as $key=>$valor){
			$listanivel.= '<option value="'.$valor['id_nivel'].'">'.$valor['nombre_nivel'].'</option>';
		}
		$listanivel.= '</select>';
		

		$template = new template;
		$template -> SetTemplate('tpl/form_problema.html');
		$template -> SetParameter('nivel',$listanivel);
		
		$template -> SetParameter('nombre','');
		  $template -> SetParameter('accion','guardar');
		return $template -> Display();
	}
	
	function editar(){
		$template = new template;
		$template -> SetTemplate('tpl/form_problema.html');
		$query = new query;
		$proveedor = $query -> getRow('*','olimpiada','where id_olimpiada = '.$_GET['id']);
		$template -> SetParameter('nombre',$proveedor['nombre_olimpiada']);
		$template -> SetParameter('descripcion',$proveedor['descripcion']);
		$template -> SetParameter('accion','guardarEdit&id='.$_GET['id']);
		return $template -> Display();
	}
	
	function guardar(){
		$query = new query;
		$arreglo['id_usuario'] = $_SESSION['idusuario'];
		$arreglo['id_nivel'] = $_POST['id_nivel'];
		$arreglo['nombre_problema'] = $_POST['nombre'];
		
		$todoOK = true;
		$max_length = (1024*1024)*10;
		$upload = new upload; // upload
		 $name = $_SESSION['idusuario'].time();
		 $rutasubida="uploads/doc/$name";
         mkdir("$rutasubida", 777); 
		$upload -> SetDirectory("$rutasubida");
		
		 shell_exec("chmod -R 777 uploads"); 
		 
	       $upload -> SetDirectory($rutasubida);
		    $file = $_FILES['imagen_path']['name'];
			if ($_FILES['imagen_path']['name'] != "")
		    {
			$tipo_archivo = $_FILES['imagen_path']['type'];
			{
			$tipo_archivo = $_FILES['imagen_path']['type'];
			
			$allowed = "pdf";
			$filename = $_FILES['imagen_path']['name'];
			$nombrearchivosubido = pathinfo($filename, PATHINFO_FILENAME);
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$tipo_archivo = $_FILES['imagen_path']['type'];
			if (!(strpos($tipo_archivo, "pdf"))) 
			{
					$todoOK = false;
					echo "<script>alert('Solo los archivos pdf Permitidos verifique y intende de nuevo: ".$ext."');</script>";
	
			}
			
			$tamanio = $_FILES['imagen_path']['size'];
			if ($tamanio > $max_length)
			{
			 $todoOK = false;
			 echo "<script>alert('el archivo de imagen es demasiado grande');</script>";
			} else {
			 //$name = $_SESSION['idusuario'].time();
			 $upload -> SetFile("imagen_path");
			if ($upload -> UploadFile($nombrearchivosubido.".".$upload->ext))
              {                          
            //  $arreglo['respuesta_nombre']=$_FILES['imagen_path']['name'];
			  $arreglo['archivo_problema'] = $rutasubida."/".$nombrearchivosubido.".".$upload->ext;
			  $nombrearchivo=$filename;
              }
			  }
			}
		}
		if($todoOK) {
			//$arreglo['archivo_problema']= $urldocumento;
			if($query -> dbInsert($arreglo,"problema")){
				
				echo "<script>alert('Datos registrados exitosamente');</script>";
			} else {
				echo "<script>alert('Error al registrar los datos');</script>";
			}
		}
		echo "<script>window.location.href='problema.php'</script>";
	}
	function guardarEdit(){
		$query = new query;
		$arreglo['nombre_olimpiada'] = $_POST['nombre'];
		$arreglo['descripcion'] = $_POST['descripcion'];
		if($query -> dbUpdate($arreglo,"olimpiada","where id_olimpiada = ".$_GET['id'])){
			echo "<script>alert('Datos actualizados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al actualizar los datos');</script>";
		}
		echo "<script>window.location.href='problema.php'</script>";
	}
	
	function eliminar(){
		$query = new query;
		if($query -> dbDelete('problema','where id_problema='.$_GET['id'])){
			echo "<script>alert('Datos eliminados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al aliminar');</script>";
		}
		echo "<script>window.location.href='problema.php'</script>";
	}

function lista(){
		$template = new template;
		$template -> SetTemplate('tpl/lista.html');
		$template -> SetParameter('titulo','Lista de los Problemas');
		$lista = "";
		/*Recuperar de la BD*/
		$query = new query;
		$listaCliente1 = $query -> getRows("*","problema","");
		if(count($listaCliente1)>0){
			$lista = "<table class=art-article border=1 cellspacing=0 cellpadding=0 >
						<tr class = 'cabeza_lista'>
							<td>     Nivel</td>
					        <td>           Nombre</td>
							
							<td>      Ver</td>
							<td>     Eliminar</td>
							<td>     Agregar datos salida</td>
						</tr>";
			foreach($listaCliente1 as $key => $valor){
				$nivelolimpic = $query->getRow('nombre_nivel','nivel','where id_nivel = '.$valor['id_nivel']);
					$var=$valor['archivo_problema'];
				$lista .= '<tr>
							<td>'.$nivelolimpic ['nombre_nivel'].'</td>
								<td>'.$valor['nombre_problema'].'</td>
							  <td><a  href="'.$valor['archivo_problema'].'"  TARGET="_blank" ><center><img src="images/pdf.png" /></center></a></td>
						 	<td><a onclick="return confirm(\'Esta seguro de eliminar los datos?\');" href="problema.php?action=eliminar&id='.$valor['id_problema'].'"><center><img src="images/delete.gif" /></center></a></td>
						          <td><a  href="salida.php?id='.$valor['id_problema'].'"  TARGET="_self" ><center><img src="images/agregarboton.png" /></center></a></td>
					       							
						 	</tr>';
			}
			$lista .= "</table>";
			} else {
			$lista = "No se tienen datos registrados";
		}
		$template -> SetParameter('lista',$lista);
		$template -> SetParameter('archivo','problema.php');
		return $template -> Display();
	}
	

    /*funcion salir(){ session_destroy(); }*/
	function Display()
	{
		$base= new base();
		$template = new template;
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		
		$template->SetParameter('titulo','');//sets the parameters that uses the template
      
		if(isset($_SESSION['logged'])==0){
			$template -> SetParameter('formlogin',$base -> formLogin());
			$template -> SetParameter('menu','');
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