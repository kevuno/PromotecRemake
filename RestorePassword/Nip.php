<?
class Nip{

	/** Numero de atencion para desbloqueo en caso de que no haya celular guardado en el telefono el usuario **/	
	const SupportNum = "2222211111";

	/** Nips length **/
	const NipLength = 4;

	/** Username for Nip **/
	public $username;

	/** NipNumber after creation**/
	public $nipNumber;

	/** Numero telefonico del usuario si es que tiene alguno al cual enviar un nip **/
	public $userPhoneNumber;

	/** ID de restauracion **/
	public $idRest;

	/** Boolean checando si ya se envio el nip al usuario**/
	public $sent = false;

	/** Objecto de mysqli link para hacer llamadas a la BD **/
	private $link;

	/** Construye un nuevo nip ya generado**/
	function __construct($username,$nipNumber, $userPhoneNumber, $idRest, $link){
		$this->username = $username;
		$this->nipNumber = $nipNumber;
		$this->userPhoneNumber = $userPhoneNumber;
		$this->idRest = $idRest;
		$this->link = $link;
	}


	static function genNipFromUser($username,$link){
		// fecha
		date_default_timezone_set('America/Mexico_City');
		$today = date ("Y-m-d");
		// sql
		$sql=mysqli_query($link, "SELECT u.id, l.cel from multi.usuarios AS u LEFT JOIN canal.lista AS l on u.dis=l.user where u.user='$username'");
		if ($row=mysqli_fetch_array($sql)) {
			// Guardar datos del celular y de la tabla donde se restaura el celular
			$userPhoneNumber = $row['cel'];
			$idRest = $row['id'];
			// Checar que se tenga un registro del celular para poder enviar el NIP
			if(!empty($userPhoneNumber)){
				// Checar si hay algun NIP activo
			    $checkNip="SELECT nip from multi.nips where user='$username' and status='1' and fecha like '$today%'";
			    $result=mysqli_query($link, $checkNip);
			    // Checar si hay algun nip activo con la fecha de hoy
			    // Si si hay entonces prodrá ser usado para el proceso de Pass Reset
			    if ($row=mysqli_fetch_array($sql)) {
			    	$nip = new Nip($username,$row['nip'],$userPhoneNumber, $idRest, $link);
			    	$nip->sent = true;
					return new Response("Ya se ha enviado un NIP al celular de éste usuario, porfavor ingréselo a continuacion o espere un máximo de 24 horas para poder volver a enviar un nuevo NIP",Response::SUCCESS,$nip);
			    }
				// No hay ningun nip ya registrado y activo asi que se genera uno nuevo
				$nipNumber = self::genNip();
				$newNip = new Nip($username,$nipNumber,$userPhoneNumber, $idRest, $link);
				return new Response("Nip generado con exito",Response::SUCCESS,$newNip);
			} else {
				return new Response("Su usuario no cuenta con algún número celular, por favor póngase en contacto al: 2227927811 con el área de soporte para poder actualizar su número celular.",Response::ERROR);
			}
		} else {
			return new Response("El usuario no existe!",Response::ERROR);
		}
	}

	/**
	* Activates the nip in the db
	**/
	public function activateNip(){
		$now = date ("Y-m-d H:i:s");
		$creaNip="INSERT into multi.nips (user, numero, nip, fecha, status, app)values('$this->username','$this->userPhoneNumber','$this->nipNumber','$now','1','WiMO-Web')";
		mysqli_query($this->link, $creaNip);
		return new Response("Se ha activado el Nip");
	}

	/**
	* Deactivates the nip in the db
	**/
	public function deactivate(){
		// TODO: Descativar un NIP activo de la base de datos
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
 		$txt = "El NIP para recuperar la clave del usuario $us es $nip";
		$query = "insert into SMSServer.MessageOut (MessageTo,MessageText) values ('52$$this->userPhoneNumber','$txt')";
		if(mysqli_query($this->link,$query)){
			$codedPhoneNum = self::codePhoneNumber($this->userPhoneNumber);
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



}