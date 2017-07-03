<?
session_start();
require('../seguro.php');
require('../link.php');
require('Nip.php');
require('../Login/LoginMain.php');
require('../Response.php');

//Recuperar datos
$user = security($_POST["user"]);


try{
	// 1. Generar un nip con el usuario dado.
		// Obtener objecto login
		$login = LoginMain::loginFactory("promotec");
		$response = Nip::getNipFromUser($user,link::getLink(),$login);

		// Si el usuario es valido y tiene un NIP valido para enviar entonces el nip estara en el campo data del objecto de respuesta
		$nip = $response->data;
		if($nip){
			// 2. Enviar SMS al usuario con el nuevo NIP solo si el nip no se ha enviado y guardamos el mensaje de respuesta
				if (!$nip->sent){
					$response = $nip->sendToUser();
					// 3. Se activara el nip en la base de datos
					$nip->activateNip();	
				}
		// Print latest response
		echo $response->toJson();

		}else{
			// Si $nip == null es porque o el usuario no existe o no hay un numero de telefono al cual enviar el nip. Si no hay un numero telefonico asociado entonces solo se le indica que se comunique con atencion a soporte mirotec.
			echo $response->toJson();
	}
}catch(Exception $e){
		echo Response::errorResponseFromException($e)->toJson();
}