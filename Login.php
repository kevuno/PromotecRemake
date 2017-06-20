<? 
abstract class Login{
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


	/** Object constructor **/
	function __construct($login_db,$login_table,$fields_from_query,$session_vars){
		$this->login_db = $login_db;
		$this->login_table = $login_table;
		$this->fields_from_query = $fields_from_query;
		$this->session_vars = $session_vars;
	}

	/** 
	* Funcion que realiza la llamada a la base de datos y checa si se pudo hacer login o no.
	* @return: Un objecto de Respuesta sobre el resultado del intento de login.
	**/
	function login(LoginData $loginData){
		$sql="SELECT id FROM '$login_db'.'$login_table' WHERE user='$loginData->user' AND (pass=recargas.crypto('$loginData->user','$loginData->$pass'))";
		$result = mysqli_query($link, $sql);
		if($row = mysqli_fetch_array($result)){
			//Obtenemos las variables que seran de tipo $_SESSION
			$session_data = $this->getSessionData();

			//Checamos que si ya haya compeltado la consulta y se hayan construido el objecto con la informacion
			if(!$session_data instanceof SessionData){
				return new Response("Ocurrió un problema al momento de recolectar informacion para la session");
			}
			//Iniciamos las variables de session
			$session_data->initializeSessions();
			return new Response("Se ha iniciado session");
		}
		return new Response("Inicio de session incorrecto, checar usuario y contraseña");
	}

	/** 
	* Funcíon que regresara un objecto con todas las variables de session
	* @return Objecto de Respuesta de Session.
	**/

	abstract protected function getSessionData();


	/**
	* Funcion para asignar las sessiones
	* @param $portales: El array de portales donde el index es su nombre y el key es el prefix que se le assignara a las
	* variables de session. 
	* @param $sess_fields: El array con las diferentes variables de session que se crearan por cada portal, donde el
	* key es el nombre del field, y el value es el valor actual en la variable con los resultados de la base de datos
	* @param $data: Los datos en concreto que seran guardados en las variables de session 
	**/
	public static function crearSessiones($portales, $sess_fields, $data){
		$sess_vars = [];
		foreach ($portales as $prefix) {
			foreach ($sess_fields as $row_field) {
				$_SESSION[$prefix.$field] = $data[$row_field];
				$sess_vars[] = $_SESSION[$prefix.$field];
			}				
		}
		return $sess_vars;
	}



}



class SessionData{
	/** Array con los nombres de los campos para las variables de session como key, y el data de la session como value**/
	private $sessionFieldsandData;



	public function add($fieldName,$data){
		$this->sessionFieldsandData[$fieldName] = $data;
	}

	/**
	*
	**/
	private function initializeSessions(){
		foreach ($sessionFieldsandData as $field => $data) {
			# code...
		}
	}
}


?>