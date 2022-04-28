<script language="javascript">
jQuery(function($) {
	
	function montaActionsButtons(){
		modal = '#modal_cadastro_empresa_contato';
		
        $('.telefones .new .actions button',modal).click(function(){
            $Ntelefone = $(this).parents('.new').find('input[name=numero_telefone]').val();
            $tipoTelefone = $(this).parents('.new').find('select[name=tipo_telefone] :selected').val();
            //console.log($Ntelefone.length);
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
            salvaContatoEmpresa(modal);
            return false;
        });
        $('.bt_excluir',modal).unbind( "click" );
        $('.bt_excluir',modal).click(function(){
            if(confirm( 'Deseja realmente apagar este contato?' )){	
                excluirContatoEmpresa(modal);
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
        modal = '#modal_cadastro_empresa_contato';
        $('.telefones .adicionado .actions button',modal).unbind( "click" );
        $('.telefones .adicionado .actions button',modal).click(function(){
            $(this).parents('.adicionado').remove();
        });
    }
	
    /** SALVA CONTATO AJAX 
	 * Salva o contato cadastrado ou alterado no modal
	 * @type	function
	 * @date	04/08/21
	 * @since	1.0.0
	 * 
	 * @param	varchar #idmodal
	 * @return	BOOLEAN
	 */
    function salvaContatoEmpresa(modal){
        modal = '#modal_cadastro_empresa_contato';
        IDEmpresa = $(modal).find('input[name=IDEmpresa]').val();
        IDContatoEmpresa = $(modal).find('input[name=IDContatoEmpresa]').val();
        
        validaForm = $.fn.validaForm('#form_cadastro_empresa_contato');
        if(!validaForm)return false;

        DSReferenciaContatoEmpJSON = [];
        $(modal).find('input:checkbox[name=DSReferenciaContatoEmpJSON]:checked').each(function(index){
            DSReferenciaContatoEmpJSON.push($(this).val());
        });
        DSTelefoneContatoEmpresaJSON = [];
        $(modal).find('.telefones .adicionado').each(function(index){
            $n = $('.numero_telefone',this).html();
            $t = $('.tipo_telefone',this).html()
            DSTelefoneContatoEmpresaJSON.push({ 'numero' : $n , 'tipo' : $t });
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
                funcao              : 'salvaContatoEmpresa',
                IDEmpresa           : IDEmpresa,
                IDContatoEmpresa    : IDContatoEmpresa,
                NMContatoEmpresa        : $(modal).find('form input[name=NMContatoEmpresa]').val(),
                EDEmailContatoEmpresa   : $(modal).find('form input[name=EDEmailContatoEmpresa]').val(),
                DSSetorContatoEmpresa   : $(modal).find('form input[name=DSSetorContatoEmpresa]').val(),
                DSCargoContatoEmpresa   : $(modal).find('form input[name=DSCargoContatoEmpresa]').val(),
                DSReferenciaContatoEmpJSON      : DSReferenciaContatoEmpJSON,
                DSTelefoneContatoEmpresaJSON    : DSTelefoneContatoEmpresaJSON,
                IDEnderecoEmpresa               : $(modal).find('select[name=IDEnderecoEmpresa]').val()

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
                if(IDContatoEmpresa){
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

    /** EXCLUIR CONTATO AJAX 
	 * excluir o determinado contato
	 * @type	function
	 * @date	05/08/21
	 * @since	1.0.0
	 * 
	 * @param	null
	 * @return	BOOLEAN
	 */
    function excluirContatoEmpresa(){
        modal = '#modal_cadastro_empresa_contato';
        IDEmpresa = $(modal).find('input[name=IDEmpresa]').val();
        IDContatoEmpresa = $(modal).find('input[name=IDContatoEmpresa]').val();
        // inicia a exclusao
        $.ajax({
			beforeSend: function() {$(modal).addClass('processando');},
			type : "POST",
            //async: false,
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'Empresas',
                funcao              : 'excluirContatoEmpresa',
                IDEmpresa           : IDEmpresa,
                IDContatoEmpresa    : IDContatoEmpresa
			}
		}).done(function(retorno){
			//var json = jQuery.parseJSON(retorno);
			alert(json);
			if(!json['status']){
				if(json['erro']){alert(json['erro']);}
				$(modal).removeClass('processando');
			}
			if(json['status']){
                $(modal).removeClass('processando');
                //Exclusao no banco deu certo => fechar modal Endereco e setar o endereco no modal de empresa
                if(IDContatoEmpresa){
                    // no caso de alteração de contrato
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
	$('#modal_cadastro_empresa_contato').on('show.bs.modal', function () {
        // setando o id da empresa
        modal = '#modal_cadastro_empresa_contato';
        $IDEmpresa= $('#modal_cadastro_empresa').find('input[name=IDEmpresa]').val();
        $(this).find('input[name=IDEmpresa]').val($IDEmpresa);
        // get id contato
        $IDContatoEmpresa = $(this).find('input[name=IDContatoEmpresa]').val();
        // zerando as referencias de tipos de contato
        $(this).find('input[name=DSReferenciaContatoEmpJSON]').prop("checked",false);
        // zerando os telefones
        $(this).find('.telefones .adicionado').remove();
        // montando o select de enderecos colocando os enderecos disponiveis 
        $("select[name=IDEnderecoEmpresa]",modal).html('<option value="">Não</option>');
        $('#modal_cadastro_empresa .enderecos .content').find('.endereco').each(function(){
            $("select[name=IDEnderecoEmpresa]",modal).append('<option value='+$(this).attr('data-id')+'>'+$('.titulo',this).html()+'</option>');
        });
        if($IDContatoEmpresa){
            // EDICAO DE CONTATO
            $DSReferenciaContatoEmpJSON = jQuery.parseJSON( $('#modal_cadastro_empresa #contato_'+$IDContatoEmpresa).find('.DSReferenciaContatoEmpJSON').html() );
            $.each($DSReferenciaContatoEmpJSON, function(index,value){
                $('input[name=DSReferenciaContatoEmpJSON][value='+value+']',modal).prop("checked",true);
            });
            $(modal).find('form input[name=NMContatoEmpresa]').val($('#modal_cadastro_empresa #contato_'+$IDContatoEmpresa).find('input[name=NMContatoEmpresa]').val() );
            $(modal).find('form input[name=EDEmailContatoEmpresa]').val($('#modal_cadastro_empresa #contato_'+$IDContatoEmpresa).find('input[name=EDEmailContatoEmpresa]').val() );
            $(modal).find('form input[name=DSSetorContatoEmpresa]').val($('#modal_cadastro_empresa #contato_'+$IDContatoEmpresa).find('input[name=DSSetorContatoEmpresa]').val() );
            $(modal).find('form input[name=DSCargoContatoEmpresa]').val($('#modal_cadastro_empresa #contato_'+$IDContatoEmpresa).find('input[name=DSCargoContatoEmpresa]').val() );
            //montando telefones
            if($('#modal_cadastro_empresa #contato_'+$IDContatoEmpresa).find('.DSTelefoneContatoEmpresaJSON').html()){
                $DSTelefoneContatoEmpresaJSON = jQuery.parseJSON( $('#modal_cadastro_empresa #contato_'+$IDContatoEmpresa).find('.DSTelefoneContatoEmpresaJSON').html() );
            }else{
                $DSTelefoneContatoEmpresaJSON = null;
            }
            $.each($DSTelefoneContatoEmpresaJSON, function(index,value){
                $telefone_html = $(modal).find('.telefones .new').clone().prop('class','telefone adicionado');
                $telefone_html.find('div.numero_telefone').html(value['numero']);
                $telefone_html.find('div.tipo_telefone').html(value['tipo']);
                $telefone_html.find('.actions button span').attr('class', "glyphicon glyphicon-trash" );
                $(modal).find('.telefones .new').before($telefone_html);
            });
            // Setando o select de enderecos
            IDEnderecoEmpresa = $('#modal_cadastro_empresa #contato_'+$IDContatoEmpresa).find('input[name=IDEnderecoEmpresa]').val();
            $(this).find('select[name=IDEnderecoEmpresa]').val(IDEnderecoEmpresa).change();
            
            $(this).find('.bt_excluir').show();
        }
        else{
            // NOVO CONTATO
            // zerando os campos para inserção.
            $(modal).find('form input[name=NMContatoEmpresa]').val('');
            $(modal).find('form input[name=EDEmailContatoEmpresa]').val('');
            $(modal).find('form input[name=DSSetorContatoEmpresa]').val('');
            $(modal).find('form input[name=DSCargoContatoEmpresa]').val('');
            $(modal).find('select[name=IDEnderecoEmpresa]').val('').change();
            $(this).find('.bt_excluir').hide();
        }
	});

	// quando o modal acabar de abrir
	$('#modal_cadastro_empresa_contato').on('shown.bs.modal', function () {
        montaActionsButtons();
	});
    // quando o modal for fechado abilitar o modal cadastro empresa pai
    $('#modal_cadastro_empresa_contato').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
	});
});
</script>

<div class="modal fade" id="modal_cadastro_empresa_contato" tabindex="-1" role="dialog" aria-labelledby="ModalCadastroEmpresaContato">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
    
            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro de Contato</h4>
            </div>
            <div class="modal-body">
                
                <form id="form_cadastro_empresa_contato" class="">
                    <input type="hidden" name="IDEmpresa" val=""/>
                    <input type="hidden" name="IDContatoEmpresa" val=""/>
                    <?php
                        $lista_DSReferenciaContatoEmpJSON = $Empresas->getGERMetadadoOpcoes('DSReferenciaContatoEmpJSON');
                        //$Empresas->printa($lista_DSReferenciaContatoEmpJSON);
                    ?>
                    <!-- TIPO DO CONTATO -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group require check">
                                <label for="DSReferenciaContatoEmpJSON" class="control-label">Referência do Contato</label><br>
                                <div class="checkbox">
                                <?php   
                                foreach($lista_DSReferenciaContatoEmpJSON as $item){
                                    $data_class = $item->DSOpcaoMetadado;
                                    $data_class = preg_replace(array("/(A|ã)/","/(E|ê)/","/(I)/","/(O|ó)/","/(U)/","/(C)/","/(F)/","/(G)/","/(P)/","/(T)/","/(I)/","/ /"),explode(" ","a e i o u c f g p t i-"),$data_class);
                                    echo '<label> <input type="checkbox" name="DSReferenciaContatoEmpJSON" data-class="'.$data_class.'" value="'. $item->IDMetadado .'"/> '. $item->DSOpcaoMetadado .' </label>';
                                } 
                                ?>
                                </div>
                                <label class="control-label mensagem_erro" for="DSReferenciaContatoEmpJSON" style="display:none">Selecione ao menos uma opção!</label>
                            </div>
                        </div>
                    </div>
                    <!-- FIM DO TIPO CONTATO -->

                    <!-- INFORMAÇÕES BASICAS DO CONTATO -->
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group input-text require">
                                <label for="NMContatoEmpresa" class="control-label">Nome:</label>
                                <input type="text" class="form-control" name="NMContatoEmpresa" value="" placeholder="Nome do contato">
                                <label class="control-label mensagem_erro" for="NMContatoEmpresa" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group input-text email require">
                                <label for="EDEmailContatoEmpresa" class="control-label">E-mail:</label>
                                <input type="text" class="form-control" name="EDEmailContatoEmpresa" value="" placeholder="E-mail do contato">
                                <label class="control-label mensagem_erro" for="EDEmailContatoEmpresa" style="display:none">E-mail inválido!</label> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group input-text require">
                                <label for="DSSetorContatoEmpresa" class="control-label">Setor:</label>
                                <input type="text" class="form-control" name="DSSetorContatoEmpresa" value="" placeholder="Setor do contato">
                                <label class="control-label mensagem_erro" for="DSSetorContatoEmpresa" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group input-text require">
                                <label for="DSCargoContatoEmpresa" class="control-label">Cargo:</label>
                                <input type="text" class="form-control" name="DSCargoContatoEmpresa" value="" placeholder="Cargo do contato">
                                <label class="control-label mensagem_erro" for="DSCargoContatoEmpresa" style="display:none">Preencha o campo!</label> 
                            </div>
                        </div>
                    </div>
                    <!-- FIM INFORMAÇÕES BASICAS DO CONTATO -->
                    
                    <!-- TELEFONES DO CONTATO -->
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
                    <!-- FIM TELEFONES DO CONTATO -->
                    <hr>
                    <!-- ENDERECO VINCULADO -->
                     <div class="row">
                        <div class="col-md-12">
                            <h4>Endreço vinculado:</h4>
                        </div>
                        <div class="col-md-12">
                            <select class="form-control" name="IDEnderecoEmpresa">
                                <option data-class="" value="">Não</option>
                            </select>
                        </div>
                    </div>
                    <!-- FIM ENDERECO VINCULADO -->


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