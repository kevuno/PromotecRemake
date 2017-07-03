<? 
// Logins // TODO improve namespace
require('Logins/LoginPromotor.php');
require('Logins/LoginSamtec.php');
require('Logins/LoginMicroTae.php');
require('Logins/LoginMicroPay.php');
// Other classes
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

	/** Database connection link **/
	protected $link;

	/** Block Middleware **/
	public $blockMiddleware;


	/** Object constructor with optional parameters**/
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

			// No esta bloqueado el usuario asi que continua
			$user = $this->loginData->user;
			$pass = $this->loginData->pass;
			//$sql="SELECT id FROM $this->db.$this->table WHERE user='$user' AND pass='$pass'";
			$sql="SELECT id FROM $this->db.$this->table WHERE user='$user' AND (pass=recargas.crypto('$user','$pass'))";
			$result = mysqli_query($this->link, $sql);	
			if($row = mysqli_fetch_array($result)){
				// Login logrado, actualizar estado de bloqueo, se ignoran los intentos restantes que devuelve updateBlockIP()
				$this->blockMiddleware->updateBlockIP(true);
				return new Response("Login basico fue exitoso",Response::SUCCESS);
			}
		} catch(Exception $e){
			throw $e;
		}

		// Login no fue logrado entonces se actualiza el objeto de bloqueo
		$tries_left = $this->blockMiddleware->updateBlockIP(false);

		// Si el numero de intentos es 0 enviar un mensaje de bloqueo
		if ($tries_left == 0){
			return new Response("Cuenta actualmente bloqueada por superar numero de intentos",Response::LOGIN_BLOCK);
		}
		// Si es mayor a 0, devolver respuesta de error con el numero de intentos que quedan
		return new Response("Inicio de session incorrecto, checar usuario y contraseña", RESPONSE::ERROR_LOGIN,$tries_left);
	}

	/** 
	* Funcíon que buscará en la base de datos los datos necesarios para hacer las variables de session
	* y regresara un objecto con todas las variables de session. Debera de ser implementada por separado por cada login.
	* @return Objecto de Respuesta de Session.
	**/

	abstract protected function getSessionData();

	/**
	* Sets the mysqli link object to the login and to the middleware
	* @param: The mysqli link object
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