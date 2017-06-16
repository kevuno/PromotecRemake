<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
  $(document).keypress(function(e) {
    if(e.which == 13) {
      checkLogin();
    }
  });
  function checkLogin(){
    user=$('input#user').val();
    pass=$('input#pass').val();
    ca=$('#g-recaptcha-response').val();

    error="0";

    if (ca.length=="") { error="Da Click en 'No soy un robot'"; acc=null; }
    if (pass.length<3 || pass==" " || pass=="") { error="Revise su contraseña"; acc=$('input#pass').focus(); }
    if (user.length<3 || user==" " || user=="") { error="Revise nombre de usuario"; acc=$('input#user').focus(); }

    if (error=="0") {
      t3=user+","+pass+","+ca;
      //alert(t3);
      $.post("check_login.php",{login:t3, cl:ca},function(respuesta){
        //console.log("respuesta: "+respuesta);
       // alert(respuesta);
        if (respuesta.substring(0,2)==="UV") {
          window.location="/promotor";
        }
        else if (respuesta.substring(0,2)==="E2") {
        $("#myModalPassword").modal('show');
        grecaptcha.reset();
        $('#msj').html('');
        grecaptcha.reset();
        } 
        else if (respuesta.substring(0,2)==="EC") {
          grecaptcha.reset();
          alert("Codigo captcha invalido");
        } else if(respuesta==="Uinvalid"){
           $('#myModalogin').modal('show');grecaptcha.reset();
        } else {
          $("#msj").html("Usuario o contraseña invalido");
          alert("El usuario o la contraseña no son validos");grecaptcha.reset();
        }
      })
    } else {
      alert(error);
      acc;
    }
  }

  function error(pagina) {
    $('#error').html('<div class="text-center"><img src="img/loading.gif"></div>');
    $('#error').load(pagina)
  }
  function captcha() {
	 $('#cap').html('<div class="text-center"><img src="img/loading.gif"></div>');
    $('#cap').load('captcha2.php?f='+ new Date().getTime());
  }
  $(document).ready(function(){
      $("[rel=tooltip]").tooltip({ placement: 'left'});
  });
</script>

<style type="text/css">
  .form-signin {
    max-width: auto;
    padding: 3px 7px 3px;
    margin: 5px auto 5px;
    color: #00306d;
    margin-top: 43%;
  }
  .form-signin .form-signin-heading,
  .form-signin .checkbox {
    color: #FFF;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
  }
  .form-signin input[type="text"],
  .form-signin input[type="password"] {
    font-size: 11px;
    margin-bottom: 15px;
    padding: 7px 9px;
  }

  table { margin-bottom: 15px; }
.disable {
  display: none;
}
</style>

<div class="form-signin">
  <table width="100%">
    <tr>
      <td><input type="text" class="form-control input-sm" placeholder="Nombre de usuario" name="user" id="user" tabindex="1" required style="width:60%"></td>
    </tr>
    <tr>
      <td><input type="password" class="form-control input-sm" placeholder="Contrase&ntilde;a" name="pass" id="pass" tabindex="2" required style="width:60%"></td>
    </tr>
    <tr>
      <td>
            <div id="rcap" class="text-xs-center">

            </div>
      </td>
    </tr>
    <tr>
      <td><div id="error"></div></td>
    </tr>
    <tr>
      <td><a href="#" class="btn btn-primary btn-block" onClick="checkLogin();" tabindex="4" style="width:60%"><i class="fa fa-sign-in"></i> Ingresar</a></td>
    </tr>
    <tr>
      <td>
        <div class="text-center" id="msj" style="background-color: red; color: white; font-weight: bold;"></div>
      </td>
    </tr>
    <tr>
      <td>
<p class="label label-info text-center" >Si olvido su contraseña para recuperarla</p><br>
<p class="label label-info text-center" > de <a href="#" data-toggle="modal" data-target="#myModalRecuContra">click aquí.</a></p>
    </td>
  </tr>
  </table>
</div>
<div class="modal fade" id="myModalRecuContra" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Recuperar contraseña</h4>
      </div>
      <div class="modal-body">
      <div id="panel1">
        <div class="row" id="msj_depo">

          <div class="col-md-12 col-sm-12 col-xs-12" style="width:50%">
      <label>Para recuperar su contraseña ingrese su usuario y de click en el boton "recuperar contraseña"</label>
      <br><br>
            <label>Usuario:<span class="txr">*</span></label>
            <input type="text" class="form-control input-sm notEmpty" name="userpass" id="userpass" placeholder="Usuario" value="">
          </div>
        </div>

        <div class="row">
    <br><br>
          <div class="col-md-6 col-sm-6 col-xs-12" id="button_depo">
            <button class="btn btn-success" onclick="generaNip();" name="Submit">Recuperar contraseña</button>
          </div>
        </div>
      </div>
        <br>
      <div id='panel2' class='disable'>
        <label>Se ha enviado un mensaje a su celular con un NIP de cuatro d&iacute;gitos, por favor ingrese el NIP  en el siguiente campo para continuar con su cambio de contrase&ntilde;a</label><br><br>
        <label>Ingrese NIP:</label><br>
        <input class="form-control input-sm notEmpty" name='nip' id='nip' minlength='4' maxlength='4'><br><br><button class='btn btn-primary' onclick='nuevopas();'>Continuar</button>
      </div>
    
        <br>
        <span id="pass"></span>
        <span id="err"></span>
      </div>
      <div class="modal-footer">
        <button type="button" id="close" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>

