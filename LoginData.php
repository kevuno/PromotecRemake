<?
class LoginData{
	/** os datos principales de session: El usuario y la contraseña **/
	public $user;
	public $pass;

	/** El tipo de cliente de donde el login proviene **/
	private $tipo;

	function __construct($user,$pass,$tipo){
		$this->user = $user;
		$this->pass = $pass;
		$this->tipo = $tipo;
	}

}

class CaptchaData{
	/** La info del captcha que proviene del formulario que deben de ser verificados **/
	private $captcha;
	
	/** La key secreta para verificar captcha **/
	private $captcha_key;

	/** La iṕ para verificar captcha **/
	private $ip;	


	function __construct($captcha,$captcha_key,$ip){
		$this->captcha = $captcha;
		$this->captcha_key = $captcha_key;
		$this->ip = $ip;
	}

	function validate(){
		return CaptchaChecker::validate($this->captcha_key, $this->captcha, $this->ip);
	}
}

?>