<script language="javascript">
jQuery(function($) {
    $page = null;
	
	function montaActionsButtons(modal){
        $page = $('input[name=page]').val();
        console.log($page);
		
        // ACAO BOTAO SALVAR PERFIL
        $('.bt_salvar',modal).unbind( "click" );
        $('.bt_salvar',modal).click(function(){
            salvarPerfilSIGSA(modal);
            return false;
        });

	}
	
	// MODAL CHAMADO
	$('#modal_cadastro_perfil_SIGSA').on('show.bs.modal', function () {
        modal = '#modal_cadastro_perfil_SIGSA';
        
        $(this).find('.has-error').each(function(index){
            $(this).removeClass('has-error');
            $('.mensagem_erro',this).hide();
        });
        $('.bt_salvar i',modal).hide();

        IDPerfil = $(this).find('#IDPerfil').val();
        
        if( IDPerfil ){
            //Edicao de cadastro do cliente
        }else{
            //Novo cadastro de Empresa
        }
	});

	// MODAL ABERTO
	$('#modal_cadastro_perfil_SIGSA').on('shown.bs.modal', function () {
        montaActionsButtons( '#' + $(this).attr('id') );
	});

    /** SALVA PERFIL SIGSA 
	 * Salva via ajax os dados de perfil selecionados
	 * @type	function
	 * @date	09/12/21
	 * @since	1.0.0
	 * 
	 * @param	modal_id
	 * @return	BOOLEAN
	 */
    function salvarPerfilSIGSA(modal,alvo = null){
        IDPerfil = $(modal).find('#IDPerfil').val();
        validaForm = $.fn.validaForm('#form_cadastro_perfil_SIGSA');
        if(!validaForm)return false;

        // inicia o salvamento
        $.ajax({
			beforeSend: function() {$(modal).addClass('processando');},
			type : "POST",
            //async: false,
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'VerificacaoAcesso',
                funcao              : 'setNewPerfil',
                IDPerfil            : $(modal).find('#IDPerfil').val(),
                DSPerfil            : $(modal).find('input[name=DSPerfil]').val(),
                NUNivel             : $(modal).find('select[name=NUNivel]').val(),
			}
		}).done(function(retorno){
			var json = jQuery.parseJSON(retorno);
			//alert(json);
			if(!json['status']){
				if(json['erro']){alert(json['erro']);}
				$(modal).removeClass('processando');
			}
			if(json['status']){
                $(modal).removeClass('processando');
                //salvamento deu certo => fechar modal contrato e setar
                alert("Perfil inserido! por favor indicar as permissões de acesso desse perfil na tela anterior.");
                $(modal).modal('hide');
                window.location = '?page='+$page;
            }
		});

        return true;
    }
});
</script>

<style>
</style>

<div class="modal fade" id="modal_cadastro_perfil_SIGSA" tabindex="-1" role="dialog" aria-labelledby="ModalCadastroPerfilSIGSA">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro Perfil do usuário SIGSA</h4>
            </div>
            <div class="modal-body">
                <form id="form_cadastro_perfil_SIGSA" class="">
                    <input type="hidden" value="" name="IDEmpresa" id="IDPerfil"/>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group require input-text">
                                <label for="DSPerfil" class="control-label">Nome do Perfil</label>
                                <input type="text" class="form-control" name="DSPerfil" placeholder="DSPerfil">
                                <label class="control-label mensagem_erro" for="DSPerfil" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-5">
                        <div class="form-group require select">
                            <label for="CDTipoContratoEmpresa" class="control-label">Tipo do contrato</label>
                                <select class="form-control" name="NUNivel">
                                    <option value="">Selecione</option>
                                    <option value="1">Nível sistema</option>]
                                    <option value="2">Nível empresa</option>
                                    <option value="3">Nível cliente</option>
                                </select>
                                <label class="control-label mensagem_erro" for="NUNivel" style="display:none">Selecione o nível!</label>
                            </div>
                        </div>
                    </div>
		
                </form>
				<br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary bt_salvar"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>Salvar</button>
            </div>
        </div>
    </div>
</div>

