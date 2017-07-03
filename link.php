<?php

/** Returns DB link or Error if failed **/

class link{
	static function getLink(){
		try{
			$ip = $_SERVER["serverdata"];
			$link = new mysqli($ip,"samtec","sam33");	
			//$link = new mysqli("localhost","root","Bacardi12312300");
			if ($link->connect_error) {
			    die('Connect Error (' . $link->connect_errno . ') '
			            . $link->connect_error);
			}
		}catch(Exception $e){
			
			throw new Exception("Error intentando contectarse a la Base de datos");
		}
		$link->set_charset("utf8");
		return $link;
	}

}

?>