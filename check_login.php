<?php


/**
* Funcion para asignar las sessiones
* param $portales: El array de portales donde el index es su nombre y el key es el prefix que se le assignara a las
* variables de session. 
* param $ sess_fields: El array con las diferentes variables de session que se crearan por cada portal, donde el
* key es el nombre del field, y el value es el valor actual en la variable con los resultados de la base de datos
**/
function crearSessiones($portales, $sess_fields, $row){
	$sess_vars = [];
	foreach ($portales as $prefix) {
		foreach ($sess_fields as $row_field) {
			$_SESSION[$prefix.$field] = $row[$row_field];
			$sess_vars[] = $_SESSION[$prefix.$field];
		}				
	}
}


function security($var){
	return $var;
}

ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");



session_start();
//require('../seguro.php');
//require('link.php');
require('captcha_checker.php');

//Recuperar y dividir datos
$t1=security($_POST["login"]);
$t2=explode(",",$t1);

$usuario=security($t2[0]);
$pass=$t2[1];
$captcha=$t2[2];
$secretKey="6LdZEwcUAAAAAHJK2O6yxnM2cw0C-P7hG5UeC6if";
$ip=$_SERVER['REMOTE_ADDR'];

$captcha_checker  = new CaptchaChecker($secretKey);
$captcha_check = $captcha_checker->validate($captcha,$ip);



//revisamos el captcha
if(!$captcha_check){
		//revisar en recargas.usuarios
		$sql="select id from recargas.usuarios where user='".$usuario."' and (pass=recargas.crypto('$usuario','$pass'))";		
		$result = mysqli_query($link, $sql);
		if ($row = mysqli_fetch_array($result)) {

		    //Crear variables de session
			$portales = ["promotec" => "pt", "tarifario" => "tar","taf" => "taf","portabilidad" => "p", "ass" => "s", "telemarketing" => "telema" ];
			$sess_fields = ["nom" => "nombre", "user" => "user", "dis" => "dis", "suc" => "suc"]; //Key is sess field name, value is name in row var
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
