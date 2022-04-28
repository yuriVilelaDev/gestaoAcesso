jQuery(function($) {
	
	$(document).ready(function(){
		// MONTA ACOES DOS BOTES EM LOCAIS
		montaActionsButtons();
        //console.log('montato empresas.js');

        /**codigo usado para testar o modo ajax quando a aba configurações >> empresasas é chamado. *
        $.ajax({
			beforeSend: function() {console.log('ajax chamado')},
			type : "POST",
            //async: false,
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'Empresas',
                funcao              : 'teste',
			}
		}).done(function(retorno){
			//var json = jQuery.parseJSON(retorno);
			//alert(json);
            console.log('retorno ajax fim'+'='+retorno);
		});
        * fim do teste para ajax */
	});

	function montaActionsButtons(){
		// AO CLICAR O ITEM EMPRESA
		$('.empresaItem').click(function(){
			IDEmpresa = $(this).attr('data-id');
			$('#modal_cadastro_empresa input[name=IDEmpresa]').val(IDEmpresa);
			$('#modal_cadastro_empresa').modal('show');
		});
		$('.bt_adicionar_empresa').click(function(){
			$('#modal_cadastro_empresa input[name=IDEmpresa]').val('');
			$('#modal_cadastro_empresa').modal('show');
		});
		
		

		if ($('.set_IMLogoEmpresa').length > 0) {
			if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
				$(document).on('click', '.set_IMLogoEmpresa', function(e) {
					e.preventDefault();
					var button = $(this);
					//var id = button.prev();
					wp.media.editor.send.attachment = function(props, attachment) {
						//id.val(attachment.id);
						//console.log(attachment);
						$('.logo_IMLogoEmpresa > img').attr('src',attachment.sizes.full.url);
						$('#IMLogoEmpresa').val(attachment.id);
						//$('body').addClass('modal-open');
					};
					wp.media.editor.open();
					return false;
				});
				
			}
			return false;
		}

	}

});