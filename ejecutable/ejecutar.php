<?php
 $ress = shell_exec("java 7888/E < 7888/hola1.txt");
   //var_dump($ress);
	$realpat=realpath("E");
	var_dump($realpat);
	$ress = shell_exec("cd /7888");
	$ress = shell_exec("pwd");
	var_dump($ress);
	
	//echo "<pre>$rr<pre>";
	echo "<pre>$ress<pre>";
	//echo $ress;