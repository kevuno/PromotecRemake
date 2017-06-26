<? 
require('Logins/LoginPromotor.php');
abstract class Login {
	/** El objecto LoginData usado para hacer login en el sistema **/
	protected $loginData;

	/** La base de datos del login**/
	protected $login_db;

	/** La tabla del login**/
	protected $login_table;

	/** Intentos de login **/
	public $login_tries;

	/** Objecto de respuesta **/
	protected $response;



	/** Object constructor with optional parameters**/
	function __construct($login_db,$login_table,LoginData $loginData = null){
		$this->login_db = $login_db;
		$this->login_table = $login_table;
		$this->login_tries = $_SESSION["intentos"];
		//Optional
		$this->loginData = $loginData;
	}
	/**
	* Sets the Login data
	* @param: The login data
	**/
	function setData(LoginData $loginData){
		$this->loginData = $loginData;
	}
	/** 
	* Funcion que realiza la llamada a la base de datos y checa si se pudo hacer login o no.
	* @return: Un objecto de Error o un objecto de Respuesta sobre el resultado del intento de login y de inicialzacion   *	de las variables de session
	**/
	function login(){
		// Bloquear cuenta despues de 5 intentos de hacer login
		if($this->login_tries >= 5){
			$message = $this->blockAccount();
			throw new Exception("$message");
		}


		$user = $this->loginData->user;
		$pass = $this->loginData->pass;
		$sql="SELECT id FROM '$this->login_db'.'$this->login_table' WHERE user='$user' AND (pass=recargas.crypto('$user','$pass'))";
		$result = mysqli_query($link, $sql);
		if($row = mysqli_fetch_array($result)){
			//Obtenemos las variables que seran de tipo $_SESSION
			try{
				$session_data = $this->getSessionData();	
			}catch(Exception $e){
				//Ocurrio algun error obteniendo la informacion de la session
				throw new Exception($e->getMessage(), $e->getCode(), $e);
			}			
			//Iniciamos las variables de session
			return $session_data->initializeSessions();
			
		}
		$_SESSION["intentos"] += 1;
		throw new Exception("Inicio de session incorrecto, checar usuario y contraseña");
	}

	/** 
	* Funcíon que buscará en la base de datos los datos necesarios para hacer las variables de session
	* y regresara un objecto con todas las variables de session. Debera de ser implementada por separado por cada login.
	* @return Objecto de Respuesta de Session.
	**/

	abstract protected function getSessionData();


	/**
	* Funcion para crear una combinacion de informacion en un array a travez de una lista de prefijos, una lista de 
	* campos que conecta el nombre de los keys que tendra el array final y los keys en el array que contiene la 
	* informacion.
	* Ejemplo:
	* $prefijos = ["promotec" => "pt", "tarifario" => "tar","taf" => "taf","portabilidad" => "p", "ass" => 
	* "s", "telemarketing" => "telema" ];
	* $campos = ["nom" => "nombre", "user" => "user", "dis" => "dis", "suc" => "suc"]; //key es nombre de la variable final, y el valor es el nombre del key en la variable data:
	*  $["pt"."nom"] = $data["nombre"], lo cual deja como resultado $["ptnom"] = $data["nombre"];
	* @param $prefjios: El array de portales donde el index es su nombre y el key es el prefix que se le assignara a las
	* variables de session. 
	* @param $campos: El array con las diferentes variables de session que se crearan por cada portal, donde el
	* key es el nombre del campo que tendra en el array final, y el value es el nombre del key en el array $data
	* @param $data: Los datos en concreto que seran guardados en las variables de session 
	**/
	public static function createArrayCombinations($prefjios, $sess_fields, $data){
		$final_array = [];
		foreach ($prefjios as $prefix){
			foreach ($campos as $field => $row_field){
				$final_array[] = $data[$row_field];
			}				
		}
		return $final_array;
	}


