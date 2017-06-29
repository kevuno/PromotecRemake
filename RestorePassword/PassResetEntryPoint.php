<?php
session_start();
require('../seguro.php');
require('../Response.php');
require('Nip.php');
require('PassReset.php');

//Recuperar datos
$nip = security($_POST["nip"]);

try{
	// 1. Checar si el nip es valido y obtener el usuario asociado con el nip
	$response = Nip::validateNip($nip);

	// Si el nip es valido entonces tendra al objecto nip en el campo data de la respuesta
	$nip = $response->data;
	if($nip){
		// 2. Se desactiva el nip y se genera un nuevo pass temporal para el usuario
		$nip->deactivate();
		$tempPass = PassReset::getTempPass($nip->user);
		// 3. Enviar SMS al usuario con su nuevo pass temporal
		$response = PassReset::sendNewTempPass($tempPass,$nip->user);
		return "R|".$response->message."|".$response->data;
	}else{
		// Si el nip es invalido puede ser por ser incorrecto o expirado
		echo "R|".$response->message;
	}
}catch(Exception $e){
		echo "E|".$e;
}

?>