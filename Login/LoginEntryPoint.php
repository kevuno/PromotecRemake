<?php
session_start();
require('../seguro.php');
require('../link.php');
require('../Response.php');
require('LoginData.php');
require('LoginMain.php');


//Recuperar datos
$user = security($_POST["user"]);
$pass = security($_POST["pass"]);
$captcha = security($_POST["captcha"]);

// De donde proviene el login
$provider = "promotec";

// Constates para el captcha
$ip = "52.42.194.160"; //$_SERVER["serverdata"],
$secretKey = "6LdZEwcUAAAAAHJK2O6yxnM2cw0C-P7hG5UeC6if"; //Secret key for captcha

// Call al Main login center
try{
	$loginData = new LoginData($user,$pass,$provider);
	$captchaData = new CaptchaData($captcha,$secretKey,$ip);
	$response = LoginMain::loginGeneral($loginData,$captchaData);
	echo $response->toJson();
}catch (Exception $e){
	echo Response::errorResponseFromException($e)->toJson();

}




?>
