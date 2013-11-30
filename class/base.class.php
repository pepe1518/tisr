<?php
class base
{
	function ranking() {

$consulta= "select  u.`id_usuario`, u.`nombre_usuario`, u.`apellido_paterno`, AVG(n.`nota_olimpista`) as promedio
from `usuario`  u, `respuesta`  r, `nota_olimpista` n
where  u.`id_usuario`=r.`id_usuario`  and r.`id_respuesta`=n.`id_respuesta`
GROUP  by  u.`id_usuario`";
		 	$resultado= mysql_query($consulta);
		 	$fila = mysql_fetch_array($resultado);
		 //	$valores="";
		 	$lista = "<table class=art-article border=1 cellspacing=0 cellpadding=0 >
						<tr class = 'cabeza_lista'>
							<td>Olimpista</td>
							
					        <td>Nota</td>
					      	</tr>";
		 	do {
		 		$valornota=(int) $fila['promedio'];
		 	$lista .= '<tr>
							<td>'.$fila['nombre_usuario']." ".$fila['apellido_paterno'].'</td>
							<td>'.$valornota.'</td>
							 </tr>';
		  	} while ($fila = mysql_fetch_array($resultado));
		  	$lista .= "</table>";
		  	return  $lista;
		 	}

	function showContent()
	{
		$template = new template;
		$template->SetTemplate('tpl/index_content.html');
		$varMoreContent = 'Sample to Ajax popup!!! <br />';
		$varMoreContent .= 'click <a href="#" onclick="ajax(\'popup\',\'index.php?action=viewPopUp\',\'\'); return false;">here</a>';
		$template->SetParameter('moreContent',$varMoreContent);
		return $template->Display();
	}
	

	function validateUsername($name){
		
		//SI longitud pero NO solo caracteres A-z
		 if(!preg_match("/^[a-zA-Z]+$/", $name))
			return false;
		// SI longitud, SI caracteres A-z
		else
			return true;
	}
function  usuarioActivo()
	{
		 $query = new query;
   
    $row = $query->getRows("nombre_rol, activo, id_rol","rol");
    foreach($row as $key)
    {
      if ($key['activo'] =='AC')
      		return $key['id_rol'];
    }
    return 0;
	}
	
	
	
	
	function menuizquierdo()
	{
		$template = new template;
		$lista = "";
                
		if(isset($_SESSION['idusuario']))
		{
			if($_SESSION['idrol']==1)
			{
			$template -> SetTemplate('tpl/form_adminizquierdo.html');
			}else {
				$template -> SetTemplate('tpl/vacio.html');
			}
		
		}else {
			$template -> SetTemplate('tpl/vacio.html');
		}
        
		return $template -> Display();
		
	}
	function menuAdmin(){
		 $template = new template;
		$lista = "";
                
		if(isset($_SESSION['idusuario']))
		{
			if($_SESSION['idrol']==1)
			{
		$template -> SetTemplate('tpl/menu_admin.html');
				}
			
		if($_SESSION['idrol']==2)
			{
		$template -> SetTemplate('tpl/menu_olimpista.html');
			}
			
		if($_SESSION['idrol']==3)
			{
		$template -> SetTemplate('tpl/menu_entrenador.html');
			}
		
		
		
		}else {
			$template -> SetTemplate('tpl/menu_inicio.html');
		}
                
        
		return $template -> Display();
	}

	function formLogin(){
		$template = new template;
		$template -> SetTemplate('tpl/form_login.html');
		return $template -> Display();
	}


}
?>