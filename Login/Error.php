<?
/** Clase que representa un error en el proceso de login */
class Error{
	public $message;

	private $cause;

	function __construct($message,$cause){
		$this->message = $message;
		$this->cause = $cause;
	}

}