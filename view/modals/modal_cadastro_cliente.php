<script language="javascript">
jQuery(function($) {
	
	function montaActionsButtons(modal){
        //modal = '#'+modal;
		
        // ACAO BOTAO SALVAR CLIENTE
        $('.bt_salvar',modal).unbind( "click" );
        $('.bt_salvar',modal).click(function(){
            salvarCliente(modal);
            return false;
        });
        // Maskara de cnpj
        $('input[name=NUCnpjCliente]',modal).unbind('input');
        $('input[name=NUCnpjCliente]',modal).bind('input',function(){
            $(this).val($.fn.formataCNPJ($(this).val()));
		});


        // BOTAO DE LOGO DO CLIENTE
        if ($('#modal_cadastro_cliente .set_IMLogoCliente').length > 0) {
			if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
				$(document).on('click', '.set_IMLogoCliente', function(e) {
					e.preventDefault();
					var button = $(this);
					//var id = button.prev();
					wp.media.editor.send.attachment = function(props, attachment) {
						//id.val(attachment.id);
						$('.logo_IMLogoCliente > img').attr('src',attachment.sizes.thumbnail.url);
						$('#IMLogoCliente').val(attachment.id);
						$('body').addClass('modal-open');
					};
					wp.media.editor.open(button);
					return false;
				});
				
			}
			return false;
		}

	}
	
	// MODAL CHAMADO
	$('#modal_cadastro_cliente').on('show.bs.modal', function () {
        // COLOCANDO MASKARA DE CNPJ
        $('input[name=NUCnpjCliente]',this).unbind('input');
        $('input[name=NUCnpjCliente]',this).bind('input',function(){
            $(this).val( $.fn.formataCNPJ($(this).val()) );
		});

        modal = '#modal_cadastro_cliente';
        $(this).find('.has-error').each(function(index){
            $(this).removeClass('has-error');
            $('.mensagem_erro',this).hide();
        });
        $('.bt_salvar i',modal).hide();
        console.log('passu aqui');
        //IDEmpresa = $(this).find('#IDEmpresa').val();
        IDCliente = $(this).find('#IDCliente').val();
        
        if( IDCliente ){
            //Edicao de cadastro do cliente
            $.ajax({
                beforeSend: function() {$(modal).addClass('processando');},
                type : "POST",
                url: $.fn.getUrlAjax(),
                data : {action : "Handler_SA",class : 'Clientes',funcao : 'getCliente',IDCliente : IDCliente}
            }).done(function(retorno){
                var json = jQuery.parseJSON(retorno);
                if(!json['status']){
                    if(json['erro']){alert(json['erro']);}
                    $(modal).removeClass('processando');
                }
                if(json['status']){
                    Cliente = json['Cliente'];
                    console.log(retorno);
                    
                    $('input[name=IDEmpresa]',modal).val(Cliente['IDEmpresa']);
                    
                    $('input:radio[name=STCliente]',modal).attr('checked', false);
                    $('input[name=NMRazaoCliente]',modal).val(Cliente['NMRazaoCliente']);
                    $('input[name=NMFantasiaCliente]',modal).val(Cliente['NMFantasiaCliente']);
                    $('input[name=NUCnpjCliente]',modal).val(  $.fn.formataCNPJ( Cliente['NUCnpjCliente'] ) );
                    $('input[name=EDWebsiteCliente]',modal).val(Cliente['EDWebsiteCliente']);
                    $('input:radio[name=STCliente]',modal).filter('[value='+Cliente['STCliente']+']').attr('checked', true);
                    //IMLogoCliente STCliente
                    $('.logo_IMLogoCliente > img',modal).attr('src',Cliente['IMLogoCliente_src']);
		            $('#IMLogoCliente',modal).val(Cliente['IMLogoCliente']);
                    $(modal).removeClass('processando');
                }
            });
        }else{
            //Novo cadastro de Empresa
            $('input:radio[name=STCliente]',modal).attr('checked', false);
            $('input[name=NMRazaoCliente]',modal).val('');
            $('input[name=NMFantasiaCliente]',modal).val('');
            $('input[name=NUCnpjCliente]',modal).val('');
            $('input[name=EDWebsiteCliente]',modal).val('');
            $('.logo_IMLogoCliente > img',modal).attr('src','');
		    $('#IMLogoCliente',modal).val('');
        }
	});

	// MODAL ABERTO
	$('#modal_cadastro_cliente').on('shown.bs.modal', function () {
        
        //montaActionsButtons($(this).attr('id'));
        //IDEmpresa = $(this).find('#IDEmpresa').val();
        //if( IDEmpresa ){
        //}else{
        //    $('input[name=NMRazaoEmpresa]').focus();
            montaActionsButtons( '#' + $(this).attr('id') );
        //}
	});

    /** SALVA CLIENTE AJAX 
	 * Salva via ajax os dados de cliente
	 * @type	function
	 * @date	19/08/21
	 * @since	1.0.0
	 * 
	 * @param	modal_id
	 * @return	BOOLEAN
	 */
    function salvarCliente(modal,alvo = null){
        IDEmpresa = $(modal).find('#IDEmpresa').val();
        IDCliente = $(modal).find('#IDCliente').val();
        //IDEmpresa = 1;
        if(!IDEmpresa){
            alert('Ocorreu um erro impedindo esta operação de ir adiante. Reinicie o processo ou contacte um técnico!');
            return false;
        }
        
        validaForm = $.fn.validaForm('#form_cadastro_cliente');
        if(!validaForm)return false;

        // inicia o salvamento
        $.ajax({
			beforeSend: function() {$(modal).addClass('processando');},
			type : "POST",
            //async: false,
			url: $.fn.getUrlAjax(),
			data : {
				action 	            : "Handler_SA",
				class               : 'Clientes',
                funcao              : 'salvarCliente',
                IDEmpresa           : IDEmpresa,
                IDCliente           : IDCliente,
                NMRazaoCliente      : $('input[name=NMRazaoCliente]').val(),
                NUCnpjCliente       : $('input[name=NUCnpjCliente]').val(),
                NMFantasiaCliente   : $('input[name=NMFantasiaCliente]').val(),
                EDWebsiteCliente    : $('input[name=EDWebsiteCliente]').val(),
                IMLogoCliente       : $('#IMLogoCliente',modal).val(),
                STCliente           : $('input[name=STCliente]:checked',modal).val()
			}
		}).done(function(retorno){
			var json = jQuery.parseJSON(retorno);
			//alert(json);
			if(!json['status']){
				if(json['erro']){alert(json['erro']);}
				$(modal).removeClass('processando');
			}
			if(json['status']){
                //$(modal).removeClass('processando');
                cliente = '';
                if(json['IDCliente'])
                    cliente='&IDCliente='+json['IDCliente'];
                window.location = '?page=actcon-sigsa/view/page-clientes.php'+cliente;
			}
            
		});
        return true;
    }
});
</script>

