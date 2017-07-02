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

	// Si el usuario es valido y tiene un NIP valido para enviarr entonces el nip estara en el campo data del objecto de respuesta
	$nip = $response->data;
	if($nip){
		// 2. Enviar SMS al usuario con el nuevo NIP y guardamos el mensaje de respuesta
		$response = $nip->sendToUser();
		// 3. Se activara el nip en la base de datos
		$nip->activateNip();
		echo $response->toJson();
	}else{
		// Si $nip == null es porque o el usuario no existe o no hay un numero de telefono al cual enviar el nip, o porque ya existe un nip reciente en la cuenta. Si no hay un numero telefonico asociado entonces solo se le indica que se comunique con atencion a soporte miro-tec.
		echo $response->toJson();
	}
}catch(Exception $e){
		echo Response::errorResponseFromException($e)->toJson();
}