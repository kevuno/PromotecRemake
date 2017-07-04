<?
require_once('../Response.php');
class BlockUserMiddleware {

	/** Maximo numero de intentos permitidos**/
	const max_login_tries = 5;

	/** Tiempo de espera en segundos para poder volvera intentar login despues de un bloqueo**/
	const wait_time = 600;	

	/** Numero actual de intentos**/
	public $login_tries;

	/** Boolean que guarda si existe alguna entrada en la BD sobre la IP 
	(se usara para cuando se quiera actualziar la tabla)**/
	public $user_exists_in_records = False;		

	/** La base de datos del login**/
	private $db = "microtec";

	/** La tabla del login**/
	private $table = "bloqueo_login";
	
	/** Database connection link **/
	private $link;

	/** User IP **/
	private $user_ip;	

	/** 
	* Construir el objecto con la info de la bd y creando la variable de 
	* session si no existe
	**/
	public function __construct(){
		$this->user_ip = $_SERVER['REMOTE_ADDR'];

	}

	/**
	* Sets the mysqli link object 
	* @param: The mysqli link object
	**/
	function setLink(mysqli $link){
		$this->link = $link;
	}


	/** 
	* Bloquea la cuenta del usuario si es que existe y envia un NIP si no hay ninguno activo
	* @return: Response obj dependiendo la situacion
	**/
	public function checkBlockIP(){
		$sql="SELECT ip, intentos, ultimo_intento from $this->db.$this->table WHERE ip='$this->user_ip'";
		if(!mysqli_query($this->link, $sql)){
			throw new Exception("Error al ejecutar SQL: ".$sql);
		}
		$result = mysqli_query($this->link, $sql);
		// 1. checar si hay algun registro de la ip del usuario
		if ($row = mysqli_fetch_array($result)) {
			$this->user_exists_in_records = True;
			$this->login_tries = $row["intentos"];
			
			// 2. Checar que fecha-hora de ultimo intento, si menor a 600 seguira bloquearlo
			date_default_timezone_set('America/Mexico_City');

			if((time() - strtotime($row["ultimo_intento"]) < self::wait_time)){
				// 3. checar numero actual de intentos
				if($this->login_tries >= self::max_login_tries){
					return new Response("Cuenta actualmente bloqueada por superar numero de intentos", Response::LOGIN_BLOCK);
				}
			}else{
				// El tiempo ha sido mayor de 10 minutos entonces se resetea el numero de intentos
				$this->login_tries = 0;
				return new Response("Se reseteo el numero de intentos",Response::NEUTRAL);
			}
			
		}
		return new Response("No hay bloqueo actual",Response::NEUTRAL);
	}
	/**
	* Actualiza la base de datos de bloqueos de login dependiendo de si se logro hacer login o no. Si si, se resetean los intentos a 0. Si no, se incrementa a uno más
	* @param: $userLoggedIN: Boolean, si el usuario logro hacer login o no.
	* @return: Numero de intentos restantes
	*/
	public function updateBlockIP($userLoggedIn){
		// Si el usuario logró hacer login, se resetean los intentos, sino se agrega uno más.
		if($userLoggedIn){
			$this->login_tries = 0;
		}else{
			++$this->login_tries;
		}
		// Si el usuario ya existe en la bd se hace un update, sino se inserta
		if($this->user_exists_in_records){
			$sql="UPDATE $this->db.$this->table SET intentos='$this->login_tries' WHERE ip='$this->user_ip'";
			if(!mysqli_query($this->link, $sql)){
				throw new Exception("Error al ejecutar SQL: ".$sql);
			}
		}else{
			$sql="INSERT INTO $this->db.$this->table (ip,intentos) VALUES ('$this->user_ip','$this->login_tries')";
			if(!mysqli_query($this->link, $sql)){
				throw new Exception("Error al ejecutar SQL ".$sql);
			}
		}
		return self::max_login_tries-$this->login_tries;
	}
	
}

?>