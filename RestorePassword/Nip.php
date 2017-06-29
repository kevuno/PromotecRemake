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


	/** Construye un nuevo nip ya generado**/
	function __construct($username,$nipNumber, $userPhoneNumber){
		$this->username = $username;
		$this->nipNumber = $nipNumber;
		$this->userPhoneNumber = $userPhoneNumber;
	}


	static function genNewNipFromUser($username){
		// Todo
		$nipNumber = self::genNip();
		$userPhoneNumber = "2221174640";
		$nip = new Nip($username,$nipNumber,$userPhoneNumber);
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

	public function activateNip(){
		// TODO: Guardar Nuevo Nip en la bd
		return new Response("Se ha activado el Nip");

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
		if(true){
			return new Response("NIP ha sido enviado al numero",Response::SUCCESS,$this->userPhoneNumber);	
		}
		
	}

	/**
	* Validates a given Nip and returns the associated user
	* @param: Nip number
	* @return: Nip's username string
	**/

	function validateNip($nipNumber){
		//Todo: Call db and check
		$username = "kev";
		return new Response("Nip valido",new Nip($username,$nipNumber));
	}


}