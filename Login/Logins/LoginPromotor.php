<?
class LoginPromotor extends Login{

	/** 
	* Construye el objeto llamando al constructor padre de Login.php con el nombre de la bd y de la tabla
	 **/
	public function __construct(){
		parent::__construct("recargas","usuarios");
	}

	/** 
	* Obtiene los datos que seran colocados en las variables de session.
	* @return: Un objecto SessionData.
	**/
	public function getSessionData(){
		$user = $this->loginData->user;
		$sql="SELECT u.*, l.dis AS suc, l.tipo, l.bloqp from recargas.usuarios AS u left join canal.lista AS l on u.dis=l.user WHERE u.user='$user'";
		if(!$result = mysqli_query($this->link, $sql)){
			throw new Exception("Error al ejecutar consulta mysql ".$sql);
		}

		if ($row = mysqli_fetch_array($result)) {
			//Crear objeto de session, solo puede existir uno por cada clase, por eso se llama a la funcion Instance
			$sessionObj = SessionData::Instance();

			$portales = ["promotec" => "pt", "tarifario" => "tar","taf" => "taf","portabilidad" => "p", "ass" => "s", "telemarketing" => "telema" ]; // key is name of portal, value is prefix for the session name
			$campos = ["nombre" => "nombre", "user" => "user", "dis" => "dis", "suc" => "suc"]; //Key is sess field name, value is name in row var
			//Este array contendrá el contenido de las combinaciones de los prefijos y campos de arriba
			$partial_data_for_sessions = SessionData::createArrayCombinations($portales, $campos, $row);
			//Añadir al objecto final de la session
			$sessionObj->addArray($partial_data_for_sessions);
			
			//Finalmente añadir los campos restantes
			$aditional_session_vars_promotec = ["ptnom" => ucwords($row["nombre"]), "ptblock" => $row["bloqp"],"ptnivel" => "","ptmesa" => "","ptref" => $row['codigo'],"ptcod" => $row['referido'],"ptref" => $row['codigo'],"pt" => 'C14',"promolog" => 'C14NC4'];
			$aditional_session_vars_tar = ["tartipo" => $row["tipo"],"tar" => md5("1c14nc4")];			
			$aditional_session_vars_taf = ["taftipo" => "0","taf" => md5("1c14nc4")];
			$aditional_session_vars_porta = ["pref" => $row['codigo'], "pcod" => $row['referido'], "porta" => md5("1c14nc4")];
			$aditional_session_vars_ass = ["stípo" => $row["tipo"], "ass" => md5("1c14nc4")];
			$aditional_session_vars_telemarketing = ["teletipo" => $row["tipo"], "teletipo" => md5("1c14nc4")];
			$sessionObj->addArray($aditional_session_vars_promotec);
			$sessionObj->addArray($aditional_session_vars_tar);
			$sessionObj->addArray($aditional_session_vars_taf);
			$sessionObj->addArray($aditional_session_vars_porta);
			$sessionObj->addArray($aditional_session_vars_ass);
			$sessionObj->addArray($aditional_session_vars_telemarketing);
			return $sessionObj;
		}else{
			throw new Exception("Error al obtener datos para la session");
		}
	}
}
?>