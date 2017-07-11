<?
/** Clase que representa una respuesta en el proceso de login */
class Response{
	/** Tipo de respuesta**/
	public $status;
	
	/** El mensaje de la respuesta **/
	public $message;

	/** Tipo de respuesta ERROR (usado cuando hay alguna exception al ejecutar sql, etc)**/
	const ERROR = -1;

	/** Tipo de respuesta Neutro **/
	const NEUTRAL = 0;

	/** Tipo de respuesta de success**/
	const SUCCESS = 1;

	/** Tipo de respuesta de Bloqueo de login **/
	const LOGIN_BLOCK = 2;
	
	/** Tipo de respuesta de error al hacer login **/
	const ERROR_LOGIN = 3;	

	/** Tipo de respuesta de error al hacer login **/
	const ERROR_CAPTCHA = 4;	

	/** Objecto de informacion de la respuesta - opcional**/
	public $data;

	/** Construye el objecto con un mensaje, una constante de tipo Response:: , y informacion opcional **/
	function __construct($message,$status = self::NEUTRAL,$data = null){
		$this->message = $message;
		$this->status = $status;
		$this->data = $data;
	}

	/**
	* Creates a new Response of status error from a Response object
	* @param: $exception: An exception object
	* @return: A new response
	**/
	static public function errorResponseFromException(Exception $exception){
		return new Response($exception->getMessage(),Response::ERROR,"Exception faulted");
	}

	/** Hooks data to the Response object **/
	function addData($data){
		$this->data = $data;
	}

	/** Gets the response status"**/
	public function getStatus(){
		return $this->status;
	}

	/** To string function **/
	public function __toString(){
        return "R|".$this->message."|".$this->status."|".$this->data;
    }

    /** To json encode **/

    public function toJson(){
    	return json_encode($this);
    }


}