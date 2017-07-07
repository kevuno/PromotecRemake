<?php
require('../seguro.php');
require('../link.php');
require('../Response.php');
require('register.php');


//Recuperar datos
$nombre=security($_POST['nombre']);
$nombre=ucwords(strtolower(trim($nombre)));
$apaterno=security($_POST['apaterno']);
$apaterno=ucwords(strtolower(trim($apaterno)));
$amaterno=security($_POST['amaterno']);
$amaterno=ucwords(strtolower(trim($amaterno)));
$cel=security($_POST['cel']);

// Call a la función de registro
try{
	$response = execRegistro($nombre,$apaterno,$amaterno,$cel,link::getLink());
	echo $response->toJson();
}catch (Exception $e){
	echo Response::errorResponseFromException($e)->toJson();

}




?>
