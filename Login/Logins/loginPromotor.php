<?
class LoginPromotor extends Login{
	public function getSessionData(){
		// llamara a la base de datos y obtener los datos de la session

		$session = SessionData::Instance();
		//Ejemplo de agregar valores al objeto de session
		$session->add("nombre",$row["nombre"]);

		return $session;
	}
}
?>