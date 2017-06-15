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
	selected_estado: {
		"id": 0,
		"name": "Seleccione"
	},
	active_municipios: [],
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
			$('#login_tab').show();
			$('#register_tab').hide();
			$('#register_tab').hide();
			loadFilesAndModal();
		},
		//open the form in the register tab
		openRegister(){
			$('#login_tab_link').removeClass("active");
			$('#login_panel').removeClass("show active");	
			$('#register_tab_link').addClass("active");
			$('#register_panel').addClass("show active");
			$('#login_tab').hide();
			$('#register_tab').show();
			loadFilesAndModal();			
		},
		//Updates the list of municipios by filtering the ones that match the estado id
		updateActiveMunicipios(){
			this.active_municipios = this.municipios.filter(function(el){
				//Because out of the scope of the methdo, it needs "Vue." reference instead of "this."
				return el.state_id == Vue.selected_estado.id;
			});
		}
	},
	data: data

})