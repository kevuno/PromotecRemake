<?php
require_once('../link.php');
require('Login.php');

class LoginMain{
	/**
	* Funcion que ejecuta todas las funciones necesarias para hacer login ejecutando el login principal en
	* en la base de datos base y despues obtiene los datos de sesión dependiendo del login del cual se trate.
	* @param: $LoginData: El objecto del login: Incluye user,pass,data del captcha e ip.
	* @param: $LoginData: El objecto del captcha: Incluye captcha e ip.
	*/
	public static function loginGeneral(LoginData $loginData, CaptchaData $captchaData){
		//Validar captcha
		if(!$captchaData->validate()){
			return new Response("No se pudo validar el captcha, favor de refrescar la página",Response::ERROR_CAPTCHA);
		}
		try{
			// Construir el login correspondiente y asiganar objecto de link e informacion de usuario y pass
			$login = self::loginFactory($loginData->tipo);
			$login->setLink(link::getLink());
			$login->setData($loginData);

			// Intentar hacer login
			$response = $login->login();

			// Si el login tuvo exito se obtienen las variables de session y el token
			if($response->getStatus() == Response::SUCCESS){
				//Obtenemos las variables que seran de $_SESSION (diferentes para cada tipo de login)
				$session_data = $login->getSessionData();

				//Iniciamos las variables de session
				$session_data->initializeSessions();
				return new Response("Login satisfactorio.",Response::SUCCESS);
			}
			// Si no solo se regresa la respuesta
			return $response;
		}catch (Exception $e){
			throw $e;
		}
	}

	/**
	* Construlle el objeto de login dependiendo del tipo de login
	* @param: $login_tipo: El tipo de login que se va a crear
	**/
	public static function loginFactory($login_tipo){
		if($login_tipo === "promotec"){
			return new LoginPromotor();
		}else if($login_tipo === "microtae"){
			return new LoginMicroTae();
		}else if($login_tipo === "micropay"){
			return new LoginMicroPay();
		}else if($login_tipo === "samtec"){
			return new LoginSamtec();
		}else{
			throw new Exception("Tipo de login ".$login_tipo." no es valido.");
		}
	}
}
