<?php


ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");
session_start();
require('seguro.php');
require('link.php');
require('CapthcaChecker.php');
require('LoginData.php');

//Recuperar datos
$raw_data = security($_POST["login"]);
$fields = explode(",",$raw_data);

$usuario = security($tfields[0]);
$pass = $tfields[1];
$provider = "promotec"

$captcha = $tfields[2];
$secretKey = "6LdZEwcUAAAAAHJK2O6yxnM2cw0C-P7hG5UeC6if"; //Secret key for captcha


return LoginMain::loginGeneral(new LoginData($usuario,$pass,$provider), new CaptchaData($captcha,$secretKey,$ip));

		
	    //Crear variables de session
	    // key is name of portal, value is prefix for the session name
		$portales = ["promotec" => "pt", "tarifario" => "tar","taf" => "taf","portabilidad" => "p", "ass" => "s", "telemarketing" => "telema" ];
		$sess_fields = ["nom" => "nombre", "user" => "user", "dis" => "dis", "suc" => "suc"]; //Key is sess field name, value is name in row var
		$aditional_session_vars = ["ptnom" => ucwords($row["nombre"]), "ptblock" => $row["bloqp"],]


		crearSessiones($portales, $sess_fields, $row);

		//sesiones promotec
		$_SESSION['ptnom'] = ucwords($row["nombre"]);
		$_SESSION['ptblock'] = $row["bloqp"];
		$_SESSION['ptnivel'] = "";
		$_SESSION['ptmesa'] = "";
		$_SESSION['ptref'] = $row['codigo'];
		$_SESSION['ptcod'] = $row['referido'];
		$_SESSION['pt'] = "C14";
		$_SESSION['promolog'] = "C14NC4";

		//sesiones tarifario
		$_SESSION["tartipo"]=$row["tipo"];
		$_SESSION["tar"]=md5("1c14nc4");

		//sesiones taf
		$_SESSION["taftipo"]="0";
		$_SESSION["taf"]=md5("1c14nc4");

		//sesiones porta
		$_SESSION['pref']=$row['codigo'];
		$_SESSION['pcod']=$row['referido'];
		$_SESSION["porta"]=md5("1c14nc4");

		//sesiones ass
		$_SESSION["stipo"]=$row["tipo"];
		$_SESSION["ass"]=md5("1c14nc4");

		//sesiones telemarketing
		$_SESSION["teletipo"]=$row["tipo"];
		$_SESSION["tele"]=md5("1c14nc4");

		echo "UValid"."|*|".$row["nombre"]."|*|".$_SESSION["taftipo"];
	} else {
		echo "Error".$sql;
	}
} else {
	echo "E|Acceso no permitido, debe validar que no es un robot.";
}
?>