	/** 
	* Bloquea la cuenta del usuario si es que existe y envia un NIP si no hay ninguno activo
	* @return: Mensaje de respuesta dependiendo la situacion
	**/
	public function blockAccount(){
		$us=$this->loginData->user;
		//fecha
		date_default_timezone_set('America/Mexico_City');
		$fecha=date ("Y-m-d H:i:s");
		$fecha2=date ("Y-m-d");

		$sql=mysqli_query($link, "SELECT u.id, l.cel from multi.usuarios u left join canal.lista l on u.dis=l.user where u.user='$us'");

		if ($row=mysqli_fetch_array($sql)){
			$cel=$row['cel'];
			if(!empty($cel)){
				$checkNip="SELECT nip from multi.nips where user='$us' and status='1' and fecha like '$fecha2%'";
				$result=mysqli_query($link, $checkNip);
				if (mysqli_num_rows($result)>0) {
					$row2=mysqli_fetch_array($result);
					$nip=$row2['nip'];
				}else{
					function gen_nip($minimo=4, $maximo=4) {
						$num = "";
						$digitos = "0123456789";
						for ($i = 0; $i < rand($minimo, $maximo); $i++) {
							$num .= $digitos[rand(0, strlen($digitos) - 1)];
						}
						return $num;
					}				
					$nip=gen_nip();

					  //asigno pass
					$creaNip="INSERT into multi.nips (user, numero, nip, fecha, status, app)values('$us','$cel','$nip','$fecha','1','WiMO-Web')";
					mysqli_query($link, $creaNip);
				}
				if(!empty($nip)){
					$txt = "El NIP para recuperar la clave del usuario $us es $nip";

					$messg = "insert into SMSServer.MessageOut (MessageTo,MessageText) values ('52$cel','$txt')";
					//echo $nip;
					mysqli_query($link,$messg);
					echo "S|Se creo NIP |cambio_contrasena|alert-success|regresar";
				}else{
					echo "E2|Ocurrio un error al generar NIP!|cambio_contrasena|alert-danger|Reintente";
				}
			}else{
			 echo "E2|Su usuario no cuenta con algún número celular, por favor póngase en contacto al: 2227927811 con el área de soporte para poder actualizar su número celular.!|cambio_contrasena|alert-warning|Reintente"; 
			}
		}else{
			echo "E2|El Usuario no existe!|cambio_contrasena|alert-danger|Reintente";
		}
	}
}

/**
* Una clase que representa toda la informacion que tendra las variables de session.
* Utiliza el design pattern de un singleton
**/
class SessionData {

	/** Array con los nombres de los campos para las variables de session como key, y el data de la session como value **/
	public $sessionFieldsandData;

	private function __construct(){}
	
    /**
     * Llamar a esta funcion para obtener la instancia
     *
     * @return UserFactory
     */
    public static function Instance(){
    	//Creandon la instancia por primera vez tendrá a $inst = null. Las proximas veces $inst será la instancia como tal
    	// a pesar de llamar "static $inst = null" cada vez que se quiere obtener la instancia.
        static $inst = null;
        if ($inst === null) {
            $inst = new SessionData();
        }
        return $inst;
    }

	/**
	* Añade al array de informacion
	* @param $fieldName: El nombre del campo que tendrá la variable de session
	* @param $data: El contenido para que contendra dicha variable de session
	**/
	public function add($fieldName,$data){
		$this->sessionFieldsandData[$fieldName] = $data;
	}
	/**
	* Añade al array de informacion
	* @param $fieldName: El nombre del campo que tendrá la variable de session
	* @param $data: El contenido para que contendra dicha variable de session
	**/
	public function addArray($fieldName_data){
		$this->sessionFieldsandData = $this->sessionFieldsandData + $fieldName_data;
	}

	/**
	* Inicializa las varibles de tipo $_SESSION de acuerdo a la informacion recopilada
	* @return: Objector de respuesta final
	**/
	private function initializeSessions(){
		foreach ($sessionFieldsandData as $field => $data) {
			$_SESSION[$field] = $data;
			//Testing that the variable session is working
			if($_SESSION[$field] != $data){
				throw new Exception("Ocurrio un problema iniciando una variable de session - nombre del campo: ".$field." con valor: ".$data);
			}
		}
		return new Response("Se han inicializado las variables de session. Login Completado");
	}
	
}


?>