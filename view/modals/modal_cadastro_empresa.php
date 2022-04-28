<script language="javascript">
jQuery(function($) {
	
	function montaActionsButtons(modal){
        //modal = '#'+modal;
		
        // ACAO BOTAO SALVAR EMPRESA
        $('.bt_salvar',modal).unbind( "click" );
        $('.bt_salvar',modal).click(function(){
            salvaEmpresa(modal);
            return false;
        });

        // ABRIR MODAL DE CONTRATO
		$('.contrato').unbind('click');
        $('.contrato').click(function(){
			$IDContratoEmpresa = $(this).attr('data-id');
            $('#modal_cadastro_empresa_contrato').find('input[name=IDContratoEmpresa]').val($IDContratoEmpresa);
			$('#modal_cadastro_empresa_contrato').modal('show');
		});
        $('.contratos a.adicionar').unbind('click');
		$('.contratos a.adicionar').click(function(){
            $('#modal_cadastro_empresa_contrato').find('input[name=IDContratoEmpresa]').val('');
            IDEmpresa = $(modal).find('#IDEmpresa').val();
            if(!IDEmpresa){
                salvaEmpresa(modal,'#modal_cadastro_empresa_contrato');
            }else{
                $('#modal_cadastro_empresa_contrato').modal('show');
            }
		});
        // ABRIR MODAL DE ENDEREÇO
        $('.endereco').unbind('click');
        $('.endereco').click(function(){
			$IDEnderecoEmpresa = $(this).attr('data-id');
            $('#modal_cadastro_empresa_endereco').find('input[name=IDEnderecoEmpresa]').val($IDEnderecoEmpresa);
			$('#modal_cadastro_empresa_endereco').modal('show');
		});
		$('.enderecos a.adicionar').unbind('click');
		$('.enderecos a.adicionar').click(function(){
            $('#modal_cadastro_empresa_endereco').find('input[name=IDEnderecoEmpresa]').val('');
            IDEmpresa = $(modal).find('#IDEmpresa').val();
            if(!IDEmpresa){
                salvaEmpresa(modal,'#modal_cadastro_empresa_endereco');
            }else{
                $('#modal_cadastro_empresa_endereco').modal('show');
            }
		});
		// ABRIR MODAL DE CONTATO
        $('.contatos .content .contato').unbind('click');
        $('.contatos .content .contato').click(function(){
			$IDContatoEmpresa = $(this).attr('data-id');
            $('#modal_cadastro_empresa_contato').find('input[name=IDContatoEmpresa]').val($IDContatoEmpresa);
			$('#modal_cadastro_empresa_contato').modal('show');
		});
		$('.contatos a.adicionar').unbind('click');
		$('.contatos a.adicionar').click(function(){
            $('#modal_cadastro_empresa_contato').find('input[name=IDContatoEmpresa]').val('');
            IDEmpresa = $(modal).find('#IDEmpresa').val();
            if(!IDEmpresa){
                salvaEmpresa(modal,'#modal_cadastro_empresa_contato');
            }else{
                $('#modal_cadastro_empresa_contato').modal('show');
            }
		});


	}
	
	//quando o modal for chamado
	$('#modal_cadastro_empresa').on('show.bs.modal', function () {
        $(this).find('.has-error').each(function(index){
            $(this).removeClass('has-error');
            $('.mensagem_erro',this).hide();
        });
        $('.bt_salvar i',this).hide();
        IDEmpresa = $(this).find('#IDEmpresa').val();
        if( IDEmpresa ){
            //Edicao de cadastro de empresa
            NMRazaoEmpresa =    $('.NMRazaoEmpresa > h3','#IDEempresa_'+IDEmpresa).html();
            NUCnpjEmpresa =     $('.NUCnpjEmpresa','#IDEempresa_'+IDEmpresa).html();
            NMFantasiaEmpresa = $('.NMFantasiaEmpresa','#IDEempresa_'+IDEmpresa).html();
            NMWebsiteEmpresa =  $('.NMWebsiteEmpresa','#IDEempresa_'+IDEmpresa).html();
            logoEndereco =      $('.thumb img','#IDEempresa_'+IDEmpresa).attr('src');
            logoID =            $('.thumb img','#IDEempresa_'+IDEmpresa).attr('data-id');  
            STEmpresa =         $('.opcoes .status','#IDEempresa_'+IDEmpresa).attr('data-status');
            $('input:radio[name=STEmpresa]',this).filter('[value='+STEmpresa+']').attr('checked', true);
            conteudoEmpresaModalHTML('#' + $(this).attr('id'));
        }else{
            //Novo cadastro de Empresa
            NMRazaoEmpresa      = '';
            NUCnpjEmpresa       = '';
            NMFantasiaEmpresa   = '';
            NMWebsiteEmpresa    = '';
            logoEndereco        = '';
            logoID              = '';
            STEmpresa           = 0;
            $('input:radio[name=STEmpresa]',this).attr('checked', false);
            $('.contratos .content',this).html('');
            $('.enderecos .content',this).html('');
            $('.contatos .content',this).html('');
        }
        $('input[name=NMRazaoEmpresa]').val(NMRazaoEmpresa);
        $('input[name=NUCnpjEmpresa]').val(NUCnpjEmpresa);
        $('input[name=NMFantasiaEmpresa]').val(NMFantasiaEmpresa);
        $('input[name=NMWebsiteEmpresa]').val(NMWebsiteEmpresa);
        $('.logo_IMLogoEmpresa > img',this).attr('src',logoEndereco);
		$('#IMLogoEmpresa',this).val(logoID);
	});

	// quando o modal acabar de abrir
	$('#modal_cadastro_empresa').on('shown.bs.modal', function () {
        //montaActionsButtons($(this).attr('id'));
        IDEmpresa = $(this).find('#IDEmpresa').val();
        if( IDEmpresa ){
        }else{
            $('input[name=NMRazaoEmpresa]').focus();
            montaActionsButtons( '#' + $(this).attr('id') );
        }
	});

    /** SALVA EMPRESAS AJAX 
	 * Levanta a lista de todos Empresas cadastradas
	 * @type	function
	 * @date	22/07/21
	 * @since	1.0.0
	 * 
	 * @param	modal_id
	 * @return	BOOLEAN
	 */
    function salvaEmpresa(modal,alvo = null){
        IDEmpresa = $(modal).find('#IDEmpresa').val();
        
        if(IDEmpresa){
        //    console.log('tem id');
        }else{
        //    console.log('Não tem id'); 
        }
        
        form_id = 'form_cadastro_empresa';
        $erro = 0;
        $('#'+form_id+' .require').each(function(){
            if($(this).hasClass('input-text'))
                $campo = $('input[type=text]',this).val();
            if($(this).hasClass('input-radio'))
                $campo = $('input[type=radio]:checked',this).val();
            if(!$campo) {
                $(this).addClass('has-error');
                $('.mensagem_erro',this).show();
                $erro++;
            }
            else{
                $(this).removeClass('has-error');
                $('.mensagem_erro',this).hide();
            }
        });
        if($erro)return false;

        // inicia o salvamento
        $.ajax({
			beforeSend: function() {$(modal).addClass('processando');},
			type : "POST",
            //async: false,
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'Empresas',
                funcao              : 'salvaEmpresa',
                IDEmpresa           : IDEmpresa,
                NMRazaoEmpresa      : $('input[name=NMRazaoEmpresa]').val(),
                NUCnpjEmpresa       : $('input[name=NUCnpjEmpresa]').val(),
                NMFantasiaEmpresa   : $('input[name=NMFantasiaEmpresa]').val(),
                NMWebsiteEmpresa    : $('input[name=NMWebsiteEmpresa]').val(),
                IMLogoEmpresa       : $('#IMLogoEmpresa',modal).val(),
                STEmpresa           : $('input[name=STEmpresa]:checked',modal).val()
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
                if(alvo){
                    $(alvo).modal('show');
                }
			}
            
		});
        return true;
    }

    function conteudoEmpresaModalHTML(modal){
        //console.log('abrindo conteudo');
        IDEmpresa = $(modal).find('#IDEmpresa').val();
        $.ajax({
			beforeSend: function() {$(modal).addClass('processando');},
			type : "POST",
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'Empresas',
                funcao              : 'getConteudoEmpresaModalHTML',
                IDEmpresa           : IDEmpresa
			}
		}).done(function(retorno){
			var json = jQuery.parseJSON(retorno);
			//alert(json);
			if(!json['status']){if(json['erro']){alert(json['erro']);}
				$(modal).removeClass('processando');
			}
			if(json['status']){
                if(json['contratos']){$(modal).find('.contratos .content').html(json['contratos'])}
                if(json['enderecos']){$(modal).find('.enderecos .content').html(json['enderecos'])}
                if(json['contatos']){$(modal).find('.contatos .content').html(json['contatos'])}
                $(modal).removeClass('processando');
			}
            montaActionsButtons(modal);
		});
    }

});
</script>

