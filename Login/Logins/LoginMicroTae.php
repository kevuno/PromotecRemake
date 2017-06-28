<?

class LoginMicroTae extends Login{
	
	/** Constrcts the object by calling the parent constructor with the name of the db and table **/
	public function __construct(){
		parent::__construct("recargas","usuarios");
	}	
	
	/** 
	* Obtains the session data from DB
	* @return: SessionData object
	**/
	public function getSessionData(){
		// TODO
		return null;
	}
}

?>
