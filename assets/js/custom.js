
/******* ENVIOS DE FORULARIOS POR AJAX ************/

/** Envia el intento de login **/
function Login(user,pass,captcha){
    // Validating
	if (user == null ||  user.length<3 || user==" " || user=="") {
    	displayError("user","Revise su nombre de usuario");
    	return;
    }
    if (pass == null || pass.length<3 || pass==" " || pass=="" ){
		displayError("pass","Revise su contraseña");
		return;
 	}
    if (captcha == null || captcha.length=="" || captcha==" " || captcha==""){
		displayError("captcha","Da Click en 'No soy un robot'");
		return;
	}
	// Send data
	var data = {
		user:user,
		pass:pass,
		captcha:captcha
	};
  	Vue.currentPanel.loading = true;
  	$.post("Login/LoginEntryPoint.php",data,function(json_response){
		// Close loading bar
		Vue.currentPanel.loading = false;
		// Get response object
		var response = assertResponse(json_response);
		// Display response in console and in response div
		console.log(response);
		// If there was a login block, take user to the Restore pass panel
		if(response.status == Vue.responseTypes.LOGINBLOCK.code){
			activatePanel("restore");
		}
		setUpResponseMessage(response);
  	});
}

/** Envia el registro **/
function Register(){
	var nombre = $("#nombre");
	var apaterno = $("#apaterno");
	var amaterno = $("#amaterno");
	var celular = $("#celular");
	if(!nameCheck(nombre.val())){
		nombre.addClass("invalid");
		$("#error").html('Revisar Nombre');
		return null;
	} 
	if(!nameCheck(apaterno.val())){
		apaterno.addClass("invalid");
		$("#error").html('Revisar Apellido Paterno');
		return null;
	}
	if(!nameCheck(amaterno.val())){
		amaterno.addClass("invalid");
		$("#error").html('Revisar Apellido Paterno');
		return null;
	}
	if(!phoneCheck(celular.val())){
		celular.addClass("invalid");
		$("#error").html('Checar número de celular');
		return null;
	}

	var data = {
		nombre: nombre.val(),
		apaterno: apaterno.val(),
		amaterno: amaterno.val(),
		cel: celular.val(),
	};
    $.post("Registro/RegistroEntryPoint.php",data,function(json_response){
    	console.log(json_response);
    	// Get response object
		var response = assertResponse(json_response);
		// Display response in console and in response div
		console.log(response);
		$("#registro_respuesta").html("<p>"+ +"</p>")
  	});
}

/** Envia el formulario para crear nip y enviarlo al celular registrado **/
function restore_submit(user){
	var error = "0";
	if (user.length<3 || user==" " || user=="") { error="Revise nombre de usuario"; acc=$('input#user').focus(); }
	if (error=="0"){
			Vue.currentPanel.loading = true;
			$.post("RestorePassword/GenNipEntryPoint.php",{user:user},function(json_response){
				// Close loading bar
				Vue.currentPanel.loading = false;
				// Get response object
				var response = assertResponse(json_response);
				setUpResponseMessage(response);
				// Only if success sending NIP display next panel
				if(response.status == Vue.responseTypes.SUCCESS.code){
					activatePanel('enterNip');
				}
				// Display response in console and in divs
				console.log(response);
				setUpResponseMessage(response);
	      	})
		
	}else{
		alert(error);
	}

}

/** Envia el formulario para crear una nueva contraseña temporal, y la envia al celular registrado **/
function enterNipSubmit(nip,user){
	var error = "0";
	if (user==" " || user=="" || user==null ){ 
		activatePanel('restore');
		Vue.currentPanel.response.message = "Debe ingresar un nombre de usuario primero.";
		return null;
	}
	if (nip==" " || nip=="" || nip==null) { error="Revise NIP"; acc=$('input#nip').focus(); }
	if (error=="0"){
		Vue.currentPanel.loading = true;
		$.post("RestorePassword/PassResetEntryPoint.php",{nip: nip, user: user},function(json_response){
			// Close loading bar
			Vue.currentPanel.loading = false;
			// Get response object
			var response = assertResponse(json_response);
			// Only if success sending NIP display next panel
			if(response.status == Vue.responseTypes.SUCCESS.code){
				activatePanel('enterNipSubmit');
			}
			// Display response in console and in divs
			console.log(response);
			setUpResponseMessage(response);
      	});
		
	}else{
		alert(error);
	}
}

