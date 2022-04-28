jQuery(function($) {
    var timer = 0;
	$(document).ready(function(){
        // MONTANDO A FORMATAÇÃO DE CNPJS
        //$('.telefones .new input[name=numero_telefone]',modal).unbind('input');
        //$('.telefones .new input[name=numero_telefone]',modal).bind('input',function(){
        $('#cliente').find('input[name=NUCnpjCliente]').each(function(){
            $(this).val( $.fn.formataCNPJ( $(this).val()) );
		});
        // COLOCANDO MASKARA DE CNPJ
        $('input[name=NUCnpjCliente]','#cliente').unbind('input');
        $('input[name=NUCnpjCliente]','#cliente').bind('input',function(){
            $(this).val( $.fn.formataCNPJ($(this).val()) );
		});
        
        // BOTAO DE LOGO DO CLIENTE
        if ($('.conteudo_aba .set_IMLogoCliente').length > 0) {
            if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                $(document).on('click', '.set_IMLogoCliente', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    //var id = button.prev();
                    wp.media.editor.send.attachment = function(props, attachment) {
                        //id.val(attachment.id);
                        //console.log(attachment.sizes);
                        $('.logo_IMLogoCliente > img').attr('src',attachment.sizes.full.url);
                        $('#IMLogoCliente').val(attachment.id);
                        //$('body').addClass('modal-open');
                        //wp.media.editor.send.attachment = send_attachment_bkp;
                    };
                    //wp.media.editor.open(button);
                    wp.media.editor.open();
                    return false;
                });
            }
            //return false;
        }

		// MONTA ACOES DOS BOTES EM LOCAIS
        montaActionsButtons();
	});
    
    //ACOES GERAIS DA PÁGINA BASE
	function montaActionsButtons(){
		// AO CLICAR O BOTAO ADICIONAR CLIENTE
        $('.adicionarCliente').click(function(){
            IDEmpresa = $('select[name=IDEmpresa]','.linha_opcoes').val();
            if(!IDEmpresa){alert('Por favor selecione uma empresa!');return false}
            else{
                IDCliente = '';
			    $('#modal_cadastro_cliente input[name=IDEmpresa]').val(IDEmpresa);
                $('#modal_cadastro_cliente input[name=IDCliente]').val(IDCliente);
                $('#modal_cadastro_cliente').modal('show');
            }
		});
        // BOTAO DE EXCLUIR O CLIENTE
        $('.excluirCliente').click(function(){
            var $delete = confirm("Deseja excluir o cliente: " + $(this).attr('data-razao') + " ?");
            if ($delete == true) {
                excluirCliente( $(this).attr('data-id'), $(this).attr('data-razao') );        
            }
            return false;
		});
        // BOTAO DE SALVAR
        $('.bt_salvar_cliente').click(function(){
            salvarCliente();
		});
        // BOTAO DE VOLTAR
        $('.bt_voltar').click(function(){
            window.history.back();
		});
        // AO CLICAR O BOTAO EDITAR ENDERECO
        $('.editarEndereco').click(function(){
			IDCliente = $('#IDCliente','#form_cadastro_cliente').val();
            IDEnderecoCliente = $(this).attr('data-id');
            $('#modal_cadastro_cliente_endereco input[name=IDCliente]').val(IDCliente);
            $('#modal_cadastro_cliente_endereco input[name=IDEnderecoCliente]').val(IDEnderecoCliente);
            $('#modal_cadastro_cliente_endereco').modal('show');
		});
        // BT ADICIONAR ENDERECO
        $('.bt_adicionar_endereco').click(function(){
            IDEmpresa = $('#IDEmpresa','#form_cadastro_cliente').val();
            IDCliente = $('#IDCliente','#form_cadastro_cliente').val();
            IDEnderecoCliente = null;
            $('#modal_cadastro_cliente_endereco input[name=IDCliente]').val(IDCliente);
            $('#modal_cadastro_cliente_endereco input[name=IDEnderecoCliente]').val(null);
            $('#modal_cadastro_cliente_endereco').modal('show');
		});
        // AO CLICAR O BOTAO EDITAR CONTATO
        $('.editarContato').click(function(){
			IDCliente = $('#IDCliente','#form_cadastro_cliente').val();
            IDContatoCliente = $(this).attr('data-id');
            $('#modal_cadastro_cliente_contato input[name=IDCliente]').val(IDCliente);
            $('#modal_cadastro_cliente_contato input[name=IDContatoCliente]').val(IDContatoCliente);
            $('#modal_cadastro_cliente_contato').modal('show');
		});
        // BT ADICIONAR ENDERECO
        $('.bt_adicionar_contato').click(function(){
            IDEmpresa = $('#IDEmpresa','#form_cadastro_cliente').val();
            IDCliente = $('#IDCliente','#form_cadastro_cliente').val();
            IDContatoCliente = null;
            $('#modal_cadastro_cliente_contato input[name=IDCliente]').val(IDCliente);
            $('#modal_cadastro_cliente_contato input[name=IDContatoCliente]').val(null);
            $('#modal_cadastro_cliente_contato').modal('show');
		});
        // BT EXCLUIR DADOS CONTATO || ENDERECO
        $('.excluirDadosCliente').click(function(){
            $tipo = $(this).attr('data-tipo');
            var $delete = confirm("Deseja excluir o " + $tipo + "?");
            if ($delete == true) {
                excluirDadosCliente( $(this).attr('data-id'), $(this).attr('data-tipo') );        
            }
            return false;
		});
        // ABA CONTRATOS - BT ADICIONAR CONTRATO
        $('.bt_adicionar_contrato').click(function(){
            $('#modal_geral').find('.modal-dialog').removeClass('modal-lg');
            $('#modal_geral').find('.modal-title').html('Adicionar Contrato');
            $.ajax({
                beforeSend: function() {$('#modal_geral').addClass('processando');},
                url: $.fn.getUrlAjax(),
                type: "POST",
                data: {
                    action 	: "Handler_SA",
                    class   : 'Clientes',
                    funcao  : 'getFormContrato'   		
                }
            }).done(function(retorno){
                $('#modal_geral #modal-geral-content').html(retorno);
                $('#modal_geral').removeClass('processando');
                $('#modal_geral .modal-footer .bt_salvar').hide();
                montaActionsModalGeral('vincula-contrato');
            });
            $('#modal_geral').modal('show');
            return false;
        });
	}

    // ACTION MODAL GERAL
    function montaActionsModalGeral(acao){
        
        if( acao == 'vincula-contrato'){
            console.log('montando acoes no modal geral para anexar contratos');
            formModal = '#form_cadastro_cliente_adiciona_contrato';
            formCliente = '#form_cadastro_cliente';
           
            //PESQUISA PROJETOS AJAX.
            $('.buscaajax input[name=busca]',formModal).unbind('input');
            $('.buscaajax input[name=busca]',formModal).bind('input',function(){
                clearTimeout(timer);
                timer = setTimeout(function () {
                    getListaContratos_ajax($('.buscaajax input[name=busca]',formModal).val());
                },400);
            });
            $('.buscaajax input[name=busca]',formModal).unbind('focus');
            $('.buscaajax input[name=busca]',formModal).bind('focus',function(){
                if($(this).val()!=""){
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        getListaContratos_ajax($('.buscaajax input[name=busca]',formModal).val());
                    },400);
                }
            });
            $('.buscaajax',formModal).mouseleave(function() {
                $( this ).find( ".lista_suspensa" ).hide();
            });
            
             // BOTAO DE CRIAR NOVO PROJETO
            $('.novo-contrato',formModal).unbind('click');
            $('.novo-contrato',formModal).on('click',function(){
                $('#redirect input[name=clienteID]').val( $('input[name=IDCliente]',formCliente).val() );
                $('#redirect input[name=clienteName]').val( $('input[name=NMRazaoCliente]',formCliente).val() );
                $('#redirect input[name=urlRetorno]').val( $('#page').val() + '&ID=' + $('input[name=ID]',formCliente).val() );
                $('#redirect').submit();
            });
            
            $('#modal_geral .modal-footer .bt_salvar').unbind('click');
            $('#modal_geral .modal-footer .bt_salvar').on('click',function(){
                //adicionaContratoNoCliente(formModal);
            });
        }
    }

    function montaActionsModalEndereco(){
        modal = '#modal_cadastro_cliente_endereco';
        //console.log('oi');
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
                montaActionsButtons_telefones(modal);
            }
        });

        $('.telefones .new input[name=numero_telefone]',modal).unbind('input');
        $('.telefones .new input[name=numero_telefone]',modal).bind('input',function(){
            $(this).val($.fn.formataTelefone($(this).val()));
		});
        // botão de salvar bt_salvar
        $('.bt_salvar',modal).click(function(){
            salvaEnderecoCliente();
        });

        montaActionsButtons_telefones(modal);
	}
    function montaActionsModalContato(){
        modal = '#modal_cadastro_cliente_contato';
        //console.log('oi');
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
                montaActionsButtons_telefones(modal);
            }
        });

        $('.telefones .new input[name=numero_telefone]',modal).unbind('input');
        $('.telefones .new input[name=numero_telefone]',modal).bind('input',function(){
            $(this).val($.fn.formataTelefone($(this).val()));
		});
        // botão de salvar bt_salvar
        $('.bt_salvar',modal).click(function(){
            salvaContatoCliente();
        });
        

        montaActionsButtons_telefones(modal);
	}

    function montaActionsButtons_telefones(modal){
        //modal = '#modal_cadastro_cliente_endereco';
        $('.telefones .adicionado .actions button',modal).unbind( "click" );
        $('.telefones .adicionado .actions button',modal).click(function(){
            $(this).parents('.adicionado').remove();
        });
    }

    /** SALVA CLIENTE AJAX 
     * Salva via ajax os dados de cliente
     * @type	function
     * @date	19/08/21
     * @since	1.0.0
     * 
     * @param	modal_id
     * @return	BOOLEAN
     */
    function salvarCliente(alvo = null){
        alvo = '#form_cadastro_cliente';
        IDEmpresa = $(alvo).find('#IDEmpresa').val();
        IDCliente = $(alvo).find('#IDCliente').val();
        //IDEmpresa = 1;
        if(!IDEmpresa){
            alert('Ocorreu um erro impedindo esta operação de ir adiante. Reinicie o processo ou contacte um técnico!');
            return false;
        }
        
        validaForm = $.fn.validaForm('#form_cadastro_cliente');
        if(!validaForm)return false;

        // inicia o salvamento
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            //async: false,
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Clientes',
                funcao              : 'salvarCliente',
                IDEmpresa           : IDEmpresa,
                IDCliente           : IDCliente,
                NMRazaoCliente      : $('input[name=NMRazaoCliente]',alvo).val(),
                NUCnpjCliente       : $('input[name=NUCnpjCliente]',alvo).val(),
                NMFantasiaCliente   : $('input[name=NMFantasiaCliente]',alvo).val(),
                EDWebsiteCliente    : $('input[name=EDWebsiteCliente]',alvo).val(),
                IMLogoCliente       : $('#IMLogoCliente',alvo).val(),
                STCliente           : $('input[name=STCliente]:checked',alvo).val()
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            //alert(json);
            if(!json['status']){
                if(json['erro']){alert(json['erro']);}
                $('body').removeClass('processando');
            }
            if(json['status']){
                $page = $('input[name=page]').val();
                window.location = '?page='+$page+'&IDCliente='+IDCliente;
            }
            
        });
        return true;
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
    function salvaEnderecoCliente(){
        modal = '#modal_cadastro_cliente_endereco';
        IDEmpresa = $('#IDEmpresa','#form_cadastro_cliente').val();
        IDCliente = $(modal).find('input[name=IDCliente]').val();
        IDEnderecoCliente = $(modal).find('input[name=IDEnderecoCliente]').val();
        
        validaForm = $.fn.validaForm('#form_cadastro_cliente_endereco');
        if(!validaForm)return false;

        CDTipoEndCliente = [];
        $(modal).find('input:checkbox[name=CDTipoEndCliente]:checked').each(function(index){
            CDTipoEndCliente.push($(this).val());
        });
        DSTelefoneEndCliente = [];
        $(modal).find('.telefones .adicionado').each(function(index){
            $n = $('.numero_telefone',this).html();
            $t = $('.tipo_telefone',this).html()
            DSTelefoneEndCliente.push({ 'numero' : $n , 'tipo' : $t });
        });
        // inicia o salvamento
        $.ajax({
            beforeSend: function() {$(modal).addClass('processando');},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Clientes',
                funcao              : 'salvaEnderecoCliente',
                IDEmpresa           : IDEmpresa,
                IDCliente           : IDCliente,
                IDEnderecoCliente   : IDEnderecoCliente,
                CDTipoEndCliente    : CDTipoEndCliente,
                CEP                     : $(modal).find('form input[name=cep]').val(),
                logradouro              : $(modal).find('form input[name=logradouro]').val(),
                numero                  : $(modal).find('form input[name=numero]').val(),
                bairro                  : $(modal).find('form input[name=bairro]').val(),
                localidade              : $(modal).find('form input[name=localidade]').val(),
                uf                      : $(modal).find('form input[name=uf]').val(),
                complemento             : $(modal).find('form input[name=complemento]').val(),
                DSTelefoneEndCliente    : DSTelefoneEndCliente

            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){
                if(json['erro']){alert(json['erro']);}
                $(modal).removeClass('processando');
            }
            if(json['status']){
                $(modal).removeClass('processando');
                //salvamento deu certo => fechar modal contrato e setar o contrato no modal de empresa
                if(IDEnderecoCliente){
                    // no caso de alteração de contrato
                    $(modal).modal('hide');
                    location.reload();
                    //$('#modal_cadastro_empresa').modal('show')
                }else{
                    $(modal).modal('hide');
                    //$('#modal_cadastro_empresa').modal('show')
                    location.reload();
                }
            }
        });
    }

    /** SALVA CONTATO AJAX 
	 * salva o contato do cliente
	 * @type	function
	 * @date	12/01/22
	 * @since	1.0.0
	 * 
	 * @param	null
	 * @return	BOOLEAN
	 */
    function salvaContatoCliente(){
        modal = '#modal_cadastro_cliente_contato';
        IDEmpresa = $('#IDEmpresa','#form_cadastro_cliente').val();
        IDCliente = $(modal).find('input[name=IDCliente]').val();
        IDContatoCliente = $(modal).find('input[name=IDContatoCliente]').val();
        
        validaForm = $.fn.validaForm('#form_cadastro_cliente_contato');
        if(!validaForm)return false;

        DSReferenciaClienteJSON = [];
        $(modal).find('input:checkbox[name=DSReferenciaClienteJSON]:checked').each(function(index){
            DSReferenciaClienteJSON.push($(this).val());
        });
        DSTelefoneContatoCliente = [];
        $(modal).find('.telefones .adicionado').each(function(index){
            $n = $('.numero_telefone',this).html();
            $t = $('.tipo_telefone',this).html()
            DSTelefoneContatoCliente.push({ 'numero' : $n , 'tipo' : $t });
        });
        // inicia o salvamento
        $.ajax({
            beforeSend: function() {$(modal).addClass('processando');},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Clientes',
                funcao              : 'salvaContatoCliente',
                IDEmpresa           : IDEmpresa,
                IDCliente           : IDCliente,
                IDContatoCliente                : IDContatoCliente,
                DSReferenciaClienteJSON  : DSReferenciaClienteJSON,
                nome                    : $(modal).find('form input[name=nome]').val(),
                setor                   : $(modal).find('form input[name=setor]').val(),
                cargo                   : $(modal).find('form input[name=cargo]').val(),
                email                   : $(modal).find('form input[name=email]').val(),
                DSTelefoneContatoCliente: DSTelefoneContatoCliente
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){
                if(json['erro']){alert(json['erro']);}
                $(modal).removeClass('processando');
            }
            if(json['status']){
                $(modal).removeClass('processando');
                //salvamento deu certo => fechar modal contrato e setar o contrato no modal de empresa
                if(IDContatoCliente){
                    // no caso de alteração de contrato
                    $(modal).modal('hide');
                    location.reload();
                    //$('#modal_cadastro_empresa').modal('show')
                }else{
                    $(modal).modal('hide');
                    //$('#modal_cadastro_empresa').modal('show')
                    location.reload();
                }
            }
        });
    }

    function excluirCliente(cliente,razao){
        
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Clientes',
                funcao              : 'excluirCliente',
                cliente             : cliente,
                razao               : razao
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){if(json['erro']){alert(json['erro']);}}
            if(json['status']){
                if(json['mensagem']){
                    alert(json['mensagem']);
                    location.reload();
                }else{
                    location.reload();
                }
            }
        });
    }

    function excluirDadosCliente(id,tipo){

        $.ajax({
            beforeSend: function() {/*$(modal).addClass('processando');*/},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Clientes',
                funcao              : 'excluirDadosCliente',
                id          : id,
                tipo        : tipo
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){if(json['erro']){alert(json['erro']);}}
            if(json['status']){
                if(json['mensagem']){
                    alert(json['mensagem']);
                    location.reload();
                }else{
                    location.reload();
                }
            }
        });
    }

    function getListaContratos_ajax(s){
        formContrato = '#form_cadastro_cliente';
		$.ajax({
			type : "POST",
			url: $.fn.getUrlAjax(),
			beforeSend: function(){
				$('.buscaajax .lista_suspensa',formModal).html('<i class="fa fa-refresh fa-spin fa-lg fa-fw"></i>carregando ...').show();
                //$('#vinculo_cliente_contrato',formModal).hide();
			},
			data : {
				action 				: "Handler_SA",
                class               : "Clientes",
				funcao  			: 'getlistaContratos_ajax',
                clienteid          : $('input[name=ID]',formContrato).val(),
				s					: s
			}
		}).done(function(retorno){
			if(retorno){
                $('.buscaajax .lista_suspensa',formModal).html(retorno).show();
                $('.buscaajax .lista_suspensa',formModal).find('a').unbind( 'click' );
                $('.buscaajax .lista_suspensa',formModal).find('a').click( function(){
                    $('input[name=contrato]',formModal).val( $(this).html() );
                    $('input[name=contrato]',formModal).attr('data-id',$(this).attr('data-id'));
                    $('.buscaajax .lista_suspensa',formModal).hide();
                    $('#vinculo_cliente_contrato',formModal).show();
                    $('#linha_botao_novo_contrato',formModal).hide();
                    //$('#modal_geral .modal-footer .bt_salvar').show();
                    return false;
                });
            }
            else{
                $('.buscaajax .lista_suspensa',formModal).html(retorno).hide();
                $('#linha_botao_novo_contrato',formModal).show();
            }
		});
	}

    function adicionaContratoNoCliente(form){
        formCliente = '#form_cadastro_cliente';
        validaForm = $.fn.validaForm(form);
        if(!validaForm)return false;
        contratoid = $('input[name=contrato]',form).attr('data-id');
        $.ajax({
            beforeSend: function() {$('#modal_geral').addClass('processando');},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Contratos',
                funcao              : 'setClienteContrato',
                contratoid          : contratoid,
                clienteid           : $('input[name=ID]',formCliente).val(),
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            //alert(json);
            if(!json['status']){
                if(json['erro']){alert(json['erro']);}
                $('#modal_geral').removeClass('processando');
                return false;
            }
            if(json['status']){
                $('#modal_geral').removeClass('processando');
                $('#modal_geral').modal('hide');
                //$page = $('input[name=page]').val();
                //window.location = '?page='+$page+'&ID='+projetoid;
                location.reload();
            }
        });
        return true;
    }

    // EXECUTA QUANDO O MODAL É CHAMADO
	$('#modal_cadastro_cliente_endereco').on('show.bs.modal', function () {
        modal = '#modal_cadastro_cliente_endereco';
        $IDCliente = $('input[name=IDCliente]',modal).val();
        $IDEnderecoCliente = $('input[name=IDEnderecoCliente]',modal).val();
        //zerando o modal
        $(modal).find('input[name=CDTipoEndCliente]').prop("checked",false);
        $(modal).find('.telefones .adicionado').remove();
        $(modal).find('form input[name=cep]').val('');
        $(modal).find('form input[name=logradouro]').val('');
        $(modal).find('form input[name=numero]').val('');
        $(modal).find('form input[name=bairro]').val('');
        $(modal).find('form input[name=localidade]').val('');
        $(modal).find('form input[name=uf]').val('');
        $(modal).find('form input[name=complemento]').val('');
        $(modal).find('.bt_excluir').hide();
        if($IDEnderecoCliente){
            $.ajax({
                beforeSend: function() {$(modal).addClass('processando');},
                type : "POST",
                url: $.fn.getUrlAjax(),
                data : {
                    action 	            : "Handler_SA",
                    class               : 'Clientes',
                    funcao              : 'getEnderecoCliente',
                    IDCliente           : IDCliente,
                    IDEnderecoCliente   : $IDEnderecoCliente
                }
            }).done(function(retorno){
                var json = jQuery.parseJSON(retorno);
                if(!json['status']){
                    if(json['erro']){alert(json['erro']);}
                    $(modal).removeClass('processando');
                }
                if(json['status']){
                    $(modal).removeClass('processando');
                    $endereco = json['endereco'];
                    // monta os tipos selecionados do endereco
                    $CDTipoEndClienteJSON = jQuery.parseJSON($endereco['CDTipoEnderecoClienteJSON']);
                    $.each($CDTipoEndClienteJSON, function(index,value){
                        $('input[name=CDTipoEndCliente][value='+value+']',modal).prop("checked",true);
                    });
                    // monta os dados do endereco
                    $DSEnderecoJSON = jQuery.parseJSON( $endereco['DSEnderecoClienteJSON'] );
                    $(modal).find('form input[name=cep]').val($DSEnderecoJSON['cep']);
                    $(modal).find('form input[name=logradouro]').val($DSEnderecoJSON['logradouro']);
                    $(modal).find('form input[name=numero]').val( $endereco['NULogradouroEndCliente'] );
                    $(modal).find('form input[name=bairro]').val($DSEnderecoJSON['bairro']);
                    $(modal).find('form input[name=localidade]').val($DSEnderecoJSON['localidade']);
                    $(modal).find('form input[name=uf]').val($DSEnderecoJSON['uf']);
                    $(modal).find('form input[name=complemento]').val( $endereco['DSComplementoEndCliente'] );
                    // monta os telefones cadastrados
                    $DSTelefoneEndClienteJSON = jQuery.parseJSON( $endereco['DSTelefoneEndClienteJSON'] );
                    $.each($DSTelefoneEndClienteJSON, function(index,value){
                        $telefone_html = $(modal).find('.telefones .new').clone().prop('class','telefone adicionado');
                        $telefone_html.find('div.numero_telefone').html(value['numero']);
                        $telefone_html.find('div.tipo_telefone').html(value['tipo']);
                        $telefone_html.find('.actions button span').attr('class', "glyphicon glyphicon-trash" );
                        $(modal).find('.telefones .new').before($telefone_html);
                    });
                    //$(modal).find('.bt_excluir').show();
                    montaActionsModalEndereco();
                }
            });
        }
	});

	// quando o modal acabar de abrir
	$('#modal_cadastro_cliente_endereco').on('shown.bs.modal', function () {
        montaActionsModalEndereco();
	});
    // quando o modal for fechado abilitar o modal cadastro empresa pai
    $('#modal_cadastro_cliente_endereco').on('hidden.bs.modal', function () {
        //$('body').addClass('modal-open');
	});

    // EXECUTA QUANDO O MODAL CONTATO É CHAMADO
	$('#modal_cadastro_cliente_contato').on('show.bs.modal', function () {
        modal = '#modal_cadastro_cliente_contato';
        $IDCliente = $('input[name=IDCliente]',modal).val();
        $IDContatoCliente = $('input[name=IDContatoCliente]',modal).val();
        //zerando o modal
        $(modal).find('input[name=DSReferenciaContatoClienteJSON]').prop("checked",false);
        $(modal).find('.telefones .adicionado').remove();
        $(modal).find('form input[name=nome]').val('');
        $(modal).find('form input[name=setor]').val('');
        $(modal).find('form input[name=cargo]').val('');
        $(modal).find('form input[name=email]').val('');
        $(modal).find('.bt_excluir').hide();
        if($IDContatoCliente){
            $.ajax({
                beforeSend: function() {$(modal).addClass('processando');},
                type : "POST",
                url: $.fn.getUrlAjax(),
                data : {
                    action 	            : "Handler_SA",
                    class               : 'Clientes',
                    funcao              : 'getContatoCliente',
                    IDCliente           : IDCliente,
                    IDContatoCliente   : $IDContatoCliente
                }
            }).done(function(retorno){
                var json = jQuery.parseJSON(retorno);
                if(!json['status']){
                    if(json['erro']){alert(json['erro']);}
                    $(modal).removeClass('processando');
                }
                if(json['status']){
                    $(modal).removeClass('processando');
                    $contato = json['contato'];
                    // monta os tipos selecionados do contato
                    $DSReferenciaClienteJSON = jQuery.parseJSON($contato['DSReferenciaClienteJSON']);
                    $.each($DSReferenciaClienteJSON, function(index,value){
                        $('input[name=DSReferenciaClienteJSON][value='+value+']',modal).prop("checked",true);
                    });
                    // monta os dados do endereco
                    $(modal).find('form input[name=nome]').val($contato['NMContatoCliente']);
                    $(modal).find('form input[name=setor]').val($contato['DSSetorCliente']);
                    $(modal).find('form input[name=cargo]').val( $contato['DSCargoCliente'] );
                    $(modal).find('form input[name=email]').val($contato['EDEmailContatoCliente']);
                    // monta os telefones cadastrados
                    $DSTelefoneContatoClienteJSON = jQuery.parseJSON( $contato['DSTelefoneContatoClienteJSON'] );
                    $.each($DSTelefoneContatoClienteJSON, function(index,value){
                        $telefone_html = $(modal).find('.telefones .new').clone().prop('class','telefone adicionado');
                        $telefone_html.find('div.numero_telefone').html(value['numero']);
                        $telefone_html.find('div.tipo_telefone').html(value['tipo']);
                        $telefone_html.find('.actions button span').attr('class', "glyphicon glyphicon-trash" );
                        $(modal).find('.telefones .new').before($telefone_html);
                    });
                    //$(modal).find('.bt_excluir').show();
                    montaActionsModalContato();
                }
            });
        }
	});

	// quando o modal acabar de abrir
	$('#modal_cadastro_cliente_contato').on('shown.bs.modal', function () {
        montaActionsModalContato();
	});
    // quando o modal for fechado abilitar o modal cadastro empresa pai
    $('#modal_cadastro_cliente_contato').on('hidden.bs.modal', function () {
        //$('body').addClass('modal-open');
	});

});