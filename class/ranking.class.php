<?php
require 'class/base.class.php';
class ranking
{
	
function lista(){
		$template = new template;
		$template -> SetTemplate('tpl/listas.html');
		$template -> SetParameter('titulo','Lista de Olimpistas');
		$lista = "";
		/*Recuperar de la BD*/
		$query = new query;
		$listaCliente1 = $query -> getRows("*","usuario","");
		if(count($listaCliente1)>0){
			$lista = "<table class=art-article border=0 cellspacing=0 cellpadding=0 >
						<tr class = 'cabeza_lista'>
							<td>Olimpista</td>
							<td> Numero Problemas</td>
					        <td>Nota Promedio</td>
					        
							</tr>";
			foreach($listaCliente1 as $key => $valor){
				$respuestas = $query->getRow('id_problema','respuesta','where id_usuario = '.$valor['id_usuario']);
					  if($respuestas['id_problema']!=0)
					  {
					  	
					  	
					  
				
				$lista .= '<tr>
							<td>'.$valor['nombre_usuario'].'</td>
							<td>'.$valor['nombre_usuario'].'</td>
							<td>'.$valor['nombre_usuario'].'</td>
							</tr>';
					  }
				
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
		$base = new base();
		$template = new template;
		$template->SetTemplate('tpl/index.html'); //sets the template for this function template
		
		$template->SetParameter('titulo','Juez Virtual');//sets the parameters that uses the template
    		
	if(isset($_SESSION['logged'])==0){
		$template->SetParameter('contenido',$base ->ranking());
    		$template->SetParameter('menu','');
    		$template->SetParameter('contenido',$base ->ranking());
			$template -> SetParameter('formlogin',$base -> formLogin());
			 $template -> SetParameter('izquierdo',$base ->menuizquierdo());
			
			//$template->SetParameter('contenido','Ud. no tiene privilegios de acceso, inicie sesion');
		} elseif(isset($_SESSION['logged'])==1)
		{
			$template -> SetParameter('formlogin',"<h2>Usuario: ".$_SESSION['nombre']."</h2>");
			
			$template -> SetParameter('menu',$base->menuAdmin());
			$template->SetParameter('contenido',$base ->ranking());
			 $template -> SetParameter('izquierdo',$base ->menuizquierdo());
				//$template->SetParameter('contenido',$this -> lista());
			
		}
    		
		
		return $template->Display();
	}
}
?>