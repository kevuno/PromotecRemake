<?php


ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");
session_start();
require('seguro.php');
require('link.php');
require('CaptchaChecker.php');
require('LoginMain.php');
require('Login.php');

//Recuperar datos
$raw_data = security($_POST["login"]);
$fields = explode(",",$raw_data);

$usuario = security($tfields[0]);
$pass = $tfields[1];
$provider = "promotec"

$captcha = $tfields[2];
$secretKey = "6LdZEwcUAAAAAHJK2O6yxnM2cw0C-P7hG5UeC6if"; //Secret key for captcha


return json_encode(LoginMain::loginGeneral(new LoginData($usuario,$pass,$provider), new CaptchaData($captcha,$secretKey,$ip)));

?>
