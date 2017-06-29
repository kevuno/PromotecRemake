<?
class Nip{

	/** Numero de atencion para desbloqueo en caso de que no haya celular guardado en el telefono el usuario **/	
	const SupportNum = "2222211111";

	/** Nips length **/
	const NipLength = 6;

	/** Username for Nip **/
	public $username;

	/** NipNumber after creation**/
	public $nipNumber;

	/** Numero telefonico del usuario si es que tiene alguno al cual enviar un nip **/
	public $userPhoneNumber;

	/** Objecto de mysqli link para hacer llamadas a la BD **/
	private $link;

	/** Construye un nuevo nip ya generado**/
	function __construct($username,$nipNumber, $userPhoneNumber, $link){
		$this->username = $username;
		$this->nipNumber = $nipNumber;
		$this->userPhoneNumber = $userPhoneNumber;
		$this->link = $link;
	}


	static function genNewNipFromUser($username,$link){
		// Todo
		$nipNumber = self::genNip();

		//$sql="SELECT user,phoneNumber,nip FROM table.stuff WHERE user='$username'";
		//$result = mysqli_query($this->link, $sql);	
		//if($row = mysqli_fetch_array($result)){
			//Stuff
		//}
		$userPhoneNumber = "2221174640";
		$nip = new Nip($username,$nipNumber,$userPhoneNumber, $link);
		if(true){
			return new Response("Nip generado con exito",Response::SUCCESS,$nip);	
		}else if(false){
			return new Response("El usuario no tiene un numero telefonico");
		}else if(false){
			return new Response("Ya existe un Nip activo con el usuario");
		}else{
			return new Response("Usuario no existe");
		}
		
	}

	/**
	* Activates the nip in the db
	**/
	public function activateNip(){
		// TODO: Guardar Nuevo Nip en la bd
		return new Response("Se ha activado el Nip");

	}

	/**
	* Deactivates the nip in the db
	**/
	public function deactivate(){
		// TODO: Guardar Nuevo Nip en la bd
		return new Response("Se ha desactivado el Nip");

	}

	/** 
	* Generates a string of NipLength caracters
	* @return: String of random generated nip
	**/
	static function genNip(){
		$length = self::NipLength;
    	return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)) )),1,$length);
	}

	/** Envia el Nip al usuario **/
	public function sendToUser(){
		// TODO: Enviar SMS al usuario
		$codedPhoneNum = self::codePhoneNumber($this->userPhoneNumber);
		if(true){
			return new Response("Un NIP ha sido enviado al número: ".$codedPhoneNum.". Porfavor, introdúzcalo a continuación." ,Response::SUCCESS,$codedPhoneNum);	
		}
		
	}

	/** 
	* Returns a string with only the last four digits of the given phone number string, and 
	* the rest of the numbers hidden by a " * "" character
	* @param: The phoneNumber
	* @return: The coded phone number
	**/
	public static function codePhoneNumber(String $phoneNumber){
		// Make sure the length of the string is at least 4 digits long
		if(isset($phoneNumber[3])){
			$last_four_index = strlen($phoneNumber)-4;
			for ($i = 0; $i < $last_four_index; $i++){
				$phoneNumber[$i] = "*";
			}
			return $phoneNumber;	
		}
		throw new Exception("Error, numero telefonico es menor de 4 digitos");
		
	}
	/**
	* Validates a given Nip and returns the associated user
	* @param: Nip number
	* @return: Nip's username string
	**/

	function validateNip($nipNumber){
		//Todo: Call db and check
		$username = "kev";
		$userPhoneNumber = "222123123";
		return new Response("Nip valido",Response::SUCCESS,new Nip($username,$nipNumber,$userPhoneNumber));
	}


}