</div>

<!-- Modal -->
<div id="myModalPassword" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cambiar contraseña</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 alert alert-danger text-center">
        <label>Por seguridad su contraseña ha caducado, por favor cambie su contraseña para poder ingresar al sistema.</label>
          </div>
        </div>
          <div class="row">
            <div class="col-md-8 col-ms-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
              <label>Ingrese Contrase&ntilde;a anterior:</label>
              <input type="password" class="form-control input-sm notEmptyP" name="passold" id="passold" placeholder="Contrase&ntilde;a" tabindex="2" required>
            </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"><br>
        <p class="label label-info text-center">La contraseña debe contener como mínimo un número,  una letra minúsculas, una mayúsculas y un máximo y mínimo de 8 dígitos.<br> Además no puede contener números continuos como por ejemplo “123”.</p>
          </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-ms-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
              <label>Nueva Contrase&ntilde;a:</label>
              <input type="password" class="form-control input-sm notEmptyP" name="newPass" id="newPass" minlength="8" maxlength="8" placeholder="Contrase&ntilde;a" tabindex="2" required>
            </div>
            <div class="col-md-8 col-ms-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
              <label>Confirmar Contrase&ntilde;a:</label>
              <input type="password" class="form-control input-sm notEmptyP" name="newPass2" id="newPass2" minlength="8" maxlength="8" onchange="vali_con();" placeholder="Contrase&ntilde;a" tabindex="2" required>
            </div>
            
          </div>
    <div class="row" align="center" style="display:block">
            <div id="btnpass" style="display:block">
              <br><button class="btn btn-xs btn-info" onclick="CambContra();" tabindex="4"><i class="fa fa-lock"></i>Cambiar</button><br>
            </div>
          </div>
          <div class="row" align="center" style="display:block">
          <div id="errPass"></div>  
          </div>
      </div>
    </div>

  </div>
</div>

<script language=javascript>
  recaptcha();

  //setTimeout(recaptcha, 1000);
  function recaptcha() {
    $('#rcap').append('<div id="rcap"><div class="g-recaptcha" data-sitekey="6LdZEwcUAAAAAC4DO6u_4JxHqs_Pqck7vJ9mQfFK" style="transform:scale(0.67);-webkit-transform:scale(0.67);transform-origin:0 0;-webkit-transform-origin:0 0;margin: 0 0; width:60%;"></div></div>');
  }


function generaNip(){
  $("#err").empty();
  us=$("#userpass").val();
  if(us!=""){
  
  t3="generaNip2,"+us;

  $.post("promotor/php/data.php",{datos:t3, a:"A1D6B6D7"},function(respuesta){
  x=respuesta.split("|");
    //console.log(respuesta);
  if (x[0]=="S") {
  $("#panel1").addClass('disable');
  $("#panel2").removeClass('disable');
  }else if(x[0]=="E2") {
  $("#err").html('<label class="label label-danger">'+x[1]+'</label>');
  }
  })

  }else{
     $("#err").append('<label class="alert alert-danger">Hace falta el Usuario</label>');
  }

}


function nuevopas() {
  user=$("#userpass").val();
  nip=$("#nip").val();
  t3="recu_contra,"+user+","+nip;
  $("#err").empty();
  if(nip!=""){

  $.post("promotor/php/data.php",{datos:t3, a:"A1D6B6D7"},function(respuesta){
  x=respuesta.split("|");
    //console.log(respuesta);
  if (x[0]=="S") {
    cad="<label class='alert alert-success'>"+x[1]+"</label>";
    $("#panel2").html(cad);

  }else if(x[0]=="E") {
    $("#nip").val('');
    $("#err").html('<label class="label label-danger">'+x[1]+'</label>');
  }
  })

  }else{
    $("#err").html('<label class="label label-danger">Agregue NIP</label>');
  }
}

