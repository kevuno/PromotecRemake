<? 
abstract class Login {
	/** El objecto LoginData usado para hacer login en el sistema **/
	protected $loginData;

	/** La base de datos del login**/
	protected $login_db;

	/** La tabla del login**/
	protected $login_table;

	/** Los campos que deberan de ser seleccionados en la query de informacion**/
	protected $fields_from_query;	

	/** Las variables de session del objecto**/
	protected $session_vars;

	/** Objecto de respuesta **/
	protected $response;


	/** Object constructor **/
	function __construct($login_db,$login_table,$fields_from_query,$session_vars){
		$this->login_db = $login_db;
		$this->login_table = $login_table;
		$this->fields_from_query = $fields_from_query;
		$this->session_vars = $session_vars;
	}

	/** 
	* Funcion que realiza la llamada a la base de datos y checa si se pudo hacer login o no.
	* @return: Un objecto de Error o un objecto de Respuesta sobre el resultado del intento de login y de inicialzacion   *	de las variables de session
	**/
	function login(){
		$user = $this->loginData->user;
		$pass = $this->loginData->pass;
		$sql="SELECT id FROM '$this->db'.'$this->login_table' WHERE user='$user' AND (pass=recargas.crypto('$user','$pass'))";
		$result = mysqli_query($link, $sql);
		if($row = mysqli_fetch_array($result)){
			//Obtenemos las variables que seran de tipo $_SESSION
			$session_data = $this->getSessionData();
			//Checamos que si ya haya compeltado la consulta y se hayan construido el objecto con la informacion. 
			 // Si no ocurrio ningun error, iniciamos las variables de session
			return ($session_data instanceof Error) ? $session_data : $session_data->initializeSessions();
			
		}
		return new Error("Inicio de session incorrecto, checar usuario y contraseña","Login.php->login()");
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
			foreach ($campos as $field-> $row_field){
				$final_array[] = $data[$row_field];
			}				
		}
		return $final_array;
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
				return new Error("There was a problem initializing a session variable- variable name: ".$field." with value: ".$data,"Login.php -> initializeSessions()")
			}
		}
	}
	return new Respose("Se han inicializado las variables de session. Login Completado");
}


?>