jQuery(function($) {
	
    var timer = 0;

    // FORMULÁRIO DE CADASTRO
    form = "#form_cadastro_contrato";

	$(document).ready(function(){
        // --> select de atitivo contratual
        // a primeira vez que lê a página
        //tipocontrato = $('select[name=tipocontrato]',form).val();
        //if(tipocontrato)dataClass = $('option[value='+tipocontrato+']',this).attr('data-class');
        //else dataClass = 'null';
        //if(dataClass == 'aditivo-contratual') $(form).find('input[name=codigoaditivo]').parent().addClass('require').parent().show('fast');
        //else $(form).find('input[name=codigoaditivo]').parent().removeClass('require').parent().hide('fast');
        // MONTA ACOES DOS BOTES EM LOCAIS
		montaActionsButtons();

        getListaContatosCliente();
	});

	function montaActionsButtons(){
    
        // AO CLICAR O BOTAO ADICIONAR
        $('.adicionarContrato').click(function(){
            IDEmpresa = $('select[name=IDEmpresa]','.linha_opcoes').val();
            if(!IDEmpresa){alert('Por favor selecione uma empresa!');return false}
            else{
			    page = $('input[name=page]','.linha_opcoes').val();
                window.location = '?page='+page + '&ID=-1';
            }
		});
        // BOTAO DE EXCLUIR O CONTRATO
        $('.excluirContrato').click(function(){
            if( $('input[name=ID]',form).length > 0 ){
                var $delete = confirm("Deseja excluir o contrato: " + $('#descricao',form).val() + " ?");
                if ($delete == true)
                excluirContrato( $('input[name=ID]',form).val(), $('#descricao',form).val() );
                return true;
            }else{
                var $delete = confirm("Deseja excluir o contrato: " + $(this).attr('data-descricao') + " ?");
                if ($delete == true)
                    excluirContrato( $(this).attr('data-id'), $(this).attr('data-descricao') );
                return false;
            }
        });
        
        // BOTAO DE SALVAR
        $('.bt_salvar_cadastro').click(function(){
            salvarContrato(form);
		});
        // BOTAO DE VOLTAR
        $('.bt_voltar').click(function(){
            window.history.back();
		});
        // quando o select tipo contrato for mudado
        $(form).find('select[name=situacaocontrato]').unbind( "change" );
        $(form).find('select[name=situacaocontrato]').change(function(){
            $valororiginal = $(this).attr('data-original');
            $valoralterado = $(this).val();
            if($valororiginal != $valoralterado)
                $(form).find('#motivomudancastatus').parent().addClass('require').parent().show('fast');
            else $(form).find('#motivomudancastatus').parent().removeClass('require').parent().hide('fast');
        });
        
        // BOTAO DE BUSCAR CLIENTE
        $('#buscaCliente',form).click(function(){
            IDEmpresa = $('#IDEmpresa',form).val();
            if(!IDEmpresa){alert('Selecione uma empresa!');return false;}
            $(this).hide();
            $('.buscaajax').show();
        });
        // Busca auto-complete ajax
        $('.buscaajax input[name=busca]',form).unbind('input');
        $('.buscaajax input[name=busca]',form).bind('input',function(){
            clearTimeout(timer);
            timer = setTimeout(function () {
                getListaClientes_ajax($('.buscaajax input[name=busca]',form).val());
            },400);
		});
        $('.buscaajax input[name=busca]',form).unbind('focus');
        $('.buscaajax input[name=busca]',form).bind('focus',function(){
            if($(this).val()!=""){
                clearTimeout(timer);
                timer = setTimeout(function () {
                    getListaClientes_ajax($('.buscaajax input[name=busca]',form).val());
                },400);
            }
		});
        $('.buscaajax',form).mouseleave(function() {
            $( this ).find( ".lista_suspensa" ).hide();
        });

        // BOTAO EDITAR LISTA DE CONTATOS DO CLIENTE NO CONTRATO
        $('.editarContatos').click(function(){
            if( $('#contatos-contrato').hasClass('edicao') )
                $('#contatos-contrato').removeClass('edicao');
            else $('#contatos-contrato').addClass('edicao');
        });
        // BOTAO ADICIONAR PROJETO
        $('.bt_adicionar_projeto').click(function(){
            $('#modal_geral').find('.modal-dialog').removeClass('modal-lg');
            $('#modal_geral').find('.modal-title').html('Adicionar projeto');
            $.ajax({
                beforeSend: function() {$('#modal_geral').addClass('processando');},
                url: $.fn.getUrlAjax(),
                type: "POST",
                data: {
                    action 	: "Handler_SA",
                    class   : 'Contratos',
                    funcao  : 'getFormProjeto'   		
                }
            }).done(function(retorno){
                $('#modal_geral #modal-geral-content').html(retorno);
                $('#modal_geral').removeClass('processando');
                $('#modal_geral .modal-footer .bt_salvar').hide();
                montaActionsModal('vincula-projeto');
            });
            $('#modal_geral').modal('show');
            return false;
        });
        // BOTAO ANEXAR ARQUIVO
        if ($('.anexar_arquivo').length > 0) {
            if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                $(document).on('click', '.anexar_arquivo', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    wp.media.editor.send.attachment = function(props, attachment) {
                        $lista = [];
                        if($('#lista_anexos').val()){
                            $lista = jQuery.parseJSON( $('#lista_anexos').val() );    
                        }
                        if( jQuery.inArray( parseInt( attachment.id ) , $lista ) < 0 ) {
                            $lista.push(attachment.id);
                            setAnexosContrato($lista);
                        }
                        else console.log('nao fazer nada');
                    };
                    wp.media.editor.open();
                    return false;
                });
            }
        }
        montaActionsAnexos();
	}

    function montaActionsModal(acao){
        
        if( acao == 'vincula-projeto'){
            formModal = '#form_cadastro_contrato_adiciona_projeto';
            formContrato = '#form_cadastro_contrato';
           
            //PESQUISA PROJETOS AJAX.
            $('.buscaajax input[name=busca]',formModal).unbind('input');
            $('.buscaajax input[name=busca]',formModal).bind('input',function(){
                clearTimeout(timer);
                timer = setTimeout(function () {
                    getListaProjetos_ajax($('.buscaajax input[name=busca]',formModal).val());
                },400);
            });
            $('.buscaajax input[name=busca]',formModal).unbind('focus');
            $('.buscaajax input[name=busca]',formModal).bind('focus',function(){
                if($(this).val()!=""){
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        getListaProjetos_ajax($('.buscaajax input[name=busca]',formModal).val());
                    },400);
                }
            });
            $('.buscaajax',formModal).mouseleave(function() {
                $( this ).find( ".lista_suspensa" ).hide();
            });

             // BOTAO DE CRIAR NOVO PROJETO
             $('.novo-projeto',formModal).unbind('click');
             $('.novo-projeto',formModal).on('click',function(){
                 $('#redirect input[name=contratoID]').val( $('input[name=ID]',formContrato).val() );
                 $('#redirect input[name=contratoCodigo]').val( $('input[name=codigo]',formContrato).val() );
                 $('#redirect input[name=contratoDescricao]').val( $('#descricao',formContrato).val() );
                 $('#redirect input[name=contratoInicio]').val( $('input[name=datainicio]',formContrato).val() );
                 $('#redirect input[name=contratoTermino]').val( $('input[name=datatermino]',formContrato).val() );
                 $('#redirect input[name=urlRetorno]').val( $('#page').val() + '&ID=' + $('input[name=ID]',formContrato).val() );
                 $('#redirect').submit();
             });

             $( ".datepicker" ).datepicker({
                dateFormat: "yy-mm-dd",
                altFormat: "yymmdd",
                monthNames: [ "Janeiro", "Fevereiro", "Março", "Abril",
                       "Maio", "Junho", "Julho", "Agosto", "Setembro",
                       "Outubro", "Novembro", "Dezembro" ],
                dayNamesMin: ['Do', 'Se', 'Tr', 'Qa', 'Qi', 'Se', 'Sa']
            });
            $('#modal_geral .modal-footer .bt_salvar').unbind('click');
            $('#modal_geral .modal-footer .bt_salvar').on('click',function(){
                adicionaProjetoNoContrato(formModal);
            });
        }
    }

    function montaActionsAnexos(){
        tabelaAnexos = '#conteudo_anexos';
        formContrato = '#form_cadastro_contrato';
        $('.excluiAnexo',tabelaAnexos).unbind('click');
        $('.excluiAnexo',tabelaAnexos).on('click',function(){
            $lista = jQuery.parseJSON( $('#lista_anexos').val() );
            $posicao = jQuery.inArray( parseInt( $(this).attr('data-id') ) , $lista );
            if( $posicao >= 0 ) {
                $lista.splice($posicao, 1);
                setAnexosContrato($lista);
            }
        });
    }

    function montaActionsContatos(){
        $('#contatos-contrato .contato',form).unbind('click');
        $('#contatos-contrato .contato',form).click(function(){
            if( $('#contatos-contrato').hasClass('edicao') ){
                lista = jQuery.parseJSON($('input[name=contatosAtivos]').val());
                posicao = jQuery.inArray( parseInt($(this).attr('data-id')), lista);
                if ( posicao >= 0 ){
                    console.log('existe');
                    lista.splice( posicao, 1 );
                    $(this).removeClass('ativo');
                }
                else{
                    lista.push( $(this).attr('data-id') );
                    $(this).addClass('ativo');
                }
                $('input[name=contatosAtivos]').val('['+lista+']');
                $('#contatos-contrato').addClass('alterado');
            }
        });

    }

    function getListaClientes_ajax(s){
		$.ajax({
			type : "POST",
			url: $.fn.getUrlAjax(),
			beforeSend: function(){
				$('.buscaajax .lista_suspensa',form).html('<i class="fa fa-refresh fa-spin fa-lg fa-fw"></i>carregando ...').show();
			},
			data : {
				action 				: "Handler_SA",
                class               : "Contratos",
				funcao  			: 'getlistaClientes_ajax',
				s					: s,
                empresa             : $('#IDEmpresa',form).val()
			}
		}).done(function(retorno){
			if(retorno){
                $('.buscaajax .lista_suspensa',form).html(retorno).show();
                $('.buscaajax .lista_suspensa',form).find('a').unbind( 'click' );
                $('.buscaajax .lista_suspensa',form).find('a').click( function(){
                    $('input[name=cliente]',form).val( $(this).html() );
                    $('input[name=cliente]',form).attr('data-id',$(this).attr('data-id'));
                    $('.buscaajax',form).hide();
                    $('#buscaCliente',form).show();
                    getListaContatosCliente( $(this).attr('data-id') );
                    return false;
                });
            }
            else{
                $('.buscaajax .lista_suspensa').html(retorno).hide();
            }
		});
	}

    function getListaProjetos_ajax(s){
        formContrato = '#form_cadastro_contrato';
		$.ajax({
			type : "POST",
			url: $.fn.getUrlAjax(),
			beforeSend: function(){
				$('.buscaajax .lista_suspensa',formModal).html('<i class="fa fa-refresh fa-spin fa-lg fa-fw"></i>carregando ...').show();
                $('#vinculo_contrato_projeto',formModal).hide();
			},
			data : {
				action 				: "Handler_SA",
                class               : "Contratos",
				funcao  			: 'getlistaProjetos_ajax',
                contratoid          : $('input[name=ID]',formContrato).val(),
				s					: s
			}
		}).done(function(retorno){
			if(retorno){
                $('.buscaajax .lista_suspensa',formModal).html(retorno).show();
                $('.buscaajax .lista_suspensa',formModal).find('a').unbind( 'click' );
                $('.buscaajax .lista_suspensa',formModal).find('a').click( function(){
                    $('input[name=projeto]',formModal).val( $(this).html() );
                    $('input[name=projeto]',formModal).attr('data-id',$(this).attr('data-id'));
                    $('.buscaajax .lista_suspensa',formModal).hide();
                    $('#vinculo_contrato_projeto',formModal).show();
                    $('#linha_botao_novo_projeto',formModal).hide();
                    $('#modal_geral .modal-footer .bt_salvar').show();
                    return false;
                });
            }
            else{
                $('.buscaajax .lista_suspensa',formModal).html(retorno).hide();
                $('#linha_botao_novo_projeto',formModal).show();
            }
		});
	}


    /** SALVA CONTRATO AJAX 
     * Salva via ajax os dados de contrato
     * @type	function
     * @date	24/01/22
     * @since	1.0.0
     * 
     * @param	modal_id
     * @return	BOOLEAN
     */
    function salvarContrato(form = null){
        console.log('funcao salvar contrato');
        form = "#form_cadastro_contrato";
        IDEmpresa = $('#IDEmpresa',form).val();
        if(!IDEmpresa){alert('Selecione uma empresa!');return false;}
        contratoid = $('input[name=ID]',form).val();
        //if(!contratoid){alert('Contrato inválido!');return false;}
        if( $('input[name=codigoaditivo]',form ).is(":visible") ) codigoaditivo=$('input[name=codigoaditivo]',form ).val();
        else codigoaditivo = null;
        console.log('ate aqui');
        validaForm = $.fn.validaForm(form);
        if(!validaForm)return false;
        console.log('ate aqui 2');
         // inicia o salvamento
         $.ajax({
            beforeSend: function() {
                $('body').parent().addClass('processando');
            },
            type : "POST",
            //async: false,
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Contratos',
                funcao              : 'salvarContrato',
                IDEmpresa           : IDEmpresa,
                contratoid          : $('input[name=ID]',form).val(),
                tipocontrato        : $('select[name=tipocontrato]',form).val(),
                situacaocontrato    : $('select[name=situacaocontrato]',form).val(),
                motivomudancastatus : $('#motivomudancastatus',form).val(),
                codigo              : $('input[name=codigo]',form).val(),
                codigoaditivo       : codigoaditivo,
                datainicio          : $('input[name=datainicio]',form).val(),
                datatermino         : $('input[name=datatermino]',form).val(),
                descricao           : $('#descricao',form).val(),
                cliente             : $('input[name=cliente]',form).attr('data-id'),
                contatosAtivos      : jQuery.parseJSON($('input[name=contatosAtivos]',form).val())
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            //alert(json);
            if(!json['status']){
                if(json['erro']){alert(json['erro']);}
                $('body').parent().removeClass('processando');
            }
            if(json['status']){
                $page = $('input[name=page]').val();
                if( contratoid != json['contratoid'] ){
                    window.location = '?page='+$page+'&ID='+json['contratoid'];
                }else
                {
                    window.location = '?page='+$page+'&ID='+contratoid;
                }   
            }
        });
        return true;
    }

    function excluirContrato(contratoid,descricao){
        $.ajax({
            beforeSend: function() {/*$(modal).addClass('processando');*/},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Contratos',
                funcao              : 'excluirContrato',
                contratoid          : contratoid,
                descricao           : descricao
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){if(json['erro']){alert(json['erro']);}}
            if(json['status']){
                if(json['mensagem']){
                    alert(json['mensagem']);
                    window.location = '?page='+$('input[name=page]').val();
                }else{
                    window.location = '?page='+$('input[name=page]').val();
                }
            }
        });
    }

    function adicionaProjetoNoContrato(form){
        formContrato = '#form_cadastro_contrato';
        validaForm = $.fn.validaForm(form);
        if(!validaForm)return false;
        projetoid = $('input[name=projeto]',form).attr('data-id');
        $.ajax({
            beforeSend: function() {$('#modal_geral').addClass('processando');},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Contratos',
                funcao              : 'setContratoProjeto',
                projetoid           : projetoid,
                contratoid              : $('input[name=ID]',formContrato).val(),
                situacaoContratoProjeto : $('select[name=situacaoContratoProjeto]',form).val(),
                inicioProjeto           : $('input[name=inicioProjeto]',form).val(),
                terminoProjeto          : $('input[name=terminoProjeto]',form).val(),
                gestaoSolicitacaoAcesso : $('select[name=gestaoSolicitacaoAcesso]',form).val(),
                usoPerfilPadrao         : $('select[name=usoPerfilPadrao]',form).val(),
                usoLocalPadrao          : $('select[name=usoLocalPadrao]',form).val(),
                cadastroEstudante       : $('select[name=cadastroEstudante]',form).val()

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

    function setAnexosContrato($lista){
        formContrato = '#form_cadastro_contrato';
        $lista.sort();
        //console.log($lista);
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	    : "Handler_SA",
                class       : 'Contratos',
                funcao      : 'setAnexosContrato',
                contratoid  : $('input[name=ID]',formContrato).val(),
                lista       : JSON.stringify($lista)
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){
                if(json['erro']){alert(json['erro']);}
                $('body').removeClass('processando');
                return false;
            }
            if(json['status']){
                $('#conteudo_anexos').html(json['html']);
                montaActionsAnexos();
                $('body').removeClass('processando');
            }
        });
        return true;
    }

    function getListaContatosCliente(clienteid = null){
        contratoid = $('input[name=ID]',formContrato).val();
        if(!clienteid){
            id = $('input[name=cliente]',form).attr('data-id');
            if(id) clienteid = id;
        }
        if( clienteid && contratoid ){
            $.ajax({
                beforeSend: function() {$('body').addClass('processando');},
                type : "POST",
                url: $.fn.getUrlAjax(),
                data : {
                    action 	    : "Handler_SA",
                    class       : 'Contratos',
                    funcao      : 'getListaContatosCliente',
                    contratoid  : $('input[name=ID]',formContrato).val(),
                    clienteid   : clienteid
                }
            }).done(function(retorno){
                var json = jQuery.parseJSON(retorno);
                if(!json['status']){
                    if(json['erro']){alert(json['erro']);}
                    $('body').removeClass('processando');
                    return false;
                }
                if(json['status']){
                    $('#contatos-contrato input[name=contatosAtivos]').val('['+json['contatosAtivos']+']');
                    //console.log(json['contatosAtivos']);
                    $('#contatos-contrato .content .contatos').html(json['html']);
                    montaActionsContatos();
                    $('body').removeClass('processando');
                }
            });
        }
        
        //$('#contatos-contrato .content').html(clienteid);
        return false;
    }
});