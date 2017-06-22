<?
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