<style>
    #modal_cadastro_cliente .logo_IMLogoCliente img{width: 60px;border-radius: 50px;border: 4px solid #cfcfcf;margin: 0 0 8px 0;}
</style>

<div class="modal fade" id="modal_cadastro_cliente" tabindex="-1" role="dialog" aria-labelledby="ModalCadastroCliente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
    
            <div class="processando_mask"><div><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br><span>processando</span></div></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro Cliente</h4>
            </div>
            <div class="modal-body">
                <form id="form_cadastro_cliente" class="">
                    <input type="hidden" value="" name="IDEmpresa" id="IDEmpresa"/>
                    <input type="hidden" value="" name="IDCliente" id="IDCliente"/>
                    <div class="row">
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <div><label for="IMLogoCliente">Logo cliente</label></div>
                                <div class="logo_IMLogoCliente">
                                    <img src=""/>
                                </div>
                                <div>
                                    <input type="hidden" value="" id="IMLogoCliente" name="" max="" min="1" step="1">
                                    <button class="set_IMLogoCliente button">Alterar</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            
                            <div class="form-group require input-text">
                                <label for="NMRazaoCliente">Razão social</label>
                                <input type="text" class="form-control" name="NMRazaoCliente" placeholder="NMRazaoCliente">
                                <label class="control-label mensagem_erro" for="NMRazaoCliente" style="display:none">Preencha o campo!</label> 
                            </div>
                            
                            <div class="form-group require input-text">
                                <label for="EDWebsiteCliente">Web site</label>
                                <input type="text" class="form-control" name="EDWebsiteCliente" placeholder="EDWebsiteCliente">
                                <label class="control-label mensagem_erro" for="EDWebsiteCliente" style="display:none">Preencha o campo!</label> 
                            </div>

                            

                        </div>
                        
                        <div class="col-md-3">
                            
                            <div class="form-group require input-text">
                                <label for="NMFantasiaCliente">Nome fantasia</label>
                                <input type="text" class="form-control" name="NMFantasiaCliente" placeholder="NMFantasiaCliente">
                                <label class="control-label mensagem_erro" for="NMFantasiaCliente" style="display:none">Preencha o campo!</label> 
                            </div>

                            <div class="form-group require input-radio">
                                <div>
                                    <label for="STCliente" control-label">Situação</label>                                
                                </div>
                                <div>
                                    <label class="radio-inline"><input type="radio" name="STCliente" value="1"> Ativo</label>
                                    <label class="radio-inline"><input type="radio" name="STCliente" value="0"> Desativado</label>
                                </div>
                                <label class="control-label mensagem_erro" for="STCliente" style="display:none">Selecione uma opção!</label>
                            </div>

                        </div>

                        <div class="col-md-3">
                            <div class="form-group require input-text cpfCnpj">
                                <label for="NUCnpjCliente">N CNPJ</label>
                                <input type="text" class="form-control" name="NUCnpjCliente" placeholder="NUCnpjCliente">
                                <label class="control-label mensagem_erro" for="NUCnpjCliente" style="display:none">Preencha o CNPJ corretamente!</label> 
                            </div>
                        </div>
                    
                    </div> 
		
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary bt_salvar"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>Salvar</button>
            </div>
        </div>
    </div>
</div>

