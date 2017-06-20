<?php
class LoginMain{

	/**
	* Funcion que ejecuta todas las funciones necesarias para hacer login ejecutando el login principal en
	* en la base de datos base y despues obtiene los datos de sesión dependiendo del login del cual se trate.
	* @param: $LoginData: El objecto del login: Incluye user,pass,data del captcha e ip.
	* @param: $LoginData: El objecto del captcha: Incluye captcha e ip.
	*/
	public static function loginGeneral($loginData,$captchaData){
		//Validar captcha
		$captcha_checker = new CaptchaChecker($loginData->secretKey);
		if(!$captchaData->validate()){
			return new Response("No se pudo validar el captcha");
		}
		//Construir el login correspondiente y ejecutar intento de login
		$login = loginFactory($loginData->tipo);
		return $login->login($loginData);



	}

	public static function loginFactory($tipo_login){
		if($tipo_login === "promotec"){
			return new PromotorLogin();

		}else if($tipo_login === "microtae"){

		}else if($tipo_login === "login3"){
			
		}else if($tipo_login === "login4"){
			
		}
	}
}

