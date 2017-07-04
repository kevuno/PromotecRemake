/******* ENVIOS DE FORULARIOS POR AJAX ************/

/** Envia el intento de login **/
function Login(user,pass,captcha){
    error="0";
    
    if (ca.length=="") { error="Da Click en 'No soy un robot'"; acc=null; }
    if (pass.length<3 || pass==" " || pass=="") { error="Revise su contraseña"; acc=$('input#pass').focus(); }
    if (user.length<3 || user==" " || user=="") { error="Revise nombre de usuario"; acc=$('input#user').focus(); }
	
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
function registrar(){

	var nombre = $("#nombre").val;
	var apaterno = $("#apaterno").val;
	var amaterno = $("#amaterno").val;
	var celular = $("#celular").val;
	var telefono = $("#telefono").val;
	var email = $("#email").val;
	var referido = $("#referido").val;
	var calle = $("#calle").val;
	var numext = $("#numext").val;
	var cp = $("#cp").val;

	var data = {
		nom: nombre,
		apa: apaterno,
		ama: amaterno,
		cel: celular,
		tel: telefono,
		correo: email,
		referido: referido,
		edi: Vue.selected_estado.name,
		munic: Vue.selected_municipio.name,
		ciudad: Vue.selected_ciudad,
		colonia: Vue.selected_colonia.name,
		cp: cp,
		calle: calle,
		next: numext
	};
	console.log(data);
    $.post("Registro/registro.php",data,function(json_response){
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

/** Loads the json files with the estados and municipios and save the data **/
function loadJsonFileOntoVar(filename,type){
	$('.mdb-select').material_select('destroy');
	$.getJSON(filename, function(data){
		// Save the response data to the corresponding data field (estado or municipio)
		Vue[type] = data;		
		if (type == "estados"){
			// Select first estado
			Vue.selected_estado = Vue.estados[0];
		}
	});
}

/**Function to load modal and necessary files in case they haven't been loaded **/
function loadFilesAndModal(){
	//Only load JSON files once
	if(!Vue.jsonLoaded) {
		loadJsonFileOntoVar("assets/json/estados.json","estados");
		loadJsonFileOntoVar("assets/json/municipios.json","municipios");
		Vue.jsonLoaded = true;
	}
	//Load modal
	$('#modalLRForm').modal();
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
	console.log('activating panel');
	Vue.panels.forEach(function(panel){
		panel.isActive = false;
		if(panel.id == panelID){
			panel.isActive = true;
			//Add to stack if toStack is true
			if(toStack){
				Vue.panelStack.push(Vue.currentPanel);	
			}
			Vue.currentPanel = panel;
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
	this.estados =  [],
	this.municipios =  [],
	this.selected_estado =  {},
	this.selected_municipio =  {},
	this.selected_ciudad =  {},
	this.selected_colonia =  {},
	this.active_municipios = [], // Objectos de Municipios activos despues de que se selecciono un estado
	this.active_ciudades = [], // Strings de ciudades activas despues de que se selecciono un municipio
	this.active_colonias = [], // Objectos de colonias activas despues de que se selecciono una ciudad
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
			header: "Acceso",
			instructions: "",
			response: "",
			inputs:[
				{
					iconClass: "fa-envelope",
					vModel: "user",
					id: "user",
					label: "Usuario",
				},
				{
					iconClass: "fa-lock",
					vModel: "pass",
					id: "pass",
					label: "Contraseña",
				}				
			],
			buttons: [
				{
					vueFunction: "submitLogin",
					label: "Login",
					class: "btn btn-default",
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
			captcha: true
			
		},{
			id: "restore",
			header: "Restaurar contraseña",
			instructions: "",
			response: "",
			inputs:[
				{
					iconClass: "fa-user",
					vModel: "user",
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
			
		},{
			id: "enterNip",
			header: "Insertar NIP",
			instructions: "",
			response: "",
			inputs:[
				{
					iconClass: "fa-key",
					vModel: "nip",
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
			
		}
	]
};


//Vue instance
var Vue = new Vue({
	el: '#main_container',

	methods: {
		//open the form in the login tab
		openLogin(){
			//Hide the register panel
			$('#register_tab_link').removeClass("active");
			$('#register_panel').removeClass("show active");			
			$('#login_tab_link').addClass("active");
			$('#login_panel').hide();
			$('#login_tab').show();
			$('#register_tab').hide();
			$('#register_panel').hide();
			$('#login_panel').show();
			//Load json files
			loadFilesAndModal();
			//Restart panel stack and activate panel login
			this.panelStack = [];
			restoreInputs(["user"]);
			activatePanel("login");
		},
		//open the form in the register tab
		openRegister(){
			$('#login_tab_link').removeClass("active");
			$('#login_panel').removeClass("show active");
			$('#register_tab_link').addClass("active");
			$('#register_panel').addClass("show active");
			$('#login_panel').hide();
			$('#login_tab').hide();
			$('#register_panel').show();
			$('#register_tab').show();
			//Load json files
			loadFilesAndModal();
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
			$('#modalLRForm').modal('hide');
		},
		//Helper method to call the Vue function passed on the button e.g.: submitLogin(), restore(), etc
		call(vueFunction){
			this[vueFunction]();
		}
	},
	data: data

})