<?php
class login
{
  function validate($login,$password) //recives the strings with username & password, returns the user id if the user is registered & false if there were not coincidences in the database
  {
    $query = new query;
    $pass = ($password);
    $row = $query->getRows("login, password, id_usuario","usuario");
    foreach($row as $key)
    {
      if ($key['login'] == $login)
      	if ($key['password'] == $pass)
      		return $key['id_usuario'];
    }
    return false;
  }
  
	function loginUser($user_id)
	{
		$query = new query;
		$row = $query->getRow("id_usuario,id_rol, nombre_usuario","usuario","WHERE id_usuario = $user_id");
		$_SESSION['logged'] = 1;
		$_SESSION['nombre'] = $row['nombre_usuario'];
		$_SESSION['idusuario'] = $row['id_usuario'];
		$_SESSION['idrol']=$row['id_rol'];
	}

}
?>