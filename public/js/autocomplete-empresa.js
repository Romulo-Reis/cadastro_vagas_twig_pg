var idempresa = null;

var optionsEmpresa = {
	url: function(empresa) {
		return "http://localhost/cadastro_vagas_twig_pg/empresa/autoComplete/" + empresa;
	},

	getValue: function(element) {
		return element.nomefantasia;
	},

	list: {		
		onChooseEvent: function() {	
			idempresa = $("#autocomplete-empresa").getSelectedItemData().idempresa;		
			$('#empresa').val(idempresa);			
		},

		onHideListEvent: function(){
			if(idempresa == null){
				$("#autocomplete-empresa").val('');	
			}		
		}

	}
};

$("#autocomplete-empresa").easyAutocomplete(optionsEmpresa);
