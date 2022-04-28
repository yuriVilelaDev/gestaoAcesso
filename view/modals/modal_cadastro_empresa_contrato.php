<script language="javascript">
jQuery(function($) {
	    // IDContratoEmpresa
        // CDTipoContradoEmprea concessao-de-uso aditivo-contratual
        // CDContratoEmpresa
        // DSContratoEmpresa
        // DTInicioContratoEmpresa
        // DTTerminioContratoEmpresa
        // CDSituacaoContratoEmpresa
        // NUAditivoContratoEmpresa
        // IDEmpresa
	function montaActionsButtons(){
        modal = '#modal_cadastro_empresa_contrato';
		$('.bt_salvar',modal).unbind( "click" );
        $('.bt_salvar',modal).click(function(){
            salvaContratoEmpresa(modal);
            return false;
        });
        $(modal).find('select[name=CDTipoContratoEmpresa]').unbind( "change" );
        $(modal).find('select[name=CDTipoContratoEmpresa]').change(function(){
            CDTipoContratoEmpresa = $(this).val()
            dataClass = $('option[value='+CDTipoContratoEmpresa+']',this).attr('data-class');
            if(dataClass == 'aditivo-contratual'){
                $(modal).find('input[name=NUAditivoContratoEmpresa]').parent().show('fast');
            }
            else{
                $(modal).find('input[name=NUAditivoContratoEmpresa]').parent().hide('fast');
                $(modal).find('input[name=NUAditivoContratoEmpresa]').val('');
            }
        });
        $('.bt_excluir',modal).unbind( "click" );
        $('.bt_excluir',modal).click(function(){
            if(confirm( 'Deseja realmente apagar este contrato?' )){	
                excluirContratoEmpresa(modal);
            }
            return false;
        });

	}
	
	
	//quando o modal for chamado
	$('#modal_cadastro_empresa_contrato').on('show.bs.modal', function () {
        $IDEmpresa= $('#modal_cadastro_empresa').find('input[name=IDEmpresa]').val();
        $(this).find('input[name=IDEmpresa]').val($IDEmpresa);
        $IDContratoEmpresa = $(this).find('input[name=IDContratoEmpresa]').val()
        if($IDContratoEmpresa){
            $CDTipoContratoEmpresa = $('#modal_cadastro_empresa #contrato_'+$IDContratoEmpresa).attr('data-tipo');
            $(this).find('select[name=CDTipoContratoEmpresa]').val($CDTipoContratoEmpresa).change();
            dataClass = $(this).find('select[name=CDTipoContratoEmpresa] option[value='+$CDTipoContratoEmpresa+']').attr('data-class');
            if(dataClass != 'aditivo-contratual')$(this).find('input[name=NUAditivoContratoEmpresa]').parent().hide();
            $CDContratoEmpresa =$('#modal_cadastro_empresa #contrato_'+$IDContratoEmpresa).find('.CDContratoEmpresa').html();
            $(this).find('input[name=CDContratoEmpresa]').val($CDContratoEmpresa);
            $DSContratoEmpresa = $('#modal_cadastro_empresa #contrato_'+$IDContratoEmpresa).find('.titulo').html();
            $(this).find('textarea[name=DSContratoEmpresa]').val($DSContratoEmpresa);
            $DTInicioContratoEmpresa = $('#modal_cadastro_empresa #contrato_'+$IDContratoEmpresa).find('.DTInicioContratoEmpresa').html();
            $(this).find('input[name=DTInicioContratoEmpresa]').val($DTInicioContratoEmpresa);
            $DTTerminoContratoEmpresa = $('#modal_cadastro_empresa #contrato_'+$IDContratoEmpresa).find('.DTTerminoContratoEmpresa').html();
            $(this).find('input[name=DTTerminoContratoEmpresa]').val($DTTerminoContratoEmpresa);
            $CDSituacaoContratoEmpresa = $('#modal_cadastro_empresa #contrato_'+$IDContratoEmpresa).attr('data-situacao');
            $(this).find('select[name=CDSituacaoContratoEmpresa]').val($CDSituacaoContratoEmpresa).change();
            $NUAditivoContratoEmpresa = $('#modal_cadastro_empresa #contrato_'+$IDContratoEmpresa).find('input[name=NUAditivoContratoEmpresa]').val();
            $(this).find('input[name=NUAditivoContratoEmpresa]').val($NUAditivoContratoEmpresa);
            $(this).find('.bt_excluir').show();
        }else{
            $(this).find('select[name=CDTipoContratoEmpresa]').val('').change();
            $(this).find('input[name=CDContratoEmpresa]').val('');
            $(this).find('textarea[name=DSContratoEmpresa]').val('');
            $(this).find('input[name=DTInicioContratoEmpresa]').val('');
            $(this).find('input[name=DTTerminoContratoEmpresa]').val('');
            $(this).find('select[name=CDSituacaoContratoEmpresa]').val('').change();
            $(this).find('input[name=NUAditivoContratoEmpresa]').val('');
            $(this).find('.bt_excluir').hide();
        }
	});

	// quando o modal acabar de abrir
	$('#modal_cadastro_empresa_contrato').on('shown.bs.modal', function () {
        montaActionsButtons();
	});
    // quando o modal for fechado abilitar o modal cadastro empresa pai
    $('#modal_cadastro_empresa_contrato').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
	});

    /** SALVA CONTRATO AJAX 
	 * Levanta a lista de todos Empresas cadastradas
	 * @type	function
	 * @date	28/07/21
	 * @since	1.0.0
	 * 
	 * @param	null
	 * @return	BOOLEAN
	 */
    function salvaContratoEmpresa(){
        modal = '#modal_cadastro_empresa_contrato';
        IDEmpresa = $(modal).find('input[name=IDEmpresa]').val();
        IDContratoEmpresa = $(modal).find('input[name=IDContratoEmpresa]').val();

        validaForm = $.fn.validaForm('#form_cadastro_empresa_contrato');
        if(!validaForm)return false;

        // inicia o salvamento
        $.ajax({
			beforeSend: function() {$(modal).addClass('processando');},
			type : "POST",
            //async: false,
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'Empresas',
                funcao              : 'salvaContratoEmpresa',
                IDEmpresa           : IDEmpresa,
                IDContratoEmpresa   : IDContratoEmpresa,
                CDTipoContratoEmpresa   : $(modal).find('select[name=CDTipoContratoEmpresa]').val(),
                CDContratoEmpresa       : $(modal).find('input[name=CDContratoEmpresa]').val(),
                DSContratoEmpresa       : $(modal).find('textarea[name=DSContratoEmpresa]').val(),
                DTInicioContratoEmpresa : $(modal).find('input[name=DTInicioContratoEmpresa]').val(),
                DTTerminoContratoEmpresa    : $(modal).find('input[name=DTTerminoContratoEmpresa]').val(),
                CDSituacaoContratoEmpresa   : $(modal).find('select[name=CDSituacaoContratoEmpresa]').val(),
                NUAditivoContratoEmpresa    : $(modal).find('input[name=NUAditivoContratoEmpresa]').val()
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
                //salvamento deu certo => fechar modal contrato e setar o contrato no modal de empresa
                if(IDContratoEmpresa){
                    // no caso de alteração de contrato
                    div_contrato = '#modal_cadastro_empresa #contrato_'+$IDContratoEmpresa;
                    CDTipoContratoEmpresa = $(modal).find('select[name=CDTipoContratoEmpresa]').val();
                    dataClass = $(modal).find('select[name=CDTipoContratoEmpresa] option[value='+CDTipoContratoEmpresa+']').attr('data-class');
                    $(div_contrato).attr('data-tipo',CDTipoContratoEmpresa);
                    $(div_contrato).attr('class','contrato caixa '+dataClass);
                    $(div_contrato).find('.CDContratoEmpresa').html($(modal).find('input[name=CDContratoEmpresa]').val());
                    $(div_contrato).find('.titulo').html($(modal).find('textarea[name=DSContratoEmpresa]').val());
                    $(div_contrato).find('.DTInicioContratoEmpresa').html($(modal).find('input[name=DTInicioContratoEmpresa]').val());
                    $(div_contrato).find('.DTTerminoContratoEmpresa').html($(modal).find('input[name=DTTerminoContratoEmpresa]').val());
                    $(div_contrato).attr('data-situacao',$(modal).find('select[name=CDSituacaoContratoEmpresa]').val());
                    $(modal).modal('hide');
                    $('#modal_cadastro_empresa').modal('show')
                }else{
                    $(modal).modal('hide');
                    $('#modal_cadastro_empresa').modal('show')
                }
            }
		});
        return true;
    }
    
    /** EXCLUIR CONTRATO AJAX 
	 * excluir o determinado contrato
	 * @type	function
	 * @date	28/07/21
	 * @since	1.0.0
	 * 
	 * @param	null
	 * @return	BOOLEAN
	 */
    function excluirContratoEmpresa(){
        modal = '#modal_cadastro_empresa_contrato';
        IDEmpresa = $(modal).find('input[name=IDEmpresa]').val();
        IDContratoEmpresa = $(modal).find('input[name=IDContratoEmpresa]').val();
        // inicia a exclusao
        $.ajax({
			beforeSend: function() {$(modal).addClass('processando');},
			type : "POST",
            //async: false,
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'Empresas',
                funcao              : 'excluirContratoEmpresa',
                IDEmpresa           : IDEmpresa,
                IDContratoEmpresa   : IDContratoEmpresa
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
                //Exclusao no banco deu certo => fechar modal contrato e setar o contrato no modal de empresa
                if(IDContratoEmpresa){
                    // no caso de alteração de contrato
                    //div_contrato = '#modal_cadastro_empresa #contrato_'+$IDContratoEmpresa;
                    $(modal).modal('hide');
                    $('#modal_cadastro_empresa').modal('show')
                }else{
                    $(modal).modal('hide');
                    $('#modal_cadastro_empresa').modal('show')
                }
            }
		});
        return true;
    }

});
</script>

