<?php
session_start();
require('../seguro.php');
require('../Response.php');
require('../link.php');
require('Nip.php');
require('PassReset.php');

//Recuperar datos
$nip = security($_POST["nip"]);

try{

	// 1. Checar si el nip es valido y obtener el usuario asociado con el nip
	$response = Nip::validateNip($nip);

	// Si el nip es valido entonces tendra al objecto de PassReset en el campo data de la respuesta
	$passReset = $response->data;
	if($passReset){
		// 2. Se desactiva el nip y se genera un nuevo pass temporal para el usuario
		$passReset->nip->deactivate();
		$tempPass = $passReset->getTempPass();
		// 3. Guardar nuevo pass temporal en la bd
		$tempPass->save();
		// 4. Enviar SMS al usuario con su nuevo pass temporal
		$response = $tempPass->sendPass();
		echo $response->toJson();
	}else{
		// Si el nip es invalido puede ser por ser incorrecto o expirado
		echo $response->toJson();
	}
}catch(Exception $e){
		echo Response::errorResponseFromException($e)->toJson();
}

?>
