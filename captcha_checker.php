<?php

/**
*  Captcha checker class
*/
class CaptchaChecker{
	private $secretKey;
	const CaptchaAdress = "https://www.google.com/recaptcha/api/siteverify";

	function __construct($secretKey){
		$this->secretKey = $secretKey;
	}
	/**
	* Validates the captcha response. An ip is optional
	* @return: A boolean, whether the captcha response checking was correct
	**/
	function validate($response,$ip = null){
		echo "testing";
		echo $response;
		echo $this->key;
		$ipCode = "";
		if ($ip) {
			$ipcode = "&remoteip=".$ip;
		}
		$response=file_get_contents(self::CaptchaAdress."?secret=".$secretKey."&response=".$response.$ipcode);
		$responseJson=json_decode($response,true);
		return intval($responseJson["success"]) === 1;
	}
}

?>