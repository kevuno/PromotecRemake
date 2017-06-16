//Check login

function checkLogin(user,pass,captcha){
    error="0";
    /*
    if (ca.length=="") { error="Da Click en 'No soy un robot'"; acc=null; }
    if (pass.length<3 || pass==" " || pass=="") { error="Revise su contraseña"; acc=$('input#pass').focus(); }
    if (user.length<3 || user==" " || user=="") { error="Revise nombre de usuario"; acc=$('input#user').focus(); }
	*/
    if (error=="0"){
		var t3 = user+","+pass+","+captcha;
      	console.log(t3);
      	$.post("check_login.php",{login:t3},function(respuesta){
        	console.log("respuesta: "+respuesta);
        	/*
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
			*/
      	})
    }else{
		alert(error);
		acc;
    }
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
	//Only load json files once
	if (!Vue.loaded) {
		loadJsonFileOntoVar("assets/json/estados.json","estados");
		loadJsonFileOntoVar("assets/json/municipios.json","municipios");
		Vue.loaded = true;
	}
	//Load modal
	$('#modalLRForm').modal();
}

// The model data
var data = {
	estados: [],
	municipios: [],
	selected_estado: {},
	active_municipios: [], // Municipios seleted after 
	loaded: false, // Variable to check that loading json data only happens once
}




//Vue instance
var Vue = new Vue({
	el: '#main_content',

	methods: {
		//open the form in the login tab
		openLogin(){
			$('#register_tab_link').removeClass("active");
			$('#register_panel').removeClass("show active");			
			$('#login_tab_link').addClass("active");
			$('#login_panel').addClass("show active");
			$('#login_panel').hide();
			$('#login_tab').show();
			$('#register_tab').hide();
			$('#register_panel').hide();
			$('#login_panel').show();
			loadFilesAndModal();
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
			loadFilesAndModal();			
		},
		//Updates the list of municipios by filtering the ones that match the estado id
		updateActiveMunicipios(){
			this.active_municipios = this.municipios.filter(function(el){
				//Because out of the scope of the methdo, it needs "Vue." reference instead of "this."
				return el.state_id == Vue.selected_estado.id;
			});
		},
		submitLogin(){
		    user=$('input#usuario').val();
		    pass=$('input#pass').val();
		    ca=$('#g-recaptcha-response').val();
			checkLogin(user,pass,ca);
		}
	},
	data: data

})