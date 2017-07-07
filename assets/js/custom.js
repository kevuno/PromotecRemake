
/******* ENVIOS DE FORULARIOS POR AJAX ************/

/** Envia el intento de login **/
function Login(user,pass,captcha){
    error="0";
    
    if (captcha == null || captcha.length=="" || captcha==" " || captcha=="")  { error="Da Click en 'No soy un robot'"; acc=null; }
    if (pass == null || pass.length<3 || pass==" " || pass=="" ) { error="Revise su contraseña"; }
    if (user == null ||  user.length<3 || user==" " || user=="") { error="Revise nombre de usuario"; }
	
    if (error=="0"){
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
			Vue.currentPanel.response = response.message;

    		if(response.status == Vue.responseTypes.LOGINBLOCK){
    			activatePanel("restore");
    			Vue.currentPanel.instructions = response.message;
    		}
    			

	        	
			
      	})
    }else{
		alert(error);
    }
}

/** Envia el registro **/
function Register(){
	var nombre = $("#nombre").val();
	var apaterno = $("#apaterno").val();
	var amaterno = $("#amaterno").val();
	var celular = $("#celular").val();
	if(!(nameCheck(nombre) && nameCheck(apaterno) && nameCheck(amaterno))){
		alert('Checar Nombre o Apellidos');
		return null;
	}
	if(!phoneCheck(celular)){
		alert('Checar número de celular');
		return null;
	}

	var data = {
		nombre: nombre,
		apaterno: apaterno,
		amaterno: amaterno,
		cel: celular,
	};
    $.post("Registro/RegistroEntryPoint.php",data,function(json_response){
    	console.log(json_response);
    	var response = JSON.parse(json_response);
		console.log(response);
    	Vue.currentPanel.response = response.message;
  	});
}

/** Envia el formulario para crear nip y enviarlo al celular registrado **/
function restore_submit(user){
	var error = "0";
	if (user.length<3 || user==" " || user=="") { error="Revise nombre de usuario"; acc=$('input#user').focus(); }
	if (error=="0"){
		if(confirm("Está seguro que quiere restaurar la contraseña del usuario " + user + ". Se le enviará un SMS al telefono asociado con esta cuenta con un NIP para verificar seguridad.")){
			Vue.currentPanel.loading = true;
			$.post("RestorePassword/GenNipEntryPoint.php",{user:user},function(json_response){
				// Close loading bar
				Vue.currentPanel.loading = false;
				// Get response object
				var response = assertResponse(json_response);
				// Display response in console and in response div
				console.log(response);
				Vue.currentPanel.response = response.message;

				// Only if success sending NIP display next panel
				if(response.status == Vue.responseTypes.SUCCESS){
					// If Response was of type success, then data contains the phonenumber to 
					// which the NIP was sent
					// Load EnterNip Panel
					activatePanel('enterNip');
					Vue.currentPanel.instructions = response.message;
				}
	      	})
		}
	}else{
		alert(error);
	}

}

/** Envia el formulario para crear una nueva contraseña temporal, y la envia al celular registrado **/
function enterNipSubmit(nip,user){
	var error = "0";
	if (user==" " || user=="" || user==null ){ 
		activatePanel('restore');
		Vue.currentPanel.response = "Debe ingresar un nombre de usuario primero.";
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
			// Display response in console and in response div
			console.log(response);
			Vue.currentPanel.response = response.message;

			// Only if success sending NIP display next panel
			if(response.status == Vue.responseTypes.SUCCESS){
				// If Response was of type success, then data contains the phonenumber to 
				// which the NIP was sent
				// Load EnterNip Panel
				activatePanel('enterNip');
				Vue.currentPanel.instructions = response.message;
			}
      	});
		
	}else{
		alert(error);
	}
}

/*************** FUNCIONES DE AYUDA **************/

/** Filters the json_response gotten from the Ajax call and returns a parsed json object **/
function assertResponse(json_response){
	try{
		var response = JSON.parse(json_response);
		if(response.status == Vue.responseTypes.ERROR){
			console.log(response);
			return response;
		}
		return response;
	}catch (e){
		// json_response was not json formatted so we display whats going on
		console.log(json_response);
		return {message: "Error interno del sistema, error de programación."};
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
			Vue.currentPanel.response = "";
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
		ERROR: -1,
		NEUTRAL: 0,
		SUCCESS: 1,
		LOGINBLOCK: 2,
		LOGINERROR: 3
	},
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
			response: "",
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
					class: "btn btn-default btn-lg",
					icon: "fa-lock"
				},
				{
					vueFunction: "restore",
					label: "Restaurar contraseña",
					class: "btn btn-secondary",
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
			response: "",
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
					vueFunction: "restore_submit",
					label: "Enviar",
					class: "btn btn-success",
					icon: "fa-question"
				},
				{
					vueFunction: "goBackPanel",
					label: "Regresar",
					class: "btn btn-info",
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
			response: "",
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
					class: "btn btn-info",
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
			response: "",
			inputs:[],
			buttons: [
				{
					vueFunction: "close",
					label: "Terminar"
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
		//Updates the list of municipios by filtering the ones that match the estado id
		updateActiveMunicipios(){
			this.active_municipios = this.municipios.filter(function(el){
				//Because out of the scope of the methdo, it needs "Vue." reference instead of "this."
				return el.state_id == Vue.selected_estado.id;
			});
		},
		//Updates the list of ciudades by filtering depending on the selected estado and municipio
		updateActiveCiudades(){
			var data = {
				e: this.selected_estado.name,
				m: this.selected_municipio.name
			}
			// Call the bd to get the list of ciudades
			$.post("form_sqls/ciudad.php",data,function(respuesta){
	        	console.log("respuesta: "+respuesta);
	        	var ciudades = JSON.parse(respuesta);
	        	this.active_ciudades = ciudades;
	      	});
		},
		//Updates the list of ciudades by filtering depending on the selected estado and municipio
		updateActiveColonias(){
			var data = {
				e: this.selected_estado.name,
				m: this.selected_municipio.name,
				c: this.selected_ciudad
			}
			// Call the bd to get the list of colonias as JSON string
			$.post("form_sqls/colonia.php",data,function(respuesta){
	        	console.log("respuesta: "+respuesta);	        	
	        	var colonias = JSON.parse(respuesta);
	        	this.active_ciudades = colonias;
	      	});
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
		restore_submit(){
			restore_submit(this.globalInputs.user);
		},
		enterNip(){
			activatePanel("enterNip");
		},
		enterNipSubmit(){
			activatePanel
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