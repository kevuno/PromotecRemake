<?php  
/*
**MicroTec
*/
session_start();
include '../link.php';
include '../seguro.php';

  //recibo datos

  //convierto datos cad1
  $referido="";
  $nombre=security($_POST['nom']);
  $nombre=ucwords(strtolower(trim($nombre)));
  $apaterno=security($_POST['apa']);
  $apaterno=ucwords(strtolower(trim($apaterno)));
  $amaterno=security($_POST['ama']);
  $amaterno=ucwords(strtolower(trim($amaterno)));
  $cel=security($_POST['cel']);
  $tel=security($_POST['tel']);
  $email=security($_POST['correo']);
  $email=strtolower($email);
  $referido=security($_POST['referido']);
  $referido=strtoupper(trim($referido));
  $tipop="4";
  $analista="1";//cocas
 
  //convierto datos cad2
  $nnego="Promotor Independiente";
  $mediov="Promotor";
  $edi=security($_POST['edi']);
  $edi=ucwords(strtolower(trim($edi)));
  $muni=security($_POST['munic']);
  $muni=ucwords(strtolower(trim($muni)));
  $cd=security($_POST['ciudad']);
  $cd=ucwords(strtolower(trim($cd)));
  $colonia=security($_POST['colonia']);
  $colonia=ucwords(strtolower(trim($colonia)));
  $cp=security($_POST['cp']);
  $calle=security($_POST['calle']);
  $calle=ucwords(strtolower(trim($calle)));
  $next=security($_POST['next']);

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

    $pgen=newps();

    //genero codigo
    $n=substr($nombre,0,1);
    $ap=substr($apaterno,0,1);
    $am=substr($amaterno,0,1);
    //consecutivo
    require("link_call.php");
    $query = "call promotec.folio('conse')";
    $result = mysqli_query($link_call, $query);
    $row = mysqli_fetch_array($result, MYSQLI_BOTH);
    $conse = $row['valor'];
    mysqli_free_result($result);
    mysqli_close($link_call);
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
    $link_call=mysqli_connect($_SERVER["serverdata"],"samtec","sam33");
    $query="call canal.folio('clisat')";
    $result=mysqli_query($link_call, $query);
    $row=mysqli_fetch_array($result, MYSQLI_BOTH);
    $clisat = $row['folio'];
    mysqli_free_result($result);
    mysqli_close($link_call);

    //inserto en canal.lista
    $sql=html_entity_decode("INSERT INTO canal.lista (fecha,nombre,apaterno,amaterno,cel,tel,email,nnego,mediov,calle,next,colonia,muni,cd,edi,cp,user,dis,representa,lim,tipopre,recibe,sucursal,tipo,bloqp,agente,satcli) VALUES ('$fecha','$nombre','$apaterno','$amaterno','$cel','$tel','$email','$nnego','$mediov','$calle','$next','$colonia','$muni','$cd','$edi','$cp','$us','$nnego','$repre','$lim','$tipopre','$recibe','$suc','6','2','$agente','$clisat')");
    
    //compruebo insercion en canal.lista
    if (mysqli_query($link, $sql)) {
      // $sql="INSERT INTO canal.descuento (dis,f000100,f000200,f000300,f000500,f000030,f000050,diasficha,diaskits) VALUES ('$us','$f100','$f200','$f300','$f500','$f30','$f50','$dficha','$dkit')";
      $sql="INSERT into samtec.descuentos (tae,movi,iusa,next,une,alo,cargo,cierto,mas,virgin,dis) values ('6','6','6','6','6','6','2.5','6','6','6','$us')";
      //compruebo insercion en canal.descuento
      if (mysqli_query($link, $sql)) {
        //insertar cliente
        $cli=html_entity_decode("insert into SAT.clientes (idcliente,nombre,calle,numext,colonia,localidad,municipio,estado,pais,cp,email,telefono) values ('$clisat','$repre','$calle','$next','$colonia','$cd','$muni','$edi','Mexico','$cp','$email','$cel')");
        mysqli_query($link, $cli);
        
        $sql=html_entity_decode("INSERT INTO recargas.usuarios (fechain,dis,fechaact,observaciones,nombre,user,tipo,referido,codigo,analista) VALUES ('$fecha','$us','$fechaact','$agente','$repre','$ugen','$tipop','$referido','$codigo','$analista')");
        //compruebo insercion en recargas.usuarios
        if (mysqli_query($link, $sql)) {
          //actualiza numdis
        $sql=mysqli_query($link, "update creditos.folioc set no='".$numdis."' where tipo='distri'");
        $sql_id=mysqli_query($link, "select id from recargas.usuarios where user='$ugen'");
        if ($row=mysqli_fetch_array($sql_id)) { $idus=$row['id']; }
        //asigno pass
        $link_call=mysqli_connect($_SERVER["serverdata"],"samtec","sam33");
        $query="call recargas.cryptoc('$idus','$pgen')";
        $result=mysqli_query($link_call, $query);
        mysqli_free_result($result);
        mysqli_close($link_call);

          $aplicado="SI";
          //enviar sms con user y pass
    $txt="Nueva alta PromoTec su usuario: ".$ugen." password: ".$pgen." y el portal: http://www.promotormicrotec.mx";
    mysqli_query($link, "insert into SMSServer.MessageOut (MessageTo,MessageText) values ('52$cel','$txt')");
    echo "S|<div class='alert alert-success text-center'><span>Solicitud capturada correctamente</a></span></div>";
        } else {
          echo "E|<div class='alert alert-danger text-center'><span>No se creo el usuario, pongase en contacto con el area de sistemas MicroTec</a></span></div>";
          exit();
        }
      } else {
        echo "E|<div class='alert alert-danger text-center'><span>Hubo un error al crear tabla de descuento, pongase en contacto con el area de sistemas MicroTec</a></span></div>";
        exit();
      }
    } else {
      echo "E|<div class='alert alert-danger text-center'><span>No se creo el usuario, por favor reintente!</a></span></div>";
      exit();
    }

    if (isset($aplicado) && !empty($email)) {
      //mando email con codigo
      $para=$email;
      include '../../manager/mail_promotor.php';
      $tit="Bienvenido a PromoTec";

      // datos que no se mueven
      require_once ('../../swift/lib/swift_required.php');
      $transport = Swift_SmtpTransport::newInstance('mail1.micro-tec.com.mx', 112)
        ->setUsername('avisos@micro-tec.com.mx')
  ->setPassword('avisos23')
      ;
      $mailer = Swift_Mailer::newInstance($transport);
      $message = Swift_Message::newInstance($tit)
        ->setFrom(array("avisos@micro-tec.com.mx" => "Servidor Microtec"))
        ->setTo(array($para => "Nueva alta PromoTec"))
        ->setBody($mensaje,'text/html')
      ;
      $result = $mailer->send($message);
      // fin de datos que no se mueven

      
      exit();
    }
    
          
  } else {
    echo "E|<div class='alert alert-danger text-center'><span>Faltan campos requeridos por rellenar.</a></span></div>";
  }

?>