<?
class PassReset {

	/** Temp pasword length **/
	const temp_pass_length = 8;

	/**
	* Generates a temporary password for the given user and saves it on the DB
	* @param: username given to generate a temp pass for
	* @return: The new temporary password if nothing failed.
	**/
	public static function getTempPass($username){
		// TODO: Generate pass from user and save pass in new db
		return self::genPass();
	}
	
	public static function sendNewTempPass($tempPass,$user){
		$phoneNum = "1223124123";
		$codePhoneNum = Nip::codePhoneNumber($phoneNum);
		
		return new Response("Contraseña temporal enviada como SMS al numero: ".$codePhoneNum.".",Response::SUCCESS,$codePhoneNum);
	}


	/** 
	* Generates a random string to work as a password
	* @return: String of random generated password
	**/
	static function genPass(){
		$length = self::temp_pass_length;
    	return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}

?>