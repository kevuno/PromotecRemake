//Check login
$(document).ready(function() {
  //var $eventSelect = $(".js-example-basic-single").select2({width: '70%'});
});


/** Envia el intento de login **/
function checkLogin(user,pass,captcha){
    error="0";
    
    //if (ca.length=="") { error="Da Click en 'No soy un robot'"; acc=null; }
    if (pass.length<3 || pass==" " || pass=="") { error="Revise su contraseña"; acc=$('input#pass').focus(); }
    if (user.length<3 || user==" " || user=="") { error="Revise nombre de usuario"; acc=$('input#usuario').focus(); }
	
    if (error=="0"){
		var t3 = user+","+pass+","+captcha;
      	console.log(t3);
      	$.post("Login/check_login.php",{login:t3},function(respuesta){
        	console.log("respuesta: "+respuesta);
        	$("#respuesta").html(respuesta);


      	})
    }else{
		alert(error);
		 ;
    }
}

function sendNip(user){

}



//Loading the json files with the estados and municipios and save the data
function loadJsonFileOntoVar(filename,type){
	$('.mdb-select').material_select('destroy');
	$.getJSON(filename, function(data){
		// Save the response data to the corresponding data field (estado or municipio)
		Vue[type] = data;
		
		if (type == "estados"){
			//Select first estado
			Vue.selected_estado = Vue.estados[0];	
		}
	});
}

//Function to load modal and necessary files in case they haven't been loaded
function loadFilesAndModal(){
	//Only load JSON files once
	if(!Vue.loaded) {
		loadJsonFileOntoVar("assets/json/estados.json","estados");
		loadJsonFileOntoVar("assets/json/municipios.json","municipios");
		Vue.loaded = true;
	}
	//Load modal
	$('#modalLRForm').modal();
}





/**
* Activates the panel of given id and it also deactivates all other panels.
* If not found, it just hides all panels. If toStack is true, it adds it to the stack of panels.
**/
function activatePanel(panelID,toStack=true){
	Vue.panels.forEach(function(panel){
		panel.isActive = false;
		if(panel.id == panelID){
			panel.isActive = true;
			//Add to stack if toStack is true
			if(toStack){
				Vue.panelStack.push(Vue.currentPanel);	
			}
			Vue.currentPanel = panel;
		}

	});

}


/**
* Go to latest panel
**/
function goBackPanel(){
	if(Vue.panelStack.length > 0){

		//Activate panel and deactivate all other panels
		activatePanel(Vue.panelStack.pop().id,false);
		console.log(Vue.panelStack);
	}
}




// The model data
var data = new function(){
	// Cada input en el array de panels tiene una propiedead vModel, que apunta como una propiedad de 
	// v-model al objecto globalInputs así: v-model="globalInputs[input.vModel]"
	this.globalInputs = {
		user: "",
		pass: "",
		captcha: "",
		NIP: "",
		phoneNumber: ""
	},
	this.estados =  [],
	this.municipios =  [],
	this.selected_estado =  {},
	this.active_municipios = [], // Municipios activos despues de que se selecciono un estado
	this.loaded = false, // Variable to check that loading json data only happens once
	//Constantes de estilos
	this.activeClass =  "show active",
	this.hiddenClass =  "hiddenPanel",
	this.currentPanel =  null,
	this.panelStack =  [],
	this.panels = [
		{
			id: "login",
			header: "Acceso",
			instructions: "",
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
					label: "Login"
				},
				{
					vueFunction: "forgot_panel",
					label: "Olvido su contraseña? "
				}
			],
			isActive: true,
			captcha: true
			
		},{
			id: "forgot",
			header: "Olvidó su Contraseña?",
			instructions: "",
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
					vueFunction: "forgot_submit",
					label: "Enviar"
				},
				{
					vueFunction: "goBackPanel",
					label: "Regresar"
				}
			],
			isActive: true,
			captcha: false
			
		},{
			id: "sendNip",
			header: "Insertar NIP",
			instructions: "Se le ha enviado un nip al telefono " + this.globalInputs.phoneNumber + ". Porfavor ingréselo a continuación</a>",
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
					vueFunction: "sendNip",
					label: "Enviar"
				},
				{
					vueFunction: "goBackPanel",
					label: "Regresar"
				}
			],
			isActive: true,
			captcha: false
			
		},{
			id: "newPass",
			header: "Nueva contraseña",
			instructions: "Para restaurar su contraseña, a continuacion escriba su nueva contraseña.",
			inputs:[
				{
					iconClass: "fa-lock",
					vModel: "nip",
					id: "nip",
					label: "NIP",
				}		
			],
			buttons: [
				{
					vueFunction: "sendNip",
					label: "Enviar"
				},
				{
					vueFunction: "goBackPanel",
					label: "Regresar"
				}
			],
			isActive: true,
			captcha: false
			
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
		submitLogin(){
			checkLogin(this.globalInputs.user,this.globalInputs.pass,this.globalInputs.ca);
		},
		forgot_panel(){
			activatePanel("forgot");
		},
		forgot_submit(){
			activatePanel("sendNip");
		},
		goBackPanel(){
			goBackPanel();
		},
		sendNip(){

		},
		//Helper method to call the Vue function passed on the button e.g.: submitLogin(), forgot_panel(), etc
		call(vueFunction){
			this[vueFunction]();
		}
	},
	data: data

})