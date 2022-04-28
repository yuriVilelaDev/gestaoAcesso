jQuery(function($) {
	
	$(document).ready(function(){
        montaActionsButtons();
	});

	function montaActionsButtons(){
		// AO MUDAR O SELECT DE EMPRESA.
        $( "#IDEmpresa" ).change(function() {
            $(this).parents('form').submit();
        });
		$('.bt-adicionar-peril_SIGSA').click(function(){
			$('#modal_cadastro_perfil_SIGSA input[name=IDPerfil]').val('');
			$('#modal_cadastro_perfil_SIGSA').modal('show');
		});
		$('.editarNomePerfilSGSA').click(function(){
			$(this).parents('.perfil').find('.panel-heading .panel-title').hide();
			$(this).parents('.perfil').find('.panel-heading input[name=DSPerfilAlterado]').show('fast');
		});
		$( '.panel-heading input[name=DSPerfilAlterado]').focusout(function() {
			if( $(this).val() != $(this).parent().find('h4 > a').text() ){
				$(this).parent().find('.opcoes .salvar').show('fast');
			}else $(this).parent().find('.opcoes .salvar').hide('fast');
		});
		$( '#permissoes .grupos input' ).change(function(){
			$perNew = false;
			$perCount = 0;
			$lista = jQuery.parseJSON( $(this).parents('.panel-body').find('input[name=permissoes]').val() );
			//console.log( $(this).parents('.panel-body').find('input[name=permissoes]').val() );
			$(this).parents('.panel-body').find('input:checkbox:checked').each(function(){
				$perCount++;
				if( jQuery.inArray( parseInt( $(this).val() ) , $lista ) < 0 ) $perNew = true;
			});
			if( $perCount < $lista.length || $perNew) $(this).parents('.perfil').find('.opcoes .salvar').show('fast');
			else $(this).parents('.perfil').find('.opcoes .salvar').hide('fast');
		});

		$( '#permissoes .perfil .opcoes .salvar' ).click(function(){
			$botao = $(this);
			$IDPerfil = $(this).attr('data-id');
			$DSPerfilAlterado = null;
			if( $(this).parents('.panel-heading').find('input[name=DSPerfilAlterado]').is(':visible') ){
				$DSPerfilAlterado = $(this).parents('.panel-heading').find('input[name=DSPerfilAlterado]').val();
			}
			$arrPermissoes = [];
			if($IDPerfil){
				$(this).parents('.perfil').find('input:checkbox:checked').each(function(){
					$arrPermissoes.push($(this).val());
				});
				$.ajax({
				beforeSend: function() {/*$('#'+$id +' .atualizando').show()*/},
					type : "POST",
					url: $.fn.getUrlAjax(),
					data : {
						action 				: "Handler_SA",
						class				: "VerificacaoAcesso",
						funcao  			: 'setPerfil',
						IDPerfil			: $IDPerfil,
						DSPerfilAlterado	: $DSPerfilAlterado,
						arrPermissoes		: $arrPermissoes
					}
				}).done(function(retorno){
					var json = jQuery.parseJSON(retorno);
					if(!json['status']){
						if(json['erro']){
							alert(json['erro']);
						}	
					}
					if(json['status']){
						$('input[name=permissoes]','#permissoes #perfil_'+$IDPerfil).val('['+$arrPermissoes+']');
						if($DSPerfilAlterado){
							$botao.parents('.panel-heading').find('input[name=DSPerfilAlterado]').hide();
							$botao.parents('.panel-heading').find('.panel-title').text($DSPerfilAlterado).show('fast');
						}
						$botao.hide('fast');
					}
				});
				//fin ajax de salvar
			}
		});
	}
});