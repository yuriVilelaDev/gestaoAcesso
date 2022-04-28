<script language="javascript">
jQuery(function($) {
	
    function montaActionsButtons(){
        modal = '#modal_cadastro_empresa_endereco';
		
        $('.telefones .new .actions button',modal).click(function(){
            $Ntelefone = $(this).parents('.new').find('input[name=numero_telefone]').val();
            $tipoTelefone = $(this).parents('.new').find('select[name=tipo_telefone] :selected').val();
            console.log($Ntelefone.length);
            if( $Ntelefone.length >= 14 && $tipoTelefone){
                $telefone_html = $(this).parents('.new').clone().prop('class','telefone adicionado');
                $telefone_html.find('div.numero_telefone').html($Ntelefone);
                $telefone_html.find('div.tipo_telefone').html($tipoTelefone);
                $telefone_html.find('.actions button span').attr('class', "glyphicon glyphicon-trash" );
                $(this).parents('.new').before($telefone_html);
                $(this).parents('.new').find('input[name=numero_telefone]').val('');
                montaActionsButtons_telefones();
            }
        });

        $('.bt_salvar',modal).unbind( "click" );
        $('.bt_salvar',modal).click(function(){
            salvaEnderecoEmpresa(modal);
            return false;
        });
        $('.bt_excluir',modal).unbind( "click" );
        $('.bt_excluir',modal).click(function(){
            if(confirm( 'Deseja realmente apagar este endereco?' )){	
                excluirEnderecoEmpresa(modal);
            }
            return false;
        });
        $('.telefones .new input[name=numero_telefone]',modal).unbind('input');
        $('.telefones .new input[name=numero_telefone]',modal).bind('input',function(){
            $(this).val($.fn.formataTelefone($(this).val()));
		});

        montaActionsButtons_telefones();
	}

    function montaActionsButtons_telefones(){
        modal = '#modal_cadastro_empresa_endereco';
        $('.telefones .adicionado .actions button',modal).unbind( "click" );
        $('.telefones .adicionado .actions button',modal).click(function(){
            $(this).parents('.adicionado').remove();
        });
    }
	
    /** SALVA ENDERECO AJAX 
	 * Levanta a lista de todos Empresas cadastradas
	 * @type	function
	 * @date	28/07/21
	 * @since	1.0.0
	 * 
	 * @param	null
	 * @return	BOOLEAN
	 */
    function salvaEnderecoEmpresa(modal){
        modal = '#modal_cadastro_empresa_endereco';
        IDEmpresa = $(modal).find('input[name=IDEmpresa]').val();
        IDEnderecoEmpresa = $(modal).find('input[name=IDEnderecoEmpresa]').val();
        
        validaForm = $.fn.validaForm('#form_cadastro_empresa_endereco');
        if(!validaForm)return false;

        CDTipoEndEmpresa = [];
        $(modal).find('input:checkbox[name=CDTipoEndEmpresa]:checked').each(function(index){
            CDTipoEndEmpresa.push($(this).val());
        });
        DSTelefoneEndEmpresa = [];
        $(modal).find('.telefones .adicionado').each(function(index){
            $n = $('.numero_telefone',this).html();
            $t = $('.tipo_telefone',this).html()
            DSTelefoneEndEmpresa.push({ 'numero' : $n , 'tipo' : $t });
        });
        // inicia o salvamento
        $.ajax({
			beforeSend: function() {$(modal).addClass('processando');},
			type : "POST",
            //async: false,
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'Empresas',
                funcao              : 'salvaEnderecoEmpresa',
                IDEmpresa           : IDEmpresa,
                IDEnderecoEmpresa   : IDEnderecoEmpresa,
                CDTipoEndEmpresa    : CDTipoEndEmpresa,
                CEP                 : $(modal).find('form input[name=cep]').val(),
                logradouro              : $(modal).find('form input[name=logradouro]').val(),
                NULogradouroEndEmpresa  : $(modal).find('form input[name=numero]').val(),
                bairro              : $(modal).find('form input[name=bairro]').val(),
                localidade          : $(modal).find('form input[name=localidade]').val(),
                uf                  : $(modal).find('form input[name=uf]').val(),
                DSComplementoEndEmpresa : $(modal).find('form input[name=complemento]').val(),
                DSTelefoneEndEmpresa    : DSTelefoneEndEmpresa

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
                if(IDEnderecoEmpresa){
                    // no caso de alteração de contrato
                    $(modal).modal('hide');
                    $('#modal_cadastro_empresa').modal('show')
                }else{
                    $(modal).modal('hide');
                    $('#modal_cadastro_empresa').modal('show')
                }
            }
		});
    }

    /** EXCLUIR ENDERECO AJAX 
	 * excluir o determinado contrato
	 * @type	function
	 * @date	04/08/21
	 * @since	1.0.0
	 * 
	 * @param	null
	 * @return	BOOLEAN
	 */
    function excluirEnderecoEmpresa(){
        modal = '#modal_cadastro_empresa_endereco';
        IDEmpresa = $(modal).find('input[name=IDEmpresa]').val();
        IDEnderecoEmpresa = $(modal).find('input[name=IDEnderecoEmpresa]').val();
        // inicia a exclusao
        $.ajax({
			beforeSend: function() {$(modal).addClass('processando');},
			type : "POST",
            //async: false,
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'Empresas',
                funcao              : 'excluirEnderecoEmpresa',
                IDEmpresa           : IDEmpresa,
                IDEnderecoEmpresa   : IDEnderecoEmpresa
			}
		}).done(function(retorno){
			var json = jQuery.parseJSON(retorno);
			
            if(!json['status']){
				if(json['erro']){alert(json['erro']);}
				$(modal).removeClass('processando');
			}
			if(json['status']){
                $(modal).removeClass('processando');
                //Exclusao no banco deu certo => fechar modal Endereco e setar o endereco no modal de empresa
                if(IDEnderecoEmpresa){
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

	//quando o modal for chamado
	$('#modal_cadastro_empresa_endereco').on('show.bs.modal', function () {
        modal = '#modal_cadastro_empresa_endereco';
        $IDEmpresa= $('#modal_cadastro_empresa').find('input[name=IDEmpresa]').val();
        $(this).find('input[name=IDEmpresa]').val($IDEmpresa);
        $IDEnderecoEmpresa = $(this).find('input[name=IDEnderecoEmpresa]').val();
        $(this).find('input[name=CDTipoEndEmpresa]').prop("checked",false);
        $(this).find('.telefones .adicionado').remove();
        if($IDEnderecoEmpresa){
            $CDTipoEndEmpresaJSON = jQuery.parseJSON( $('#modal_cadastro_empresa #endereco_'+$IDEnderecoEmpresa).find('.CDTipoEndEmpresaJSON').html() );
            $.each($CDTipoEndEmpresaJSON, function(index,value){
                $('input[name=CDTipoEndEmpresa][value='+value+']',modal).prop("checked",true);
            });
            $DSEnderecoEmpresaJSON = jQuery.parseJSON( $('#modal_cadastro_empresa #endereco_'+$IDEnderecoEmpresa).find('.DSEnderecoEmpresaJSON').html() );
            $(modal).find('form input[name=cep]').val($DSEnderecoEmpresaJSON['cep']);
            $(modal).find('form input[name=logradouro]').val($DSEnderecoEmpresaJSON['logradouro']);
            $(modal).find('form input[name=numero]').val($('#modal_cadastro_empresa #endereco_'+$IDEnderecoEmpresa).find('input[name=NULogradouroEndEmpresa]').val() );
            $(modal).find('form input[name=bairro]').val($DSEnderecoEmpresaJSON['bairro']);
            $(modal).find('form input[name=localidade]').val($DSEnderecoEmpresaJSON['localidade']);
            $(modal).find('form input[name=uf]').val($DSEnderecoEmpresaJSON['uf']);
            $(modal).find('form input[name=complemento]').val($('#modal_cadastro_empresa #endereco_'+$IDEnderecoEmpresa).find('input[name=DSComplementoEndEmpresa]').val() );
            if($('#modal_cadastro_empresa #endereco_'+$IDEnderecoEmpresa).find('.DSTelefoneEndEmpresaJSON').html()){
                $DSTelefoneEndEmpresaJSON = jQuery.parseJSON( $('#modal_cadastro_empresa #endereco_'+$IDEnderecoEmpresa).find('.DSTelefoneEndEmpresaJSON').html() );
            }else{
                $DSTelefoneEndEmpresaJSON = null;
            }
            $.each($DSTelefoneEndEmpresaJSON, function(index,value){
                //$('input[name=CDTipoEndEmpresa][value='+value+']',modal).prop("checked",true);
                $telefone_html = $(modal).find('.telefones .new').clone().prop('class','telefone adicionado');
                $telefone_html.find('div.numero_telefone').html(value['numero']);
                $telefone_html.find('div.tipo_telefone').html(value['tipo']);
                $telefone_html.find('.actions button span').attr('class', "glyphicon glyphicon-trash" );
                $(modal).find('.telefones .new').before($telefone_html);
            });
            $(this).find('.bt_excluir').show();
        }else{
            $(modal).find('form input[name=cep]').val('');
            $(modal).find('form input[name=logradouro]').val('');
            $(modal).find('form input[name=numero]').val('');
            $(modal).find('form input[name=bairro]').val('');
            $(modal).find('form input[name=localidade]').val('');
            $(modal).find('form input[name=uf]').val('');
            $(modal).find('form input[name=complemento]').val('');
            $(this).find('.bt_excluir').hide();
        }
	});

	// quando o modal acabar de abrir
	$('#modal_cadastro_empresa_endereco').on('shown.bs.modal', function () {
        montaActionsButtons();
	});
    // quando o modal for fechado abilitar o modal cadastro empresa pai
    $('#modal_cadastro_empresa_endereco').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
	});
});
</script>

<div class="modal fade modal_cadastro_endereco" id="modal_cadastro_empresa_endereco" tabindex="-1" role="dialog" aria-labelledby="ModalCadastroEmpresaEndereco">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            
            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro Endereco</h4>
            </div>
            <div class="modal-body">
                
                <form id="form_cadastro_empresa_endereco" class="">
                    <input type="hidden" name="IDEmpresa" val=""/>
                    <input type="hidden" name="IDEnderecoEmpresa" val=""/>
                    <?php
                        $lista_CDTipoEndEmpresa = $Empresas->getGERMetadadoOpcoes('CDTipoEndEmpresa');
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group require check">
                                <label for="CDTipoEndEmpresa" class="control-label">Tipo do Endereço</label><br>
                                <div class="checkbox">
                                <?php   
                                foreach($lista_CDTipoEndEmpresa as $item){
                                    $data_class = $item->DSOpcaoMetadado;
                                    $data_class = preg_replace(array("/(A|ã)/","/(E|ê)/","/(I)/","/(O|ó)/","/(U)/","/(C)/","/(F)/","/(G)/","/(P)/","/(T)/","/(I)/","/ /"),explode(" ","a e i o u c f g p t i-"),$data_class);
                                    echo '<label> <input type="checkbox" name="CDTipoEndEmpresa" data-class="'.$data_class.'" value="'. $item->IDMetadado .'"/> '. $item->DSOpcaoMetadado .' </label>';
                                } 
                                ?>
                                </div>
                                <label class="control-label mensagem_erro" for="CDTipoEndEmpresa" style="display:none">Selecione ao menos uma opção!</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group input-text require">
                                <label for="cep" class="control-label">Cep:</label>
                                <input type="text" class="form-control" name="cep" value="" placeholder="CEP">
                                <label class="control-label mensagem_erro" for="cep" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-9">
                            <div class="form-group input-text require">
                                <label for="logradouro" class="control-label">Logradouro:</label>
                                <input type="text" class="form-control" name="logradouro" value="" placeholder="Logradouro:">
                                <label class="control-label mensagem_erro" for="logradouro" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group input-text require">
                                <label for="numero" class="control-label">Número:</label>
                                <input type="text" class="form-control" name="numero" value="" placeholder="Número:">
                                <label class="control-label mensagem_erro" for="numero" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-text require">
                                <label for="bairro" class="control-label">Bairro:</label>
                                <input type="text" class="form-control" name="bairro" value="" placeholder="Bairro:">
                                <label class="control-label mensagem_erro" for="bairro" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-text require">
                                <label for="localidade" class="control-label">Cidade:</label>
                                <input type="text" class="form-control" name="localidade" value="" placeholder="Cidade:">
                                <label class="control-label mensagem_erro" for="localidade" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group input-text require">
                                <label for="uf" class="control-label">Estado:</label>
                                <input type="text" class="form-control" name="uf" value="" placeholder="Estado:">
                                <label class="control-label mensagem_erro" for="uf" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group input-text">
                                <label for="complemento" class="control-label">Complemento:</label>
                                <input type="text" class="form-control" name="complemento" value="" placeholder="Complemento:">
                                <label class="control-label mensagem_erro" for="complemento" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Telefones:</h4>
                            <div class="telefones cadastro_telefones_1">
                                <div class="telefone new">
                                    <div class="numero_telefone"><input name="numero_telefone" type="text" placeholder="(xx)xxxxx-xxxx"></div>
                                    <div class="tipo_telefone">
                                        <select class="form-control" name="tipo_telefone">
                                            <option data-class="" value="">Selecione</option>
                                            <option data-class="" value="Celular">Celular</option>
                                            <option data-class="" value="Fixo">Fixo</option>
                                            <option data-class="" value="Whatsapp">Whatsapp</option>
                                        </select>
                                    </div>
                                    <div class="actions"><button class="btn btn-default btn-xs" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button></div>
                                </div>
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

