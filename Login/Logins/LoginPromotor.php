<?
class LoginPromotor extends Login{
	public function getSessionData(){
		$user = $this->loginData->usuario;
		$sql="SELECT u.*, l.dis AS suc, l.tipo, l.bloqp from recargas.usuarios AS u left join canal.lista AS l on u.dis=l.user WHERE u.user='$user'";

		$result = mysqli_query($link, $sql);
		if ($row = mysqli_fetch_array($result)) {
			//Crear objeto de session, solo puede existir uno por cada clase.
			$sessionObj = SessionData::Instance();

			$portales = ["promotec" => "pt", "tarifario" => "tar","taf" => "taf","portabilidad" => "p", "ass" => "s", "telemarketing" => "telema" ]; // key is name of portal, value is prefix for the session name
			$campos = ["nom" => "nombre", "user" => "user", "dis" => "dis", "suc" => "suc"]; //Key is sess field name, value is name in row var
			//Este array contendrá el contenido de las combinaciones de los prefijos y campos de arriba
			$partial_data_for_sessions = $this->createArrayCombinations($portales, $sess_fields, $row);
			//Añadir al objecto final de la session
			$sessionbj->addArray($partial_data_for_sessions);
			
			//Finalmente añadir los campos restantes
			
			$aditional_session_vars_promotec = ["ptnom" => ucwords($row["nombre"]), "ptblock" => $row["bloqp"],"ptnivel" => "","ptmesa" => "","ptref" => $row['codigo'],"ptcod" => $row['referido'],"pt" => $row['C14'],"promolog" => $row['C14NC4'],"ptref" => $row['codigo']];
			$aditional_session_vars_tar = ["tartipo" => $row["tipo"],"tar" => md5("1c14nc4")];
			$sessionbj->addArray($aditional_session_vars_tar);

			$aditional_session_vars_taf = ["taftipo" => "0","taf" => md5("1c14nc4")];
			$sessionbj->addArray($aditional_session_vars_taf);

			$aditional_session_vars_porta = ["pref" => $row['codigo'], "pcod" => $row['referido'], "porta" => md5("1c14nc4") ];
			$sessionbj->addArray($aditional_session_vars_porta);

			$aditional_session_vars_ass = ["stípo" => $row["tipo"], "ass" => md5("1c14nc4")];
			$sessionbj->addArray($aditional_session_vars_ass);

			$aditional_session_vars_telemarketing = ["teletipo" => $row["tipo"], "teletipo" => md5("1c14nc4")];
			$sessionbj->addArray($aditional_session_vars_telemarketing);

			return $sessionbj;
		}else{
			throw new Exception("Error al obtener datos para la session");
		}
	}
}
?>