/*************** FUNCIONES DE AYUDA **************/
/** Displays an error in the error panel section **/
function displayError(input,errorMessage){
    	Vue.currentPanel.response = {
    		"message": errorMessage,
    		"color_text": "red-text"
    	}
    	$("#"+input).addClass("invalid");
}

/** Sets up response messages
 *  @param: A json_formatted response obtained from assertResponse()
 **/
function setUpResponseMessage(response_obj){
	let responseTypeObj = filterResponseType(response_obj.status);
	Vue.currentPanel.response.color_text = responseTypeObj.color + "-text";
	Vue.currentPanel.response.message = response_obj.message;
}

/** Filters the response types to match the one with the given response code and returns the response type object **/
function filterResponseType(response_code){
	for (var key in Vue.responseTypes) {
		if(Vue.responseTypes[key].code == response_code){
			return Vue.responseTypes[key];
		}
	}
	return Vue.responseTypes.NEUTRAL;
}

/** Filters the json_response gotten from the Ajax call and returns a parsed json object **/
function assertResponse(json_response){
	try{
		var response = JSON.parse(json_response);
		if(response.status == Vue.responseTypes.ERROR.code){
			console.log(response);
			return response;
		}
		return response;
	}catch (e){
		// json_response was not json formatted so we display whats going on
		// console.log(json_response);
		return {message: "Error interno del sistema, error de programación.",status: -1};
	}
}

/** Restores the values of the globalInputs field on the Vue instance
 * @param: Exceptions: Which fields wont be restored
 **/
function restoreInputs(exceptions){
	if(exceptions == null){
		exceptions = [];
	}
	for(var key in Vue.globalInputs){
		// If the key is an actual field in global input and is not in the exceptions
		if (Vue.globalInputs.hasOwnProperty(key) && exceptions.indexOf(key) == -1) {
			Vue.globalInputs[key] = "";
		}
	}
}

/**
* Activates the panel of given id and it also deactivates all other panels.
* If not found, it just hides all panels. If toStack is true, it adds it to the stack of panels,
* it will usually be set to false when going back to the previous pannel.
* @return: Whether panel activation was succesful
**/
function activatePanel(panelID,toStack=true){
	//Activate labels so that it doesn't overlap the code
	Vue.panels.forEach(function(panel){
		panel.isActive = false;
		if(panel.id == panelID){
			panel.isActive = true;
			//Add to stack if toStack is true
			if(toStack){
				Vue.panelStack.push(Vue.currentPanel);	
			}
			Vue.currentPanel = panel;
			Vue.currentPanel.response = {};
			return true;
		}
	});
	return false;
}


