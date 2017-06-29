<?
/** Clase que representa una respuesta en el proceso de login */
class Response{
	/** El mensaje de la respuesta **/
	public $message;


	/** Tipo de respuesta Neutro **/
	const NEUTRAL = 0;

	/** Tipo de respuesta de success**/
	const SUCCESS = 1;

	/** Tipo de respuesta de Bloqueo de login **/
	const LOGIN_BLOCK = 2;
	
	/** Tipo de respuesta de error al hacer login **/
	const ERROR_LOGIN = 3;	

	/** Tipo de respuesta**/
	public $type;

	/** Objecto de informacion de la respuesta - opcional**/
	public $data;

	/** Construye el objecto con un mensaje, una constante de tipo Response:: , y informacion opcional **/
	function __construct($message,$type,$data = null){
		$this->message = $message;
		$this->type = $type;
		$this->data = $data;
	}

	/** Hooks data to the Response object **/
	function addData($data){
		$this->data = $data;
	}

}