<?php
require('Response.php');
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
		$captcha_checker = $captchaData->validate();
		//if(!$captchaData->validate()){
		//	throw new Exception("No se pudo validar el captcha");
		//}
		try{
			//Construir el login correspondiente y ejecutar intento de login
			$login = self::loginFactory($loginData->tipo);
			$login->setData($loginData);
			// Intentar hacer login
			return $response = $login->login();	
		}catch (Exception $e){
			throw new Exception($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	* Construlle el objeto de login dependiendo del tipo de login
	* @param: $tipo_login: El tipo de login que se va a crear
	**/
	public static function loginFactory($tipo_login){
		if($tipo_login === "promotec"){
			return new LoginPromotor("recargas","usuarios","fields","session_vars");

		}else if($tipo_login === "microtae"){

		}else if($tipo_login === "login3"){
			
		}else if($tipo_login === "login4"){
			
		}else{
			throw new Exception("Tipo de login ".$tipo_login." no es valido.");
		}
	}
}