<div class="modal fade" id="modal_cadastro_empresa_contrato" tabindex="-1" role="dialog" aria-labelledby="ModalCadastroEmpresaContrato">
    
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            
            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Dados do Contrato</h4>
            </div>
            <div class="modal-body">
                <form id="form_cadastro_empresa_contrato" class="">
                    <input type="hidden" name="IDEmpresa" val=""/>
                    <input type="hidden" name="IDContratoEmpresa" val=""/>
                    <?php
                        $lista_CDTipoContratoEmpresa = $Empresas->getGERMetadadoOpcoes('CDTipoContratoEmpresa');
                        //Empresas::printa($lista_CDTipoContratoEmpresa);
                        $lista_CDSituacaoContratoEmpresa = $Empresas->getGERMetadadoOpcoes('CDSituacaoContratoEmpresa');
                    ?>

                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group require select">
                                <label for="CDTipoContratoEmpresa" class="control-label">Tipo do contrato</label>
                                <select class="form-control" name="CDTipoContratoEmpresa">
                                    <?php
                                    foreach($lista_CDTipoContratoEmpresa as $item){
                                        $data_class = $item->DSOpcaoMetadado;
				                        $data_class = preg_replace(array("/(A|ã)/","/(E)/","/(I)/","/(O)/","/(U)/","/(C)/","/ /"),explode(" ","a e i o u c -"),$data_class);
                                        echo '<option data-class="'.$data_class.'" value="'. $item->IDMetadado .'">'. $item->DSOpcaoMetadado .'</option>';
                                    }
                                    ?>
                                </select>
                                <label class="control-label mensagem_erro" for="CDTipoContratoEmpresa" style="display:none">Selecione uma opção!</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group input-text">
                                <label for="NUAditivoContratoEmpresa" class="control-label">Código do Aditivo Contratual</label>
                                <input type="text" class="form-control" name="NUAditivoContratoEmpresa" value="">
                                <label class="control-label mensagem_erro" for="NUAditivoContratoEmpresa" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                    
                    </div>
                    
                    <div class="row">
                    
                        <div class="col-md-4">
                            <div class="form-group require input-text">
                                <label for="CDContratoEmpresa" class="control-label">Código do contrato</label>
                                <input type="text" class="form-control" name="CDContratoEmpresa" value="">
                                <label class="control-label mensagem_erro" for="CDContratoEmpresa" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                    
                    </div>
                   
                    <div class="row">
                    
                        <div class="col-md-6">
							<div class="form-group require input-text">
								<label for="DTInicioContratoEmpresa" class="control-label">Data de início do contrato</label>
								<input type="text" class="form-control datepicker" name="DTInicioContratoEmpresa" placeholder="xx/xx/xxxx" value="">
								<label class="control-label mensagem_erro" for="DTInicioContratoEmpresa" style="display:none">Preencha o campo!</label> 
							</div>
						</div>

                        <div class="col-md-6">
							<div class="form-group require input-text">
								<label for="DTTerminoContratoEmpresa" class="control-label">Data de término do contrato</label>
								<input type="text" class="form-control datepicker" name="DTTerminoContratoEmpresa" placeholder="xx/xx/xxxx" value="">
								<label class="control-label mensagem_erro" for="DTTerminoContratoEmpresa" style="display:none">Preencha o campo!</label> 
							</div>
						</div>

                    </div>
                    
                    <div class="row">

                        <div class="col-md-12">
							<div class="form-group require textarea">
								<label for="DSContratoEmpresa" class="control-label">Descrição do contrato</label>
								<textarea class="form-control" name="DSContratoEmpresa"></textarea>
								<label class="control-label mensagem_erro" for="DSContratoEmpresa" style="display:none">Preencha o campo!</label> 
							</div>
						</div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group require select">
                                <label for="CDSituacaoContratoEmpresa" class="control-label">Situação do contrato</label>
                                <select class="form-control" name="CDSituacaoContratoEmpresa">
                                    <?php
                                    foreach($lista_CDSituacaoContratoEmpresa as $item){
                                        echo '<option value="'. $item->IDMetadado .'">'. $item->DSOpcaoMetadado .'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                      
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger bt_excluir"><i class="fa fa-trash-o"></i>Excluir</button>
                <button type="button" class="btn btn-primary bt_salvar"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="display:none;"></i>Salvar</button>
            </div>
        </div>
    </div>
</div>