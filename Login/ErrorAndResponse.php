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

/** Clase que representa una respuesta en el proceso de login */
class Response{
	/** El mensaje de la respuesta **/
	public $message;

	/** Objecto de informacion de la respuesta - opcional**/
	public $data;

	function __construct($message,$data){
		$this->message = $message;
		$this->data = $data;
	}

	function addData($data){

	}

}