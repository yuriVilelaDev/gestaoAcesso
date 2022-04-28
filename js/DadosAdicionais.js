jQuery(function($) {
	
	$(document).ready(function(){
		// MONTA ACOES DOS BOTES EM LOCAIS
		montaActionsButtons();
		//console.log('montato dados adicionais.js');
	});
	
	function actionBotaoEditar($id_select){
		if(! $id_select){
			$('.selectOpcoes .bt_editar').on('click',function(){
				if( $(this).parents('.selectOpcoes').hasClass('edicao') ){
					if( $(this).parents('.selectOpcoes').hasClass('modificado') ){
						salvaSelect($(this).parents('.selectOpcoes').attr('id'));
					}
					else
						$(this).parents('.selectOpcoes').removeClass('edicao');
				}
				else
					$(this).parents('.selectOpcoes').addClass('edicao');
			});
		}; 
	}

	/** MONTA ACOES DOS BOTÕES
	 * 
	 */
	function montaActionsButtons($id_select = null){
		
		// BOTAO EDITAR METADADO
		actionBotaoEditar($id_select);
		
		// AO CLICAR NO INPUT DE METATADO
		if($id_select)$alvo = '#'+$id_select;else $alvo = '.selectOpcoes';
		$($alvo + ' .opcao').on('click',function(){
			if( $(this).parents('.selectOpcoes').hasClass('edicao') && $('input',this).prop( "disabled" )  ){
				$('input',this).prop( "disabled", false );
				$('input',this).focus();
			}
		});
		// AO APERTAR ENTER DENTRO DO INPUT
		if($id_select)$alvo = '#'+$id_select;else $alvo = '.selectOpcoes';
		$($alvo + ' .opcao input').enterKey(function(){
			$(this).off('focus');
			$(this).prop( "disabled", true );
		});
		// QUANDO SAIR DO FOCUS DO INPUT
		if($id_select)$alvo = '#'+$id_select;else $alvo = '.selectOpcoes';
		$($alvo + ' .opcao input').focusout(function(){
			if( $(this).attr('data-oldValue') != $(this).val() ){
				$(this).prop( "disabled", false );
				$(this).parents('.selectOpcoes').find('.bt_editar span').removeClass('glyphicon-edit');
				$(this).parents('.selectOpcoes').find('.bt_editar span').addClass('glyphicon-floppy-disk');
				$(this).parents('.selectOpcoes').addClass('modificado');
			}else{
				$(this).prop( "disabled", true );
			}
		});
		// BOTAO DE ADICIONAR OPCAO AO SELECT
		if($id_select)$alvo = '#'+$id_select;else $alvo = '.selectOpcoes';
		$($alvo + ' .add').on('click',function(){
			if( $(this).parents('.selectOpcoes').hasClass('edicao') ){
				$select_id = $(this).parents('.selectOpcoes').attr('id');
				console.log( $('.sem_acao','#'+$select_id).length  );
				if( !$('.sem_acao','#'+$select_id).length ){
					//console.log('existe');
					$html = '<li class="opcao new sem_acao"><input type=text value="" name="" data-oldValue="" /></li> ';
					$( $html ).insertBefore( $(this).parent() );
					
					$('.sem_acao input','#'+$select_id).enterKey(function(){
						$(this).blur();
						$(this).off('focus');
					});

					$('.sem_acao input','#'+$select_id).focus();
					$('.sem_acao input','#'+$select_id).focusout(function(){
						if( $(this).attr('data-oldValue') == $(this).val() ){
							$(this).unbind();
							$(this).parent().hide('fast');
							$(this).parent().remove();
						}
						else{
							$('.sem_acao','#'+$select_id).removeClass('sem_acao');
							$('#'+$select_id).find('.bt_editar span').removeClass('glyphicon-edit');
							$('#'+$select_id).find('.bt_editar span').addClass('glyphicon-floppy-disk');
							$('#'+$select_id).addClass('modificado');
						}
					});
				}
			}
			return false;
		});

		// BOTAO DE APAGAR UMA OPCAO DO SELECT
		if($id_select)$alvo = '#'+$id_select;else $alvo = '.selectOpcoes';
		$($alvo + ' .excluir').on('click',function(){
			$(this).parent().find('input').val('');
			$(this).parent().addClass('apagar');
			$(this).parent().hide('fast');
			$(this).parents('.selectOpcoes').find('.bt_editar span').removeClass('glyphicon-edit');
			$(this).parents('.selectOpcoes').find('.bt_editar span').addClass('glyphicon-floppy-disk');
			$(this).parents('.selectOpcoes').addClass('modificado');
			return false;
		});
	}

	/** SALVA SELECT */
	function salvaSelect($id){
		$precisa_salvar = [];
		$('#'+$id).find('input').each(function(){
			if( $(this).attr('data-oldValue') != $(this).val() ){
				
				// testando se é um novo elemento adicionado
				input_id = 0;
				if(  !$(this).parent().hasClass('new')  )
					input_id = $(this).attr('data-id');
				
				// teste se é para apagar
				control = 0;
				if(  $(this).parent().hasClass('apagar')  )
					control = 'apagar';
					
				input = {
					'id' 		: input_id,
					'value' 	:  $(this).val(),
					'control' 	: control
				};
				$precisa_salvar.push(input);
			}
		});
		if($precisa_salvar.length){
			//console.log('precisa salvar'+ $id)
			$.ajax({
				beforeSend: function() {$('#'+$id +' .atualizando').show()},
				type : "POST",
				url: $.fn.getUrlAjax(),
				data : {
					action 				: "Handler_SA",
					class				: "DadosAdicionais",
					funcao  			: 'salvaSelect',
					metaKey				: $id,
					dado				: $precisa_salvar
				}
			}).done(function(retorno){
				var json = jQuery.parseJSON(retorno);
				if(!json['status']){
					if(json['erro']){
						alert(json['erro']);
					}	
				}
				if(json['status']){
					$('#'+$id+' .content').html(json['html']);
					montaActionsButtons($id);
					$('#'+$id+' .bt_editar span').removeClass('glyphicon-floppy-disk');
					$('#'+$id+' .bt_editar span').addClass('glyphicon-edit');
					$('#'+$id).removeClass('modificado');
					$('#'+$id).removeClass('edicao');
					$('#'+$id +' .atualizando').hide();
				}
			});
			//fin ajax de salvar
		}
		else{
			$('#'+$id+' .bt_editar span').removeClass('glyphicon-floppy-disk');
			$('#'+$id+' .bt_editar span').addClass('glyphicon-edit');
			$('#'+$id).removeClass('modificado');
			$('#'+$id).removeClass('edicao');
		}
	}

});