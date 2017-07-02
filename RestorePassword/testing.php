<?php

function gen_nip($minimo=4, $maximo=4) {
  $num = "";
  $digitos = "0123456789";
  for ($i = 0; $i < rand($minimo, $maximo); $i++) {
    $num .= $digitos[rand(0, strlen($digitos) - 1)];
  }
  return $num;
}
if($tipo=='generaNip2'){
  $us=security($k[1]);
  //fecha
  date_default_timezone_set('America/Mexico_City');
  $fecha=date ("Y-m-d H:i:s");
  $fecha2=date ("Y-m-d");

  $sql=mysqli_query($link, "SELECT u.id, l.cel from multi.usuarios u left join canal.lista l on u.dis=l.user where u.user='$us'");
 
  if ($row=mysqli_fetch_array($sql)) {
    $cel=$row['cel'];
    if(!empty($cel)){
        $checkNip="SELECT nip from multi.nips where user='$us' and status='1' and fecha like '$fecha2%'";
        $result=mysqli_query($link, $checkNip);
        if (mysqli_num_rows($result)>0) {
          $row2=mysqli_fetch_array($result);
          $nip=$row2['nip'];
        }else{
          $nip=gen_nip();
          //asigno pass
          $creaNip="INSERT into multi.nips (user, numero, nip, fecha, status, app)values('$us','$cel','$nip','$fecha','1','WiMO-Web')";
          mysqli_query($link, $creaNip);
        }
        if(!empty($nip)){
            $txt = "El NIP para recuperar la clave del usuario $us es $nip";

            $messg = "insert into SMSServer.MessageOut (MessageTo,MessageText) values ('52$cel','$txt')";
            //echo $nip;
            mysqli_query($link,$messg);
            echo "S|Se creo NIP |cambio_contrasena|alert-success|regresar";
        }else{
          echo "E2|Ocurrio un error al generar NIP!|cambio_contrasena|alert-danger|Reintente";
        }
    }else {
      echo "E2|Su usuario no cuenta con algún número celular, por favor póngase en contacto al: 2227927811 con el área de soporte para poder actualizar su número celular.!|cambio_contrasena|alert-warning|Reintente"; 
    }
  } else {
    echo "E2|El Usuario no existe!|cambio_contrasena|alert-danger|Reintente";
  }
}

if ($tipo=="recu_contra"){
    $us=security($k[1]);
    $nip=security($k[2]);
    $password=newps();
    date_default_timezone_set('America/Mexico_City');
    $fecha=date ("Y-m-d H:i:s");
    $fecha2=date ("Y-m-d");

    if (!empty($us) && !empty($nip)) {
      //recupero celular
      $sql="SELECT l.cel, u.id from multi.usuarios u left join canal.lista l on u.dis=l.user where u.user='$us'";
      $result=mysqli_query($link, $sql);
      $row=mysqli_fetch_array($result);
      $id=$row['id'];
      $cel=$row['cel'];

      if (empty($cel)) {
        echo "E|Error, no tiene registrado un numero para la recuperacion de password|login|danger|Reintente";
        exit();
      }
      $checkNip="SELECT nip from multi.nips where user='$us' and status='1' and nip='$nip' and fecha like '$fecha2%'";
      $result=mysqli_query($link, $checkNip);
      if (mysqli_num_rows($result)>0) {
        //actualizo password
        $link_call=mysqli_connect($_SERVER["serverdata"],"samtec","sam33");
        $query="call multi.cryptoc('$id','$password')";
        //echo $id."|".$password."|".$query."|".$nip;
        $result=mysqli_query($link_call, $query);
        //mysqli_free_result($result);
        mysqli_close($link_call);
        $txt="Su clave WiMO temporal es: $password Si Ud. No solicito cambio de clave, por favor comuníquese al 2222084123.";
        //envio mensaje
        $sms="INSERT into SMSServer.MessageOut (MessageTo,MessageText) values ('52$cel','$txt')";

        //creo nuevo registro
        $creaNip="INSERT into multi.nips (user, numero, nip, fecha, status, app)values('$us','$cel','$nip','$fecha','2','WiMO-Web')";
        mysqli_query($link, $creaNip);
        $upNip="UPDATE multi.nips set status='3' where user='$us' and status='1' and nip='$nip' and fecha like '$fecha2%'";
        mysqli_query($link, $upNip);
        // Si se logro enviar el msm enviar mensaje
        if (mysqli_query($link, $sms)) {
          echo "S|Su Contrase&ntilde;a fue enviada, En caso de que el usuario sea correcto usted <br>recibirá un mensaje de txt a su celular con su clave provisional.!|login|success|regresar";
        } else {
          echo "E|Ocurrio un error al cambiar su Contrase&ntilde;a!|login|warning|Reintente";
        }

      }else{
       echo "E|NIP incorrecto, por favor Reintente!|cambio_contrasena|alert-danger|Reintente"; 
      }
    }else {
        echo "E|Faltan datos para enviar su solicitud!|login|danger|Reintente";
    }
    return self::genPass();
}