function recu_contra(){
  $("#err").empty();
  us=$("#userpass").val();
  if(us!=""){
    $.post("recu_contra.php", {usuario:us, a:"A1D6B6D7"}, function(resp){
    x=resp.split("|");
    $("#userpass").val("");
   if (x[0]=='E') {
    $("#err").append('<label class="alert alert-danger">'+x[1]+'</label>'); }
   else { //$("#myModalRecuContra").hide();
   $("#err").append('<label class="alert alert-success">'+x[1]+'</label>'); }

  });
  }else{
     $("#err").append('<label class="alert alert-danger">Hace falta el Usuario</label>');
  }

}

function CambContra(){
  us=$("#user").val();
  oldPass=$("#passold").val();
  newPass=$("#newPass").val();
  Seg=$("#newPass").val();
  Seg2=$("#newPass").val();
  segu=seguridad_clave(Seg);
  $("#errPass").empty();
   var error = 0;
 $('.notEmptyP').each(function(i, camp){
  if ($(camp).val() == '') {
   $(camp).addClass("notValid");
   error++;
  } else {
   $(camp).removeClass("notValid");
  }
 });
  var c = /l*01|l*12|l*23|l*34|l*45|l*56|l*67|l*78|l*89/;
 if(error > 0){
   $("#errPass").html('<label class="label label-danger">Por favor revise los campos en rojo</label>');
 }else{
  if(Seg.length==8){
    if(!c.test(Seg)){
       if(segu>=90){
    $("#btnpass").hide();
    $("#errPass").empty();
    $("#errPass").html('<div class="text-center"><img src="img/loading.gif"></div>');
     t3="CambContra,"+us+","+newPass+","+new Date().getTime();
        $.post("promotor/php/data.php",{datos:t3, a:"A1D6B6D7"},function(respuesta){
         x=respuesta.split("|");
         console.log("respuesta: "+x);
           if (x[0]=='E') {$("#errPass").html('<label class="label label-danger">'+x[1]+'</label>'); $("#btnpass").show();}
            else if(x[0]=='S'){$("#errPass").html('<label class="label label-success">'+x[1]+'</label>');
           setTimeout('location.reload();',3000);
          }
          });
  }else{
    $("#errPass").html('<label class="label label-danger">Nivel de seguridad bajo <br> Recuerde, la contrase&ntilde;a debe contener n&uacute;meros, letras min&uacute;sculas y may&uacute;sculas</label>');
  }
    }else{
    $("#errPass").html('<label class="label label-danger">La contrase&ntilde;a no puede tener n&uacute;meros continuos</label>');
    }
   
  }else{
    $("#errPass").html('<label class="label label-danger">La contrase&ntilde;a debe contener un m&aacute;ximo y m&iacute;nimo de 8 d&iacute;gitos</label>');
  }
 }
 
}

//VALIDAR SEGURIDAD DE CONTRASEÑA
function seguridad_clave(clave){
   var seguridad = 0;
   if (clave.length!=0){
      if (tiene_numeros(clave) && tiene_letras(clave)){
         seguridad += 30;
      }
      if (tiene_minusculas(clave) && tiene_mayusculas(clave)){
         seguridad += 30;
      }
      if (clave.length >= 4 && clave.length <= 5){
         seguridad += 10;
      }else{
         if (clave.length >= 6 && clave.length <= 8){
            seguridad += 30;
         }else{
            if (clave.length > 8){
               seguridad += 40;
            }
         }
      }
   }
   return seguridad            
} 

var numeros="0123456789";

function tiene_numeros(texto){
   for(i=0; i<texto.length; i++){
      if (numeros.indexOf(texto.charAt(i),0)!=-1){
         return 1;
      }
   }
   return 0;
}

var letras="abcdefghyjklmnñopqrstuvwxyz";

function tiene_letras(texto){
   texto = texto.toLowerCase();
   for(i=0; i<texto.length; i++){
      if (letras.indexOf(texto.charAt(i),0)!=-1){
         return 1;
      }
   }
   return 0;
}

function tiene_minusculas(texto){
   for(i=0; i<texto.length; i++){
      if (letras.indexOf(texto.charAt(i),0)!=-1){
         return 1;
      }
   }
   return 0;
}

var letras_mayusculas="ABCDEFGHYJKLMNÑOPQRSTUVWXYZ";

function tiene_mayusculas(texto){
   for(i=0; i<texto.length; i++){
      if (letras_mayusculas.indexOf(texto.charAt(i),0)!=-1){
         return 1;
      }
   }
   return 0;
}

function vali_con(){
   var num1=$('#newPass').val();
    var num2=$('#newPass2').val();
    if(num1!==num2){
      $('#errPass').html('Las Contraseñas no coinciden');
   $('#errPass').addClass("label label-danger");
    $('#newPass2').val("");
    }else{
      $('#errPass').html('');
   $('#errPass').removeClass("label label-danger");
    }
}
</script>
