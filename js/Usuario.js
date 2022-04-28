jQuery(function($) {
	
	$(document).ready(function(){

		$('#atribuiPerfil').on('click', function() {	
			$.ajax({
				beforeSend: function() {},
				url: $.fn.getUrlAjax(),
				type: "POST",
				data: {
					action 	            : "Handler_SA",
					class               : 'Usuario',
					funcao              : 'atribuiPerfil',
					IDPerfil			: $('#IDPerfil').val(),
					IDEmpresa			: $('#IDEmpresa').val(),
					IDUsuario			: $('#IDUsuario').val(),    		
				}
			}); 
		});

		$('#salvarUsuario').on('click', function() {	
			$.ajax({
				beforeSend: function() {},
				url: $.fn.getUrlAjax(),
				type: "POST",
				data: {
					action 	            : "Handler_SA",
					class               : 'Usuario',
					funcao              : 'salvaUsuario',
					user_login 			: $('#usuario').val(),
					user_pass			: $('#senha').val(),
					user_nicename       : $('#nome').val(),
					user_email          : $('#email').val(),
					display_name        : $('#nome').val()	  		
				}
			}); 
		});

		$('#editarUsuario').on('click', function() {	
			$.ajax({
				beforeSend: function() {},
				url: $.fn.getUrlAjax(),
				type: "POST",
				data: {
					action 	            : "Handler_SA",
					class               : 'Usuario',
					funcao              : 'salvaUsuario',
					id_user				: $('#idusuario').val(),
					user_login 			: $('#usuario').val(),
					user_pass			: $('#senha').val(),
					user_nicename       : $('#nome').val(),
					user_email          : $('#email').val(),
					display_name        : $('#nome').val()	  		
				}
			}); 
		});

		$('#carregaModalUsuario').on('click', function() {		
			$('#modal_editar_usuario').modal('show');
		});

	});
});