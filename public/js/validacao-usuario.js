$().ready(function() {
	$('#form_cadastro_usuario').validate({
		rules: {           
			login: {
				required: true
			},
			senha: {
				required: true
			},
			confSenha: {
				required: true,
				equalTo: '#senha'
			},
			email : {
				required: true
			}
		},
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		},
		messages: {
			login: {
				required: "Este campo não pode ser vazio",
			},
			senha: {
				required: "Este campo não pode ser vazio",
			},
			confSenha: {
				required: "Este campo não pode ser vazio",
				equalTo: "A senha não confere."
			},
			email : {
				required: "Este campo não pode ser vazio",
			}
		}
	});
});