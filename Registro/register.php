<?php

function execRegistro($nombre,$apaterno,$amaterno,$cel,$link){
  //constantes
  $referido="";
  $tipop="4";
  $analista="1";//cocas
 
  //convierto datos cad2
  $nnego="Promotor Independiente";
  $mediov="Promotor";

  if (!empty($nombre) && !empty($apaterno) && !empty($amaterno) && !empty($cel) ) {

    date_default_timezone_set('America/Mexico_City');
    $fecha=date ("Y-m-d H:i:s");

    //folio de $numdis
    $sql="SELECT no FROM creditos.folioc WHERE tipo='distri'";
    $result=mysqli_query($link, $sql);
    if ($row=mysqli_fetch_array($result)) {
      $numdis=$row["no"];
    }
    mysqli_free_result($result);
    $numdis++;
    $us="micd".$numdis;

    //grabo folio de $numdis
    $sql=mysqli_query($link, "UPDATE creditos.folioc SET no='$numdis' WHERE tipo='distri'");

    //variables
    $repre=$nombre." ".$apaterno." ".$amaterno;
    $lim=0;
    $tipopre=1;
    $recibe="automatico";
    $suc="Microtec Puebla Promotec P";
    $agente="PromoTec Web";
    $fechaact="2012-01-01 00:00:00";

    //divido si existen 2 nombres
    $llegonombre=trim($nombre);
    $cadena=explode(" ",$llegonombre);
    if (!empty($cadena[1])) {
      $cadena[0];
      $cadena[1];
    } else {
      $cadena[0];
    }
    //genero nombre de usuario y pass
    function gen_num($minimo=3, $maximo=3) {
      $num = "";
      $digitos = "0123456789";
      for ($i = 0; $i < rand($minimo, $maximo); $i++) {
        $num .= $digitos[rand(0, strlen($digitos) - 1)];
      }
      return $num;
    }
    $num=gen_num();
    $llegonombre=trim($nombre);
    $cadena=explode(" ",$llegonombre);
    $cadena[0]=strtolower($cadena[0]);
    $letra=substr($apaterno, 0,1);
    $letra=strtoupper($letra);
    $ugen=$cadena[0].$letra.$num;

    $pgen=generaPass();

    //genero codigo
    $n=substr($nombre,0,1);
    $ap=substr($apaterno,0,1);
    $am=substr($amaterno,0,1);
    //consecutivo
    $link_call=mysqli_connect($_SERVER['serverdata'],'samtec','sam33');
    $query = "call promotec.folio('conse')";
    $result = mysqli_query($link_call, $query);
    $row = mysqli_fetch_array($result);
    $conse = $row['valor'];
    mysqli_free_result($result);

    //aumentar 0
    if (strlen($conse)==1) {
      $conse="00".$conse;
    } elseif (strlen($conse)==2) {
      $conse="0".$conse;
    }
    //creo su codigo
    $codigo=$n.$ap.$am.$conse;
    $codigo=strtoupper($codigo);

    //saco clisat
    $link_call=mysqli_connect($_SERVER['serverdata'],'samtec','sam33');
    $query="call canal.folio('clisat')";
    $result=mysqli_query($link_call, $query);
    $row=mysqli_fetch_array($result);
    $clisat = $row['folio'];
    mysqli_free_result($result);
    
    $sql=html_entity_decode("INSERT INTO canal.lista (fecha,nombre,apaterno,amaterno,cel,nnego,mediov,user,dis,representa,lim,tipopre,recibe,sucursal,tipo,bloqp,agente,satcli) VALUES ('$fecha','$nombre','$apaterno','$amaterno','$cel','$nnego','$mediov','$us','$nnego','$repre','$lim','$tipopre','$recibe','$suc','6','2','$agente','$clisat')");
    
    //compruebo insercion en canal.lista
    if (mysqli_query($link, $sql)) {
      // inserto en samtec descuentos
      $sql="INSERT into samtec.descuentos (tae,movi,iusa,next,une,alo,cargo,cierto,mas,virgin,dis) values ('6','6','6','6','6','6','2.5','6','6','6','$us')";
      //compruebo insercion en canal.descuento
      if (mysqli_query($link, $sql)) {
        //insertar cliente
        $cli=html_entity_decode("insert into SAT.clientes (idcliente,nombre,telefono) values ('$clisat','$repre','$cel')");
        mysqli_query($link, $cli);
        //se registra el usuario
        $sql=html_entity_decode("INSERT INTO recargas.usuarios (fechain,dis,fechaact,observaciones,nombre,user,tipo,referido,codigo,analista) VALUES ('$fecha','$us','$fechaact','$agente','$repre','$ugen','$tipop','$referido','$codigo','$analista')");
        //compruebo insercion en recargas.usuarios
        if (mysqli_query($link, $sql)) {
          //actualiza numdis
        $sql=mysqli_query($link, "update creditos.folioc set no='".$numdis."' where tipo='distri'");
        //se verifica el usuario 
        $sql_id=mysqli_query($link, "select id from recargas.usuarios where user='$ugen'");
        if ($row=mysqli_fetch_array($sql_id)) { $idus=$row['id']; }
        //asigno pass
        $link_call=mysqli_connect($_SERVER['serverdata'],'samtec','sam33');
        $query="call recargas.cryptoc('$idus','$pgen')";
        $result=mysqli_query($link_call, $query);
        //mysqli_close($link);

          $aplicado="SI";
          //enviar sms con user y pass
          $txt="Nueva alta PromoTec su usuario: ".$ugen." password: ".$pgen." y el portal: http://www.promotormicrotec.mx";
          if(!mysqli_query($link, "insert into SMSServer.MessageOut (MessageTo,MessageText) values ('52$cel','$txt')")){
            return new Response("Solicitud capturada satisfactoriamente! Sin embargo ocurrió un problema al enviar sus datos a su telefono. Porfavor comuníquese a cualquiera de los siguientes números y un representate lo ayudará a completar el proceso: 2225051926 o 2221123782.  ",Response::SUCCESS);
          }
          return new Response("Solicitud capturada satisfactoriamente! Se le ha enviado un mensaje SMS a su celular con los datos de acceso a promotec. Un representate se pondrá en contacto con usted en un máximo de 48 horas. De no ser así, porfavor comuníquese a cualquiera de los siguientes números y un representate lo ayudará a completar el proceso: 2225051926 o 2221123782.",Response::SUCCESS);
        } else {
          return new Response("No se creo el usuario, pongase en contacto con el area de sistemas MicroTec",Response::ERROR);
        }
      } else {
        return new Response("Hubo un error al crear tabla de descuento, pongase en contacto con el area de sistemas MicroTec",Response::ERROR);
      }
    } else {
      return new Response("No se creo el usuario, por favor reintente!",Response::ERROR);
    }   
          
  } else {
    return new Response("Faltan campos requeridos por rellenar",Response::ERROR);
  }
}

/** Genera una contraseña nueva temporal**/
function generaPass(){
    //Se define una cadena de caractares. Te recomiendo que uses esta.
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    //Obtenemos la longitud de la cadena de caracteres
    $longitudCadena=strlen($cadena);

    //Se define la variable que va a contener la contraseña
    $pass = "";
    //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
    $longitudPass=8;

    //Creamos la contraseña
    for($i=1 ; $i<=$longitudPass ; $i++){
        //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
        $pos=rand(0,$longitudCadena-1);

        //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
        $pass .= substr($cadena,$pos,1);
    }
    return $pass;
}

//gen ps
function newps(){
  $may=chr(rand(65,90));
  $min1=chr(rand(97,122));
  $min2=chr(rand(97,122));
  $num=rand(1000,9999);  
  $ps=$may.$min1.$min2.$num;
  return $ps;
}
?>