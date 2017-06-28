<?php
require('Response.php');
require('Login.php');
require('../link.php');

class LoginMain{

	/**
	* Funcion que ejecuta todas las funciones necesarias para hacer login ejecutando el login principal en
	* en la base de datos base y despues obtiene los datos de sesión dependiendo del login del cual se trate.
	* @param: $LoginData: El objecto del login: Incluye user,pass,data del captcha e ip.
	* @param: $LoginData: El objecto del captcha: Incluye captcha e ip.
	*/
	public static function loginGeneral(LoginData $loginData, CaptchaData $captchaData){
		//Validar captcha
		$captcha_checker = $captchaData->validate();
		//if(!$captchaData->validate()){
		//	throw new Exception("No se pudo validar el captcha");
		//}
		try{
			//Construir el login correspondiente y ejecutar intento de login
			$login = self::loginFactory($loginData->tipo);
			// Asiganar objecto de link e informacion de usuario y pass al login
			$login->setLink(link::getLink());
			$login->setData($loginData);
			// Intentar hacer login
			$response = $login->login();

			// Si el login tuvo exito se obtienen las variables de session y el token
			if($response->type == Response::SUCCESS){
				//Obtenemos las variables que seran de tipo $_SESSION
				$session_data = $login->getSessionData();

				//Iniciamos las variables de session
				$session_data->initializeSessions();

				// FIN
				return new Response("Login satisfactorio.",Response::SUCCESS,self::generateToken());
			}
			// Si no solo se regresa la respuesta
			return $response;
		}catch (Exception $e){
			throw $e;
		}
	}

	/**
	* Construlle el objeto de login dependiendo del tipo de login
	* @param: $tipo_login: El tipo de login que se va a crear
	**/
	public static function loginFactory($tipo_login){
		if($tipo_login === "promotec"){
			return new LoginPromotor();

		}else if($tipo_login === "microtae"){

		}else if($tipo_login === "login3"){
			
		}else if($tipo_login === "login4"){
			
		}else{
			throw new Exception("Tipo de login ".$tipo_login." no es valido.");
		}
	}


	private function generateToken(){
		$length = 20;
    	return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}