<style>
    #modal_cadastro_empresa .logo_IMLogoEmpresa img{width: 86px;border-radius: 50px;border: 4px solid #cfcfcf;margin: 0 0 8px 0;}
</style>

<div class="modal fade" id="modal_cadastro_empresa" tabindex="-1" role="dialog" aria-labelledby="ModalCadastroEmpresa">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
    
            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro Empresa</h4>
            </div>
            <div class="modal-body">
                <form id="form_cadastro_empresa" class="">
                    <input type="hidden" value="" name="IDEmpresa" id="IDEmpresa"/>
                    <div class="row">
                        
                        <div class="col-md-6">
                            
                            <div class="form-group require input-text">
                                <label for="NMRazaoEmpresa">Razão social</label>
                                <input type="text" class="form-control" name="NMRazaoEmpresa" placeholder="NMRazaoEmpresa">
                                <label class="control-label mensagem_erro" for="NMRazaoEmpresa" style="display:none">Preencha o campo!</label> 
                            </div>
                        
                            <div class="form-group require input-text">
                                <label for="NUCnpjEmpresa">N CNPJ</label>
                                <input type="text" class="form-control" name="NUCnpjEmpresa" placeholder="NUCnpjEmpresa">
                                <label class="control-label mensagem_erro" for="NUCnpjEmpresa" style="display:none">Preencha o CNPJ!</label> 
                            </div>

                            <div class="form-group">
                                <div><label for="IMLogoEmpresa">Logo empresa</label></div>
                                <div class="logo_IMLogoEmpresa">
                                    <img src=""/>
                                </div>
                                <div>
                                    <input type="hidden" value="" id="IMLogoEmpresa" name="" max="" min="1" step="1">
                                    <button class="set_IMLogoEmpresa button">Alterar</button>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="col-md-6">
                            
                            <div class="form-group require input-text">
                                <label for="NMFantasiaEmpresa">Nome fantasia</label>
                                <input type="text" class="form-control" name="NMFantasiaEmpresa" placeholder="NMFantasiaEmpresa">
                                <label class="control-label mensagem_erro" for="NMFantasiaEmpresa" style="display:none">Preencha o campo!</label> 
                            </div>

                            <div class="form-group require input-text">
                                <label for="NMWebsiteEmpresa">Web site</label>
                                <input type="text" class="form-control" name="NMWebsiteEmpresa" placeholder="NMWebsiteEmpresa">
                                <label class="control-label mensagem_erro" for="NMWebsiteEmpresa" style="display:none">Preencha o campo!</label> 
                            </div>

                            <div class="form-group require input-radio">
                                <div>
                                    <label for="STEmpresa" control-label">Situação</label>                                
                                </div>
                                <div>
                                    <label class="radio-inline"><input type="radio" name="STEmpresa" value="1"> Ativo</label>
                                    <label class="radio-inline"><input type="radio" name="STEmpresa" value="0"> Desativado</label>
                                </div>
                                <label class="control-label mensagem_erro" for="STEmpresa" style="display:none">Selecione uma opção!</label>
                            </div>

                        </div>
                    
                    </div> 
		
                </form>
				
                <div class="fundocaixa">
                    
                    <div class="contratos">
                        <div class="actions">
                            <h4>Contratos da empresa:</h4>
                            <a href="javascript:void(0);" class="adicionar btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                Contrato
                            </a>
                        </div>
                        <div class="content">

                        </div>

                    </div>

                    <div class="enderecos">
                        <div class="actions">
                            <h4>Endereços:</h4>
                            <a href="javascript:void(0);" class="adicionar btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                Endereço
                            </a>
                        </div>
                        
                        <div class="content">
                            
                        </div>
                    </div>

                    <div class="contatos">
                        <div class="actions">
                            <h4>Contatos:</h4>
                            <a href="javascript:void(0);" class="adicionar btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                Contato
                            </a>
                        </div>
                        
                        <div class="content">
                            
                        </div> 

                    </div>

                </div>
			
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary bt_salvar"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>Salvar</button>
            </div>
        </div>
    </div>
</div>

