$().ready(function() {
	$('#form_login').validate({
		rules: {           
			login: {
				required: true
			},
			senha: {
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
			}
		}
	});
});