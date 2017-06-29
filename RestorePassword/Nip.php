<?
class Nip{

	/** Numero de atencion para desbloqueo en caso de que no haya celular guardado en el telefono el usuario **/	
	const SupportNum = "2222211111";

	/** Nips length **/
	const NipLength = 6;


	/** Construye un nuevo nip ya generado**/
	function __construct($username,$nipNumber){
		$this->username = $username;
		$this->nipNumber = $nipNumber;
	}


	/**
	* Validates a given Nip and returns the associated user
	* @param: Nip number
	* @return: Nip's username string
	**/

	function validateNip($nipNumber){
		//Todo: Call db and check
		$username = "kev";
		return new Response("Nip generado con exito",new Nip($username,$nipNumber));
	}


	static function genNewNipFromUser($username){
		// Todo
		$nipNumber = "123456";
		if(true){
			return new Response("Nip generado con exito",new Nip($username,$nipNumber));	
		}else{
			return new Response("Usuario no existe");
		}
		
	}

	/** 
	* Generates a string of NipLength caracters
	* @return: String of random generated nip
	**/
	static function genNip(){
		$length = self::NipLength;
    	return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)) )),1,$length);
	}

	function sendToUser(){

	}

}