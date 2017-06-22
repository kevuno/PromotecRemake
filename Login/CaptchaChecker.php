<?php

/**
*  Captcha checker class
*/
class CaptchaChecker{

	/** Adress for the Captcha to be checked at. **/
	const CaptchaAdress = "https://www.google.com/recaptcha/api/siteverify";

	/**
	* Validates the captcha response. An ip is optional
	* @param: $secretKey: The key to be validated
	* @param: $captchaResponse: The actual captcha input data
	* @param: $ipCOde: An optonal parameter for the server ip
	* @return: A boolean, whether the captcha response checking was correct
	**/
	static function validate($secretKey, $captchaResponse, $ip = null){
		$ipCode = "";
		if ($ip) {
			$ipCode = "&remoteip=".$ip;
		}
		$response=file_get_contents(self::CaptchaAdress."?secret=".$secretKey."&response=".$captchaResponse.$ipCode);
		$responseJson=json_decode($response,true);
		return intval($responseJson["success"]) === 1;
	}
}

?>