// The model data
var data = new function(){
	// Cada input en el array de panels tiene una propiedead vModel, que apunta como una propiedad de 
	// v-model al objecto globalInputs así: v-model="globalInputs[input.vModel]"
	this.globalInputs = {
		user: "",
		pass: "",
		captcha: "",
		nip: "",
	},
	this.responseTypes = {
		ERROR: {
			code: -1,
			color: "red",
			class: "danger"
		},
		NEUTRAL: {
			code: 0,
			color: "gray",
			class: "default"
		},
		SUCCESS: {
			code: 1,
			color: "green",
			class: "success"
		},
		LOGINBLOCK: {
			code: 2,
			color: "red",
			class: "danger"
		},
		LOGINERROR: {
			code: 3,
			color: "orange",
			class: "warning"
		}
	},
	this.error = "",
	this.mensaje_error_backend = "Ocurrió un error interno en el sistema...",
	this.jsonLoaded = false, // Variable to check that loading initial json data only happens once
	//Constantes de estilos
	this.activeClass =  "show active",
	this.hiddenClass =  "hiddenPanel",
	this.currentPanel =  null,
	this.loadingPanel = false,
	this.panelStack =  [],
	this.panels = [
		{
			id: "login",
			header: "",
			instructions: "",
			response: {},
			inputs:[
				{
					iconClass: "fa-user",
					vModel: "user",
					type: "text",
					id: "user",
					label: "Usuario",
				}		
			],
			buttons: [
				{
					vueFunction: "submitLogin",
					label: "Accesar",
					class: "btn btn-cyan",
					icon: "fa-sign-in"
				},
				{
					vueFunction: "restore",
					label: "Restaurar contraseña",
					class: "btn btn-outline-warning waves-effect btn-sm",
					icon: "fa-question"
				}
			],
			isActive: false,
			loading: false,
			passInput: true,
			extra: "<div class'col'><div v-show='panel.captcha' class='g-recaptcha' data-sitekey='6LdZEwcUAAAAAC4DO6u_4JxHqs_Pqck7vJ9mQfFK'></div></div>"
		},{
			id: "restore",
			header: "Restaurar contraseña",
			instructions: "",
			response: {},
			inputs:[
				{
					iconClass: "fa-user",
					vModel: "user",
					type: "text",
					id: "user",
					label: "Usuario",
				}		
			],
			buttons: [
				{
					vueFunction: "restore_are_you_sure",
					label: "Enviar",
					class: "btn btn-success",
					icon: "fa-question"
				},
				{
					vueFunction: "goBackPanel",
					label: "Regresar",
					class: "btn btn-info btn-sm",
					icon: "fa-backward"
				}
			],
			isActive: false,
			loading: false,
			extra: "",
			
		},{
			id: "restore_are_you_sure",
			header: "Restaurar contraseña",
			instructions: "",
			response: {},
			inputs:[],
			buttons: [
				{
					vueFunction: "restore_submit",
					label: "Enviar",
					class: "btn btn-success",
					icon: "fa-check"
				},
				{
					vueFunction: "goBackPanel",
					label: "Regresar",
					class: "btn btn-info btn-sm",
					icon: "fa-backward"
				}
			],
			isActive: false,
			loading: false,
			extra: "",
			
		},{
			id: "enterNip",
			header: "Insertar NIP",
			instructions: "",
			response: {},
			inputs:[
				{
					iconClass: "fa-key",
					vModel: "nip",
					type: "text",
					id: "nip",
					label: "NIP",
				}		
			],
			buttons: [
				{
					vueFunction: "enterNipSubmit",
					label: "Enviar",
					class: "btn btn-success",
					icon: "fa-paper-plane"
				},
				{
					vueFunction: "goBackPanel",
					label: "Regresar",
					class: "btn btn-info btn-sm",
					icon: "fa-backward"
				}
			],
			isActive: false,
			loading: false,
			extra: "",
			
		},{
			id: "enterNipSubmit",
			header: "Se ha restaurado su contraseña",
			instructions: "",
			response: {},
			inputs:[],
			buttons: [
				{
					vueFunction: "close",
					class: "btn btn-info",
					label: "Terminar",
					icon: "fa-paper-cross"
				}
			],
			isActive: false,
			loading: false,
			extra: "",
			
		}
	]
};


//Vue instance
var Vue = new Vue({
	el: '#main_container',

	methods: {
		//open the form in the login tab
		openLogin(){
			//Hide the register modal
			setTimeout(function () { $('#modalRegister').modal('hide'); });

			//Load modal
			$('#modalLogin').modal('show');
			//Restart panel stack and activate panel login
			this.panelStack = [];
			restoreInputs(["user"]);
			activatePanel("login");
		},
		//open the form in the register tab
		openRegister(){
			//Hide the register modal
			setTimeout(function () { $('#modalLogin').modal('hide'); });

			//Load modal
			$('#modalRegister').modal('show');
			//Restart panel stack and activate panel login
			this.panelStack = [];
			activatePanel("register");
		},
		goBackPanel(){
			//Activates last viewed panel and removes it from the stack
			if(this.panelStack.length > 0){
				activatePanel(this.panelStack.pop().id,false);
			}
		},
		submitLogin(){
			Login(this.globalInputs.user,this.globalInputs.pass,this.globalInputs.ca);
		},
		submitRegister(){
			Register();
		},
		restore(){
			activatePanel("restore");
		},
		restore_are_you_sure(){
			this.globalInputs.user.trim();
			if(this.globalInputs.user){
				activatePanel("restore_are_you_sure");
				this.currentPanel.instructions = "¿Está seguro que quiere restaurar la contraseña del usuario <b>" + this.globalInputs.user + "</b>? Se le enviará un SMS al telefono asociado con esta cuenta con un NIP para verificar seguridad.";
			}else{
				displayError("user","Revisar Usuario");
			}
			
		},
		restore_submit(){
			restore_submit(this.globalInputs.user);
		},
		enterNip(){
			activatePanel("enterNip");
		},
		enterNipSubmit(){			
			enterNipSubmit(this.globalInputs.nip,this.globalInputs.user);
		},
		restorePass(){
			// Restore all inputs except for user
			restoreInputs(["user"]);
			activatePanel("newPassResult");
		},
		close(){
			// Closes modal
			$('#modalLogin').modal('hide');
		},
		//Helper method to call the Vue function passed on the button e.g.: submitLogin(), restore(), etc
		call(vueFunction){
			this[vueFunction]();
		}
	},
	data: data

})