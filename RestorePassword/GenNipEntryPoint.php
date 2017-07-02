<?
session_start();
require('../seguro.php');
require('../Response.php');
require('../link.php');
require('Nip.php');

//Recuperar datos
$user = security($_POST["user"]);

try{

	// 1. Generar un nip con el usuario dado.
	$response = Nip::genNewNipFromUser($user,link::getLink());

	// Si el usuario es valido entonces el nip estara en el campo data del objecto de respuesta
	$nip = $response->data;
	if($nip){
		// 2. Se activara el nip con el nombre de usuario
		$nip->activateNip();
		// 3. Enviar SMS al usuario con el nuevo NIP
		$response = $nip->sendToUser();
		echo $response->toJson();
	}else{
		// Si $nip == null es porque o el usuario no existe o no hay un numero de telefono al cual enviar el nip, o porque un nip activado existe en la cuenta. Si no hay un numero telefonico asociado entonces solo se le indica que se comunique con atencion a soporte miro-tec.
		echo $response->toJson();
	}
}catch(Exception $e){
		echo Response::errorResponseFromException($e)->toJson();
}