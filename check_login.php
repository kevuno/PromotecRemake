<?php
ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");
session_start();
require('../seguro.php');
require('link.php');

//recupero datos
$t1=security($_POST["login"]);
//divido datos
$t2=explode(",",$t1);

$us=security($t2[0]);
$ps=$t2[1];
$cp=$t2[2];
$valr=null;
$secretKey="6LdZEwcUAAAAAHJK2O6yxnM2cw0C-P7hG5UeC6if";
$ip=$_SERVER['REMOTE_ADDR'];
$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$cp."&remoteip=".$ip);
$responseKeys=json_decode($response,true);
if(intval($responseKeys["success"]) !== 1) {
	$valr='S';
} else {
	$valr='V';
}
//revisamos el captcha
if ($valr==="V") {
	// revisamos si existe el usuario y pass
	$sql="select u.*, l.dis as suc, l.tipo, l.bloqp from canal.usuarios u left join canal.lista l on u.dis=l.user where u.user='".$us."' and u.pass=canal.crypto('".$us."','".$ps."')";
	$result = mysqli_query($link, $sql);
	if ($row = mysqli_fetch_array($result)) {
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

			//sesiones promotec
			$_SESSION['ptnom'] = ucwords($row["nombre"]);
			$_SESSION['ptuser'] = $row["user"];
			$_SESSION['ptdis'] = $row["dis"];
			$_SESSION['ptsuc'] = $row["suc"];
			$_SESSION['ptnivel'] = $row["nivel"];
			$_SESSION['ptblock'] = $row["bloqp"];
			if ($row["id"]==='131') { $_SESSION['mesa'] = "pu"; } else { $_SESSION['mesa'] = ""; }
			$_SESSION['ptmesa'] = $row["nivel"];
			$_SESSION['promolog'] = "C14NC4";

			//sesiones tarifario
			$usuu=$row["nombre"];
			$_SESSION["tarnombre"]=$row["nombre"];
			$_SESSION["taruser"]=$row["user"];
			$_SESSION["tardis"]=$row["dis"];
			$_SESSION["tarsuc"]=$row["suc"];
			$_SESSION["tartipo"]=$row["tipo"];
			$_SESSION["tar"]=md5("1c14nc4");

			//sesiones taf
			$_SESSION["tafnombre"]=$row["nombre"];
			$_SESSION["tafuser"]=$row["user"];
			$_SESSION["tafdis"]=$row["dis"];
			$_SESSION["tafsuc"]=$row["suc"];
			$_SESSION["taftipo"]=$row["tipo"];
			$_SESSION["taf"]=md5("1c14nc4");

			//sesiones porta
			$_SESSION["pnombre"]=$row["nombre"];
			$_SESSION["puser"]=$row["user"];
    	$_SESSION["pdis"]=$row["dis"];
    	$_SESSION["psuc"]=$row["suc"];
    	$_SESSION["porta"]=md5("1c14nc4");

			//sesiones ass
			$_SESSION["snombre"]=$row["nombre"];
			$_SESSION["suser"]=$row["user"];
    	$_SESSION["sdis"]=$row["dis"];
    	$_SESSION["ssuc"]=$row["suc"];
			$_SESSION["stipo"]=$row["tipo"];
    	$_SESSION["ass"]=md5("1c14nc4");

			//sesiones soportec
			$_SESSION["sop_nombre"]=$row["nombre"];
			$_SESSION["sop_user"]=$row["user"];
			$_SESSION["sop_dis"]=$row["dis"];
			$_SESSION["sop_suc"]=$row["suc"];

			//sesiones telemarketing
			$_SESSION["telemanombre"]=$row["nombre"];
			$_SESSION["telemauser"]=$row["user"];
    		$_SESSION["telemadis"]=$row["dis"];
        	$_SESSION["telemasuc"]=$row["suc"];
        	$_SESSION["teletipo"]=$row["tipo"];
    		$_SESSION["tele"]=md5("1c14nc4");

			//creo token
			$tk=md5($us);
			$_SESSION['tk'] = $tk;
			$sql_tk=mysqli_query($link, "INSERT INTO microtec.tokens (user, pass, token) VALUES ('$us', '$ps', '$tk')");

			echo "UValid";
		} else {
			echo "Uinvalid";
		}
	} else {
		//revisar en recargas.usuarios
		$sql="select u.*, l.dis as suc, l.tipo, l.bloqp, DATEDIFF(now(),u.fechaup) AS dias from recargas.usuarios u left join canal.lista l on u.dis=l.user where u.user='".$us."' and (u.pass=recargas.crypto('".$us."','".$ps."') or u.pass=md5('".$ps."'))";
		
		$result = mysqli_query($link, $sql);
		if ($row = mysqli_fetch_array($result)) {
			$psw=$row['pass'];
			if (strlen($psw)=='32'){
        $id=$row["id"];
        $sql="call recargas.cryptoc('".$id."','".$ps."')";
        $result = mysqli_query($link,$sql);
      }
			$dias=$row['dias'];
			//sesiones promotec
			$_SESSION['ptnom'] = ucwords($row["nombre"]);
			$_SESSION['ptuser'] = $row["user"];
			$_SESSION['ptdis'] = $row["dis"];
			$_SESSION['ptsuc'] = $row["suc"];
			$_SESSION['ptblock'] = $row["bloqp"];
			$_SESSION['ptnivel'] = "";
			$_SESSION['ptmesa'] = "";
			$_SESSION['ptref'] = $row['codigo'];
			$_SESSION['ptcod'] = $row['referido'];
			$_SESSION['pt'] = "C14";
			$_SESSION['promolog'] = "C14NC4";

			//sesiones tarifario
			$usuu=$row["nombre"];
			$_SESSION["tarnombre"]=$row["nombre"];
			$_SESSION["taruser"]=$row["user"];
			$_SESSION["tardis"]=$row["dis"];
			$_SESSION["tarsuc"]=$row["suc"];
			$_SESSION["tartipo"]=$row["tipo"];
			$_SESSION["tar"]=md5("1c14nc4");

			//sesiones taf
			$_SESSION["tafnombre"]=$row["nombre"];
			$_SESSION["tafuser"]=$row["user"];
			$_SESSION["tafdis"]=$row["dis"];
			$_SESSION["tafsuc"]=$row["suc"];
			$_SESSION["taftipo"]="0";
			$_SESSION["taf"]=md5("1c14nc4");

			//sesiones porta
			$_SESSION["pnombre"]=$row["nombre"];
			$_SESSION["puser"]=$row["user"];
    	$_SESSION["pdis"]=$row["dis"];
    	$_SESSION["psuc"]=$row["suc"];
			$_SESSION['pref']=$row['codigo'];
			$_SESSION['pcod']=$row['referido'];
			$_SESSION["porta"]=md5("1c14nc4");

			//sesiones ass
			$_SESSION["snombre"]=$row["nombre"];
			$_SESSION["suser"]=$row["user"];
    	$_SESSION["sdis"]=$row["dis"];
    	$_SESSION["ssuc"]=$row["suc"];
			$_SESSION["stipo"]=$row["tipo"];
    	$_SESSION["ass"]=md5("1c14nc4");

			//sesiones telemarketing
			$_SESSION["telemanombre"]=$row["nombre"];
			$_SESSION["telemauser"]=$row["user"];
    		$_SESSION["telemadis"]=$row["dis"];
        	$_SESSION["telemasuc"]=$row["suc"];
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