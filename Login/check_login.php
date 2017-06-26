<?php

ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");
session_start();
//require('seguro.php');
require('../link.php');

require('LoginData.php');
require('LoginMain.php');
//Recuperar datos
$raw_data = $_POST["login"];
$fields = explode(",",$raw_data);
$usuario = $fields[0];
$pass = $fields[1];
$provider = "promotec";
$ip = $ip = "52.42.194.160"; //$_SERVER["serverdata"],

$captcha = $fields[2];
$secretKey = "6LdZEwcUAAAAAHJK2O6yxnM2cw0C-P7hG5UeC6if"; //Secret key for captcha

/* Call al Main login center */
$response;
try{
	$response = LoginMain::loginGeneral(new LoginData($usuario,$pass,$provider), new CaptchaData($captcha,$secretKey,$ip));
	echo "R|".$response."|";
}catch (Exception $e){
	echo "E|".$e->getMessage()."|";

}




?>
