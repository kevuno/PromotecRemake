<? 
require('Logins/LoginPromotor.php');
require('BlockUserMiddleware.php');
abstract class Login {
	/** El objecto LoginData usado para hacer login en el sistema **/
	protected $loginData;

	/** La base de datos del login**/
	protected $login_db;

	/** La tabla del login**/
	protected $login_table;

	/** Objecto de respuesta **/
	protected $response;

	/** Database connection link **/
	protected $link;

	/** Block Middleware **/
	public $blockMiddleware;


	/** Object constructor with optional parameters**/
	function __construct($login_db,$login_table){
		$this->login_db = $login_db;
		$this->login_table = $login_table;
		$this->blockMiddleware = new BlockUserMiddleware();
	}

	/** 
	* Funcion que realiza la llamada a la base de datos y checa si se pudo hacer login o no.
	* @return: Un objecto de Error o un objecto de Respuesta sobre el resultado del intento de login y de inicialzacion   *	de las variables de session
	**/
	function login(){
		$user = $this->loginData->user;
		$pass = $this->loginData->pass;
		$sql="SELECT id FROM $this->login_db.$this->login_table WHERE user='$user' AND (pass=recargas.crypto('$user','$pass'))";
		$result = mysqli_query($this->link, $sql);
		if($row = mysqli_fetch_array($result)){
			return new Response("Login was successful");
		}
		$_SESSION["tries"] += 1;
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
	public static function createArrayCombinations($prefjios, $campos, $data){
		$final_array = [];
		foreach ($prefjios as $prefix){
			foreach ($campos as $field => $row_field){
				$final_array[$prefix.$field] = $data[$row_field];
			}				
		}
		return $final_array;
	}

	/**
	* Sets the mysqli link object to the login and to the middleware
	* @param: The mysqli link object
	**/
	function setLink(mysqli $link){
		$this->link = $link;
		isset($this->blockMiddleware){
			$this->blockMiddleware->setLink($link);
		}
	}

	/**
	* Sets the Login data
	* @param: The login data
	**/
	function setData(LoginData $loginData){
		$this->loginData = $loginData;
	}

	function setMiddleware($middleware){
		try{
			$block = new BlockUserMiddleware($this->login_db,$this->login_table,$this->link);
			$block->checkBlockAccount();
		}catch(Exception $e){
			throw new Exception($e->getMessage());
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
            $inst-> sessionFieldsandData = array();
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
	* @param $fieldName: El array con informacion de variables de session
	**/
	public function addArray($fieldName_data){
		$this->sessionFieldsandData = array_merge($this->sessionFieldsandData,$fieldName_data);
	}

	/**
	* Inicializa las varibles de tipo $_SESSION de acuerdo a la informacion recopilada
	* @return: Objector de respuesta final
	**/
	public function initializeSessions(){
		foreach ($this->sessionFieldsandData as $field => $data) {
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