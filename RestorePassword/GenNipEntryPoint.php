<?
session_start();
require('../seguro.php');
require('../Response.php');
require('Nip.php');

//Recuperar datos
$user = security($_POST["user"]);

echo $user;
try{
	// 1. Generar un nip con el usuario dado.
	$response = Nip::genNewNip($user);

	// Si el usuario es valido entonces el nip estara en el campo data del objecto de respuesta
	$nip = $response->data;
	if($nip){
		// 2. Se activara el nip con el nombre de usuario
		$nip->activateNip();
		// 3. Enviar SMS al usuario con el nuevo NIP
		$response = $nip->sendToUser();
		return "R|".$response->message;
	}else{
		// Si no existe un Nip es porque o el usuario no existe o no hay un numero de telefono al cual enviar el nip
		echo "R|".$response->message;
	}
}catch(Exception $e){
		echo "E|".$e;
}