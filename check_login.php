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
	// revisamos si existe el usuario y pass
	$sql="select u.*, l.dis as suc, l.tipo, l.bloqp from canal.usuarios u left join canal.lista l on u.dis=l.user where u.user='".$usuario."' and u.pass=canal.crypto('".$usuario."','".$pass."')";
	$result = mysqli_query($link, $sql);
	//Checar si usuario es miembro de la mesa administrativa
	if ($row = mysqli_fetch_array($result)){
		//check pass
		$token=$row["token"];
		$id=$row["id"];
		if (is_null($token)){
			$sql="call canal.cryptoc('".$id."','".$ps."')";
			$result = mysqli_query($link,$sql);
		}
		//actualizo ultlog
		$ult=mysqli_query($link, "update canal.usuarios set ultlog=now() where id='$id'");
		//creo sesiones $row["user"];
		if($row["user"]==="L5414C6474" || $row["user"]==="A5471A1514" || $row["user"]==="alex" || $row["user"]==="L5674M2145" || $row["user"]==="E1574M6894"){

			//Crear variables de session
			$portales = ["promotec" => "pt", "tarifario" => "tar","taf" => "taf","portabilidad" => "p", "ass" => "s", "soportec" => "sop_","telemarketing" => "telema" ];
			$sess_fields = ["nom" => "nombre", "user" => "user", "dis" => "dis", "suc" => "suc"]; //Key is sess field name, value is name in row var
			crearSessiones($portales, $sess_fields, $row);

			//Crear variables de session restantes

			//sesiones promotec
			$_SESSION['ptnom'] = ucwords($row["nombre"]);
			$_SESSION['ptnivel'] = $row["nivel"];
			$_SESSION['ptblock'] = $row["bloqp"];
			if ($row["id"]==='131') { $_SESSION['mesa'] = "pu"; } else { $_SESSION['mesa'] = ""; }
			$_SESSION['ptmesa'] = $row["nivel"];
			$_SESSION['promolog'] = "C14NC4";

			//sesiones tarifario
			$usuu=$row["nombre"];
			$_SESSION["tartipo"]=$row["tipo"];
			$_SESSION["tar"]=md5("1c14nc4");
			//sesiones taf
			$_SESSION["taftipo"]=$row["tipo"];
			$_SESSION["taf"]=md5("1c14nc4");
			//sesiones porta
			$_SESSION["porta"]=md5("1c14nc4");
			//sesiones ass
			$_SESSION["stipo"]=$row["tipo"];
			$_SESSION["ass"]=md5("1c14nc4");
			//sesiones soportec estan completas
			//sesiones telemarketing
			$_SESSION["teletipo"]=$row["tipo"];
			$_SESSION["tele"]=md5("1c14nc4");
			//creo token
			$tk=md5($us);
			$_SESSION['tk'] = $tk;
			$sql_tk=mysqli_query($link, "INSERT INTO microtec.tokens (user, pass, token) VALUES ('$usuario', '$pass', '$tk')");

			echo "UValid";
		}else{
			echo "Uinvalid";
		}
	}else{
		//revisar en recargas.usuarios
		$sql="select u.*, l.dis as suc, l.tipo, l.bloqp from recargas.usuarios u left join canal.lista l on u.dis=l.user where u.user='".$usuario."' and (u.pass=recargas.crypto('".$usuario."','".$pass."') or u.pass=md5('".$pass."'))";		
		$result = mysqli_query($link, $sql);
		if ($row = mysqli_fetch_array($result)) {
			$psw=$row['pass'];
			if (strlen($psw)=='32'){
		        $id=$row["id"];
		        $sql="call recargas.cryptoc('".$id."','".$ps."')";
		        $result = mysqli_query($link,$sql);
		    }

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
	}
} else {
	echo "E|Acceso no permitido, debe validar que no es un robot.";
}
?>