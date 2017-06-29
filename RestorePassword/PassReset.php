<?
class PassReset {

	/** Temp pasword length **/
	const temp_pass_length = 8;

	/** Generates a temporary password for the given user**/
	public static function getTempPass($user){
		// TODO: Generate pass from user and save pass in new db
		return genPass();
	}
	
	public static function sendNewTempPass($tempPass,$user){
		$phoneNum = "1223124123";
		return new Response("Contraseña temporal enviada como SMS al numero",$phoneNum);
	}


	/** 
	* Generates a random string to work as a password
	* @return: String of random generated password
	**/
	static function genPass(){
		$length = self::temp_pass_length;
    	return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)) )),1,$length);
	}
}

?>