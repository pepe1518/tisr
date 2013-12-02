<?php
require 'class/base.class.php';

include 'compilador/lib/Config.php';
include 'compilador/lib/Compiler.php';
include 'compilador/lib/Runner.php';
include 'compilador/lib/Validator.php';

class visualizar
{	
function guardar()
    {
    	$extenciones = array('java', 'c','cpp'); 
    	  $idresouestaactual=0;
		
		$query = new query;
		$arreglo['id_problema'] =$_SESSION['idproblemas'];
		$arreglo['id_lenguaje'] = $_POST['id_lenguaje'];
		$arreglo['id_usuario'] = $_SESSION['idusuario'];
		$arreglodatos= array();
		if(isset($_POST['observaciones']))
		{
		$arreglodatos= $_POST['observaciones'];
		}
                
            $nombrearchivo="";
            $todoOK = true;
		    $max_length = (1024*1024)*10;
		    $upload = new upload; // upload
		    $name = $_SESSION['idusuario'].time();
            $rutasubida="compilador/data/problems/$name";
            $rutaclass="compilador/data/bin/$name";
            mkdir("$rutasubida", 777); 
            mkdir("$rutaclass", 777);
            shell_exec("chmod -R 777 compilador/data/"); 
		    $upload -> SetDirectory($rutasubida);
		    $file = $_FILES['imagen_path']['name'];
			if ($_FILES['imagen_path']['name'] != "")
		    {
			$tipo_archivo = $_FILES['imagen_path']['type'];
			{
			$tipo_archivo = $_FILES['imagen_path']['type'];
			
			$allowed =  array('java','c' ,'cpp');
			$filename = $_FILES['imagen_path']['name'];
			$nombrearchivosubido = pathinfo($filename, PATHINFO_FILENAME);
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			if(!in_array($ext,$allowed) ) 
			{
					$todoOK = false;
					echo "<script>alert('Solo los archivos .java .c .cpp Permitidos verifique y intende de nuevo: ".$ext."');</script>";
	
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
              $arreglo['respuesta_nombre']=$_FILES['imagen_path']['name'];
			  $arreglo['respuesta_archivo'] = $rutasubida."/".$nombrearchivosubido.".".$upload->ext;
			  $nombrearchivo=$filename;
              }
			  }
			}
		}
		if($todoOK)
        {
        
            $input= $rutasubida."/"."input.txt";	
      		$fp = fopen($input, 'w+');
			fwrite($fp,$_POST['entrada']);
			fclose($fp);
		
		   $config = new Config();
         if (!isset( $nombrearchivo)) 
           {
                 return;
            }
          $files =  $nombrearchivo;

          echo  $files ."<br>";
         $result = Compiler::compile($name,$files, $config);
         $vandera=false;
           if ($result[0])    
            {
            $arreglo['error_compilacion']='[OK]' . PHP_EOL;
            $vandera=true;
           // echo "ok";
            shell_exec("chmod -R 777 compilador/data/"); 
            exec("sh script.sh $name $nombrearchivosubido $ext");
            
            $rutaresultado=$rutaclass."/".$nombrearchivosubido."/salida.txt";
            
            
            $fh = fopen($rutaresultado, 'r');
			$theData = fread($fh, 10000);
			fclose($fh);
			echo $theData;
			 $arreglo['resultado']=$theData;
		
            } else 
            {
            }
            
             $error=  substr($result[1], 51, -1);
             echo $error;
         if($query -> dbInsert($arreglo,"respuesta"))
           {
		   $idresouestaactual=mysql_insert_id();
			$arre['id_respuesta'] =mysql_insert_id();
		
		  	foreach ($arreglodatos as $value) 
		  	{
		       $arre['valor_datossalida'] = $value;
		
			$query -> dbInsert($arre,"datos_salida");
		  	}
		  	
		  	$notacompilacion=0;
		  	
		  	
       if($vandera==true)
        {
        	  $fh = fopen($rutaresultado, 'r');
			$theData = fread($fh, 10000);
			fclose($fh);
			
        $notacompilacion=50;    		
		$datossalidad=array();
	
			$valoridproblema=$_SESSION['idproblemas'];
		
		 	$consulta= "SELECT  d.dato_salida
            FROM  problema p, dato  d
            where p.id_problema=d.id_problema  and p.id_problema=$valoridproblema";
		 	$resultado= mysql_query($consulta);
		      $fieldnames=array(); 
		      if (mysql_num_rows($resultado) > 0) { 
		        while ($row = mysql_fetch_assoc($resultado)) { 
		        $datossalidad[] = $row['dato_salida']; 
		        } 
				}
      	 			//if(in_array($theData, $datossalidad))
		 			{
		 				$notacompilacion=$notacompilacion+50;
		 			}
		 		
		 		  $arreglonota['id_respuesta']= $idresouestaactual;
		 		   $arreglonota['nota_olimpista']= $notacompilacion;
		 			$query -> dbInsert($arreglonota,"nota_olimpista");
                         	
             }
		  	
		  	
		  	
		  	
		  	
                        
 			
			echo "<script>alert('Datos registrados exitosamente');</script>";
		} else {
			echo "<script>alert('Error al registrar los datos');</script>";
		}
	}
		
		
		
	
	//echo "<script>window.location.href='listaproblemas.php'</script>";
	}

	function lista(){
		if(! isset($_SESSION['idproblemas']))
		echo "<script>window.location.href='listaproblemas.php'</script>";
	  
		$_SESSION['idproblemas']=$_GET['id'];
		$query = new query;
		$lenguaje=$query->getRows('id_lenguaje,nombre_lenguaje','lenguaje');
		$listalenguaje= '<select name="id_lenguaje"><option value="">--seleccione  lenguaje--</option>';
		foreach($lenguaje as $key=>$valor){
			$listalenguaje.= '<option value="'.$valor['id_lenguaje'].'">'.$valor['nombre_lenguaje'].'</option>';
		}
			$listalenguaje.= '</select>';
		
			
		$template = new template;
		$template -> SetTemplate('tpl/form_visualizador.html');
		$template -> SetParameter('nombre','');
		$template -> SetParameter('lenguaje',$listalenguaje);
		$template -> SetParameter('password','');
		
		$template -> SetParameter('accion','guardar');
		return $template -> Display();
	
		
	}
	
	
	
	
	function Display()
	{
		$template = new template;
		$base= new base();
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		
		$template->SetParameter('titulo','');//sets the parameters that uses the template
      
		if(isset($_SESSION['logged'])==0){
			$template -> SetParameter('formlogin',$base -> formLogin());
			  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			$template->SetParameter('contenido','Ud. no tiene privilegios de acceso, inicie sesion');
		} elseif(isset($_SESSION['logged'])==1)
		{
			$template -> SetParameter('formlogin',"<h2>Usuario: ".$_SESSION['nombre']."</h2>");
			
			$template -> SetParameter('menu',$base->menuAdmin());
			  $template -> SetParameter('izquierdo',$base ->menuizquierdo());
				$template->SetParameter('contenido',$this -> lista());
			
		}
		return $template->Display();
	}
}
?>