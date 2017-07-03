<?
class PassReset {

	/** Temp pasword length **/
	const temp_pass_length = 8;
	
	/** The NIP object obtained from the username**/
	public $nip;

	/** Pass temporal **/
	private $tempPass;

	/** Objecto de mysqli link para hacer llamadas a la BD **/
	private $link;

	/** Construye un nuevo objecto de PassResset**/
	function __construct($nip){
		$this->nip = $nip;
		$this->link = $nip->link;
	}
	/**
	* Generates a temporary password for the given user and saves it on the DB
	* @param: username given to generate a temp pass for
	* @return: The new temporary password if nothing failed.
	**/
	public function getTempPass(){
		$this->tempPass = genPass(self::temp_pass_length);
		if(!$this->tempPass){
			throw new Exception("Error al momento de generar nuevo pass");
		}
	}
	/** 
	* Updates the new generated password into the database and Updastes NIP data
	**/
	public function save(){
		// Datos
		$username = $this->nip->username;
		$nipNumber = $this->nip->nipNumber;
		$cel = $this->nip->userPhoneNumber;
		// Fecha
		date_default_timezone_set('America/Mexico_City');
		$fecha=date ("Y-m-d H:i:s");
		$today=date ("Y-m-d");
		// Actualizar contraseña
		$idRest = $this->nip->idRest;
		$query="call multi.cryptoc('$idRest','$this->tempPass')";
		$result=mysqli_query($this->link, $query);
		// Crear nuevo registro de NIP de que ya quedo usado
		$creaNip="INSERT into multi.nips (user, numero, nip, fecha, status, app)values('$username','$cel','$nipNumber','$fecha','2','WiMO-Web')";
		mysqli_query($link, $creaNip);
		// Actualiza registro de NIP
		$upNip="UPDATE multi.nips set status='3' where user='$username' and status='1' and nip='$nipNumber' and fecha like '$today%'";
		mysqli_query($link, $upNip);
	}

	/**
	* Sends the password to the user's phone, accesed through the NIP's object data
	**/
	public function sendPass(){
		// Datos
		$cel = $this->nip->userPhoneNumber;
		$txt="Su clave WiMO temporal es: $this->tempPass Si Ud. No solicito cambio de clave, por favor comuníquese al 2222084123.";
		$sms="INSERT into SMSServer.MessageOut (MessageTo,MessageText) values ('52$cel','$txt')";

		// Si se logro enviar el msm enviar mensaje
		if (mysqli_query($link, $sms)) {
			// Codificar numero telefonico
			$codePhoneNum = Nip::codePhoneNumber($cel);
			return new Response("Contraseña temporal enviada como SMS al numero: ".$codePhoneNum.".",Response::SUCCESS,$codePhoneNum);		
		} else {
			throw new Exception("Error al enviar mensaje de sms");
		}
		
		
	}

	/**
	* Validates a given Nip and returns a PassReset object
	* @param: Nip number, username, link for connection
	* @param: login object with information of where to valdate the data
	* @return: A Pass Reset object with all the information needed
	**/
	public static function validateNip($nipNumber,$username,$link,$login){
		// fecha
		date_default_timezone_set('America/Mexico_City');
		$today = date ("Y-m-d");
		// Checar si hay algun nip activo con la fecha de hoy
	    $checkNip="SELECT user from multi.nips where user='$username' and status='1' and nip='$nipNumber' and status='1' and fecha like '$today%'";
	    $result=mysqli_query($link, $checkNip);
	    if (mysqli_num_rows($result)>0) {
	    	// Get an nip object with all the data
	    	$response = genNipFromUser($username,$link,$login);
	    	$nip = $response->data;
	    	if($nip){
	    		return new PassReset($nip);
	    	}
	    	return $response;
	    }
		return new Response("Nip no valido!",Response::ERROR);
	}
	/** 
	* Generates a random string to work as a password
	* @param: Length of password to be created
	* @return: String of random generated password
	**/
	static function genPass($length){
    	return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}

?>