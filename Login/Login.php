<? 
// Logins 
require('Logins/LoginPromotor.php');
require('Logins/LoginSamtec.php');
require('Logins/LoginMicroTae.php');
require('Logins/LoginMicroPay.php');
// Otras classes
require('BlockUserMiddleware.php');
require('SessionData.php');
abstract class Login {
	/** El objecto LoginData usado para hacer login en el sistema **/
	protected $loginData;

	/** La base de datos del login**/
	public $db;

	/** La tabla del login**/
	public $table;

	/** Objecto de respuesta **/
	protected $response;

	/** Link de conexion de BD**/
	protected $link;

	/** Block Middleware **/
	public $blockMiddleware;


	/**
	* Construye el objecto de cualquier tipo de login
	* @param $db y $table: La base de datos y tabla del login en concreto.
	**/
	function __construct($db,$table){
		$this->db = $db;
		$this->table = $table;
		$this->blockMiddleware = new BlockUserMiddleware();
	}

	/** 
	* Funcion que realiza la llamada a la base de datos y checa si se pudo hacer login o no.
	* Antes de hacer login checa el estatus del bloqueo de la ip en la base de datos.
	* Al finalizar el intento del login se actualiza el estado del bloqueo
	* @return: Un objecto de Respuesta sobre el resultado del intento de login y chequeo de bloqueo.
	**/
	function login(){
		try{
			// Checar el estado del bloqueo, si el usuario esta bloqueado, regresara una respuesta de tipo LOGIN_BLOCK y brincara el intento de login.
			$check_response = $this->blockMiddleware->checkBlockIP();
			if($check_response->status == RESPONSE::LOGIN_BLOCK){
				return $check_response;
			}

			// No esta bloqueado el usuario asi que continua intentando hacer login
			$user = $this->loginData->user;
			$pass = $this->loginData->pass;
			$db = $this->db;
			$table = $this->table;
			$link = $this->link;
			if($self::intentarLogin($user,$pass,$db,$table,$link)){
				// Login logrado, actualizar estado de bloqueo, se ignoran los intentos restantes que devuelve updateBlockIP()
				$this->blockMiddleware->updateBlockIP(true);
				return new Response("Login basico fue exitoso",Response::SUCCESS);
			}
		} catch (Exception $e){
			throw $e;
		}

		// Login no fue logrado entonces se actualiza el objeto de bloqueo
		$tries_left = $this->blockMiddleware->updateBlockIP(false);

		// Si el numero de intentos es 0 enviar un mensaje de bloqueo
		if ($tries_left == 0){
			return new Response("Cuenta actualmente bloqueada por superar numero de intentos",Response::LOGIN_BLOCK);
		}
		// Si es mayor a 0, devolver respuesta de error con el numero de intentos que quedan
		return new Response("Inicio de session incorrecto, checar usuario o contraseña", RESPONSE::ERROR_LOGIN,$tries_left);
	}

	/**
	* Intenta hacer login con los datos del usuario y de bd
	**/
	private static function intentarLogin($user,$pass,$db,$table,$link){
		$sql="SELECT id FROM $db.$table WHERE user='$user' AND (pass=recargas.crypto('$user','$pass'))";
		$result = mysqli_query($link, $sql);	
		if($row = mysqli_fetch_array($result)){
			return True;
		}
		return False;
	}

	/** 
	* Funcíon que buscará en la base de datos los datos necesarios para hacer las variables de session
	* y regresara un objecto con todas las variables de session. Debera de ser implementada por separado por cada login.
	* @return Objecto de SessionData.
	**/
	abstract protected function getSessionData();


	/** ----- FUNCIONES DE AYUDA ----- **/

	/**
	* Coloca el objecto de mysqli link en el login y al midleware de bloqueos
	* @param link: El objecto mysqli 
	**/
	function setLink(mysqli $link){
		$this->link = $link;
		if(isset($this->blockMiddleware)){
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

}
?>