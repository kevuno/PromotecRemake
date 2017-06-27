<?
class BlockUserMiddleware{

	/** Maximo numero de intentos permitidos**/
	const $max_login_tries = 5;

	/** Numero actual de intentos**/
	public $login_tries;	

	/** La base de datos del login**/
	protected $db;

	/** La tabla del login**/
	protected $table;
	
	/** Database connection link **/
	protected $link;	

	/** 
	* Construir el objecto con la info de la bd y creando la variable de 
	* session si no existe
	**/
	public function __construct(){
		$this->db = "microtec";
		$this->table = "bloqueo_login";
		// Iniciar variable de intentos si no existe
		if (!isset($_SESSION['tries'])){
			$_SESSION["tries"] = 0;
		}
		$this->login_tries = $_SESSION["tries"];
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
	* @return: Mensaje de respuesta dependiendo la situacion
	**/
	public function checkBlockAccount(){
		$user_ip = $_SERVER['REMOTE_ADDR'];

		$sql="SELECT ip, intentos, ultimo_intento from $this->db.$this->table WHERE ip='$user_ip'";
		$result = mysqli_query($this->link, $sql);

		// 1. checar si hay algun registro de la ip del usuario
		if ($row = mysqli_fetch_array($result)) {
			// 2. checar numero actual de intentos

			$this->login_tries = $row["intentos"] ? $row["intentos"] : 0;
			if($this->login_tries >= 5){
				throw new Exception("Cuenta actualmente bloqueada por superar numero de intentos");
			}
		}
		return new Response("No hay bloqueo actual")
		//TODO
		// 2. Bloquear cuenta despues de 5 intentos de hacer login
		if($this->login_tries >= 5){
			$message = $this->blockAccount();
			throw new Exception("$message");
		}
		$us=$this->loginData->user;
	}
	
}

?>