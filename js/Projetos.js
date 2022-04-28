jQuery(function($) {
	
    var timer = 0;
    var dataInicioContrato = '0';
    var dataTerminoContrato = '0';
    var dataInicioContratoProjeto = '0';
    // FORMULÁRIO DE CADASTRO
    form = "#form_cadastro_projeto";

	$(document).ready(function(){
 		montaActionsButtons();
        montaActionsComposicoesFuncionais();
        montaActionsButtonsListaComposicoesVinculadas();
        
        montaActionsButtonsAbaPlataformas();
        montaActionsButtonsListaPlataformasVinculadas();

        montaActionsButtonsAbaLocais();
        montaActionsButtonsListaLocaisVinculados();
	});


	function montaActionsButtons(){
        //console.log();
        dataInicioContrato = new Date($('input[name=contrato]').attr('data-contratoinicio'));

        if( $('input[name=contrato]').attr('data-contratoinicio') < $('input[name=inicioProjeto]',form).val() ){
            dataInicioContratoProjeto = new Date( $('input[name=inicioProjeto]',form).val() );
        }
        else{
            dataInicioContratoProjeto = dataInicioContrato;
        }
        dataInicioContrato = new Date($('input[name=contrato]').attr('data-contratoinicio'));
        dataInicioContrato.setDate(dataInicioContrato.getDate() + 1);
        dataTerminoContrato = new Date($('input[name=contrato]').attr('data-contratotermino'));
        dataTerminoContrato.setDate(dataTerminoContrato.getDate() + 1);

        dataInicioContratoProjeto.setDate(dataInicioContratoProjeto.getDate() + 1);
        $( ".terminoProjeto" ).datepicker({
			dateFormat: "yy-mm-dd",
			altFormat: "yymmdd",
			monthNames: [ "Janeiro", "Fevereiro", "Março", "Abril",
                   "Maio", "Junho", "Julho", "Agosto", "Setembro",
                   "Outubro", "Novembro", "Dezembro" ],
			dayNamesMin: ['Do', 'Se', 'Tr', 'Qa', 'Qi', 'Se', 'Sa'],
            minDate: dataInicioContratoProjeto, 
            maxDate: dataTerminoContrato
		});
        $( ".inicioProjeto" ).datepicker({
			dateFormat: "yy-mm-dd",
			altFormat: "yymmdd",
			monthNames: [ "Janeiro", "Fevereiro", "Março", "Abril",
                   "Maio", "Junho", "Julho", "Agosto", "Setembro",
                   "Outubro", "Novembro", "Dezembro" ],
			dayNamesMin: ['Do', 'Se', 'Tr', 'Qa', 'Qi', 'Se', 'Sa'],
            minDate: dataInicioContrato,
            maxDate: dataTerminoContrato,
            onSelect: function (selected,evnt){
                $( ".terminoProjeto" ).datepicker('option', 'minDate', selected);
            }
		});

        // AO CLICAR O BOTAO ADICIONAR
        $('.adicionarProjeto').click(function(){
            IDEmpresa = $('select[name=IDEmpresa]','.linha_opcoes').val();
            if(!IDEmpresa){alert('Por favor selecione uma empresa!');return false}
            else{
			    page = $('input[name=page]','.linha_opcoes').val();
                window.location = '?page='+page + '&ID=-1';
            }
		});
        // BOTAO DE EXCLUIR O PROJETO
        $('.excluirProjeto').click(function(){
            if( $('input[name=ID]',form).length > 0 ){
                var $delete = confirm("Deseja excluir o projeto: " + $('#descricao',form).val() + " ?");
                if ($delete == true)
                excluirProjeto( $('input[name=ID]',form).val(), $('#descricao',form).val() );
                return true;
            }else{
                var $delete = confirm("Deseja excluir o projeto: " + $(this).attr('data-descricao') + " ?");
                if ($delete == true)
                    excluirProjeto( $(this).attr('data-id'), $(this).attr('data-descricao') );
                return false;
            }
        });
        // BOTAO DE ALTERAR A LOGO
        if ($('.set_logoProjeto',form).length > 0) {
            if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                $(document).on('click', '.set_logoProjeto', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    //var id = button.prev();
                    wp.media.editor.send.attachment = function(props, attachment) {
                        //id.val(attachment.id);
                        //console.log(attachment.sizes);
                        $('.logo_projeto > img',form).attr('src',attachment.sizes.full.url);
                        $('#logo_projeto').val(attachment.id);
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
        // BOTAO DE BUSCA CONTRATO
        $('#buscaContrato',form).click(function(){
            $(this).hide();
            $('.buscaajax').show();
        });
        // Busca auto-complete ajax
        $('.buscaajax input[name=busca]',form).unbind('input');
        $('.buscaajax input[name=busca]',form).bind('input',function(){
            clearTimeout(timer);
            timer = setTimeout(function () {
                getListaContratos_ajax($('.buscaajax input[name=busca]',form).val());
            },600);
        });
        $('.buscaajax input[name=busca]',form).unbind('focus');
        $('.buscaajax input[name=busca]',form).bind('focus',function(){
            if($(this).val()!=""){
                clearTimeout(timer);
                timer = setTimeout(function () {
                    getListaContratos_ajax($('.buscaajax input[name=busca]',form).val());
                },600);
            }
        });
        $('.buscaajax',form).mouseleave(function() {
            $( this ).find( ".lista_suspensa" ).hide();
        });

        // BOTAO DE SALVAR
        $('.bt_salvar_cadastro').click(function(){
            salvarProjeto(form);
		});
        // BOTAO DE VOLTAR
        $('.bt_voltar').click(function(){
            window.history.back();
		});

	}

    function getListaContratos_ajax(s){
		$.ajax({
			type : "POST",
			url: $.fn.getUrlAjax(),
			beforeSend: function(){
				$('.buscaajax .lista_suspensa').html('<i class="fa fa-refresh fa-spin fa-lg fa-fw"></i>carregando ...').show();
			},
			data : {
				action 				: "Handler_SA",
                class               : "Projetos",
				funcao  			: 'getlistaContratos_ajax',
				s					: s,
                contratoAtual       : $('input[name=contrato]',form).attr('data-id')
			}
		}).done(function(retorno){
			if(retorno){
                $('.buscaajax .lista_suspensa').html(retorno).show();
                $('.buscaajax .lista_suspensa').find('a').unbind( 'click' );
                $('.buscaajax .lista_suspensa').find('a').click( function(){
                    response = confirm("Voce está prestes a trocar o contrato atual vinculado a este projeto!\nEsta ação apagará o vínculo existente entre este projeto e o contrato.\nDeseja continuar?");
                    if(response){
                        $('input[name=contrato]',form).attr('data-id',  $(this).attr('data-id') );
                        $('input[name=contrato]',form).val( $(this).attr('data-codigo') );
                        $('input[name=contratoDescricao]',form).val( $(this).attr('data-descricao') );
                        $('input[name=inicioProjeto]',form).val('');
                        $('input[name=terminoProjeto]',form).val('');
                        $('select[name=situacaoContratoProjeto]').val('');
                        $('select[name=gestaoSolicitacaoAcesso]').val('');
                        $('select[name=usoPerfilPadrao]').val('');
                        $('select[name=usoLocalPadrao]').val('');
                        $('select[name=cadastroEstudante]').val('');
                        $('.buscaajax').hide();
                        $('#buscaContrato').show();
                    }
                    
                    return false;
                });
            }
            else{
                $('.buscaajax .lista_suspensa').html(retorno).hide();
            }
		});
	}


    /** SALVA PROJETO AJAX 
     * Salva via ajax os dados de projeto
     * @type	function
     * @date	01/02/22
     * @since	1.0.0
     * 
     * @param	modal_id
     * @return	BOOLEAN
     */
    function salvarProjeto(form = null){
        projetoid = $('input[name=ID]',form).val();
        validaForm = $.fn.validaForm(form);
        if(!validaForm)return false;

         // inicia o salvamento
         $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            //async: false,
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Projetos',
                funcao              : 'salvarProjeto',
                projetoid           : $('input[name=ID]',form).val(),
                logo_projeto        : $('#logo_projeto',form).val(),
                nome                : $('input[name=nome]',form).val(),
                status              : $('input[name=status]:checked',form).val(),
                descricao           : $('#descricao',form).val(),
                //  dados do contrato
                contratoid              : $('input[name=contrato]',form).attr('data-id'),
                inicioProjeto           : $('input[name=inicioProjeto]',form).val(),
                terminoProjeto          : $('input[name=terminoProjeto]',form).val(),
                situacaoContratoProjeto : $('select[name=situacaoContratoProjeto]',form).val(),
                gestaoSolicitacaoAcesso : $('select[name=gestaoSolicitacaoAcesso]',form).val(),
                usoPerfilPadrao         : $('select[name=usoPerfilPadrao]',form).val(),
                usoLocalPadrao          : $('select[name=usoLocalPadrao]',form).val(),
                cadastroEstudante       : $('select[name=cadastroEstudante]',form).val()
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            //alert(json);
            if(!json['status']){
                if(json['erro']){alert(json['erro'].toString() )}
                $('body').removeClass('processando');
            }
            if(json['status']){
                //$(modal).removeClass('processando');]
                console.log('salvamento ok')
                if( $('input[name=urlRetorno]',form).val() != '' ){
                    window.location = '?page='+$('input[name=urlRetorno]',form).val();
                }else{
                    $page = $('input[name=page]').val();
                    if( projetoid != json['projetoid'] ){
                        window.location = '?page='+$page+'&ID='+json['projetoid'];
                    }else
                    {
                        window.location = '?page='+$page+'&ID='+projetoid;
                    }
                }
            }
        });
        return true;
    }

    function excluirProjeto(projetoid,descricao){
        $.ajax({
            beforeSend: function() {/*$(modal).addClass('processando');*/},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Projetos',
                funcao              : 'excluirProjeto',
                projetoid           : projetoid,
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

    function montaActionsComposicoesFuncionais(){
        
        $('.abrir_lista_composicoes_disponiveis').click(function(){
            $('.lista_composicoes_disponiveis').show('fast');
            $('.fechar_lista_composicoes_disponiveis').show('fast');
        });
        $('.fechar_lista_composicoes_disponiveis').click(function(){
            $('.lista_composicoes_disponiveis').hide('fast');
            $(this).hide('fast');
        });
        $('.lista_composicoes_disponiveis .composicaofuncional').click(function(){
            projeto_id = $('input[name=ID]',form).val();
            composicaof_id = $(this).attr('data-id');
            setVinculoContratoProjetoComposicao(null,projeto_id,composicaof_id,'vincular');
        });
    }

    function setVinculoContratoProjetoComposicao(contrato_id,projeto_id,composicaof_id,acao){
        // inicia o salvamento
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            //async: false,
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'ComposicaoFuncional',
                funcao              : 'setVinculoContratoProjetoComposicao',
                acao                : acao,
                contrato_id         : contrato_id,
                projeto_id          : projeto_id,
                composicaof_id      : composicaof_id
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){
                if(json['erro']){alert(json['erro'].toString() )}
                $('body').removeClass('processando');
                return false;
            }
            if(json['status']){
                $('body').removeClass('processando');
                if(acao == 'vincular'){
                    let composicao_tr = $('<tr></tr>').attr({ 'data-id' : composicaof_id });
                    td = $('<td>'+ composicaof_id +'</td>');
                    composicao_tr.append(td);
                    td = $('<td>'+ $('#ACF_'+composicaof_id).attr('data-sigla') +'</td>').addClass('sigla');
                    composicao_tr.append(td);
                    td = $('<td>'+ $('#ACF_'+composicaof_id).attr('data-nome') +'</td>').addClass('nome');
                    composicao_tr.append(td);
                    td = $('<td><a href="#" class="desvincularComposicaoFuncional" data-id="'+composicaof_id+'"><i class="fa fa-trash" aria-hidden="true"></i></a></td>')
                    composicao_tr.append(td);
                    $('.lista_composicoes_vinculadas tbody').append(composicao_tr);
                    montaActionsButtonsListaComposicoesVinculadas();
                }
                if(acao == 'desvincular'){
                    $('#CF_'+composicaof_id).remove();
                    montaActionsButtonsListaComposicoesVinculadas();
                }
                return true;
            }
        });
    }

    function montaActionsButtonsListaComposicoesVinculadas(){
        $('.lista_composicoes_vinculadas a.desvincularComposicaoFuncional').unbind('click');
        $('.lista_composicoes_vinculadas a.desvincularComposicaoFuncional').click(function(){
            projeto_id = $('input[name=ID]',form).val();
            composicaof_id = $(this).attr('data-id');
            setVinculoContratoProjetoComposicao(null,projeto_id,composicaof_id,'desvincular');
            return false;
        });
    }

    // ABA PLATAFORMAS
    function montaActionsButtonsAbaPlataformas(){
        // BOTAO FECHAR LISTA DE DISPONÍVEIS
        $('.plataformas-disponiveis a.fechar').click(function(){
            $('.plataformas-disponiveis').hide('fast');
        });
        // BOTAO DE LISTAR TODAS AS DISPONÍVEIS.
        $('#listar_todas_plataformas').click(function(){
            projetoid = $('input[name=ID]',form).val();
            campos =['IDPlataforma','NMPlataforma']
            var data = {
                action 		: "Handler_SA",
                class       : "Plataforma",
				funcao  	: 'getListaRegistros',
                //projetoid   : projetoid,
                campos      : campos,
                output      : "JSON"
            }
            getListaPlataformas_ajax(data);
        });
    }

    function montaActionsButtonsListaPlataformasVinculadas(){
        $('.lista_plataformas_vinculadas a.desvincularPlataforma').unbind('click');
        $('.lista_plataformas_vinculadas a.desvincularPlataforma').click(function(){
            projeto_id = $('input[name=ID]',form).val();
            composicaof_id = $(this).attr('data-id');
            setVinculoContratoProjetoPlataforma(null,projeto_id,composicaof_id,'desvincular');
            return false;
        });
    }

    function getListaPlataformas_ajax(data = null){
        $.ajax({
			type : "POST",
			url: $.fn.getUrlAjax(),
			beforeSend: function(){
				$('.buscaajax .lista_suspensa').html('<i class="fa fa-refresh fa-spin fa-lg fa-fw"></i>carregando ...').show();
			},
			data : data
		}).done(function(retorno){
			if(retorno){
                $('.plataformas-disponiveis tbody').html('');
                plataformas = jQuery.parseJSON(retorno);
                plataformas.forEach(platf => {
                    var i = '<td><i class="fa fa-hand-o-down" aria-hidden="true"></i></td>';
                    $('.plataformas-disponiveis tbody').append('<tr id="PLAA_'+platf.IDPlataforma+'" data-id="'+platf.IDPlataforma+'"><td class="nome">'+platf.NMPlataforma+'</td>'+i+'</tr>');
                });
                $('.plataformas-disponiveis tbody tr').each(function(){
                    $(this).unbind('click');
                    $(this).click(function(){
                        projeto_id = $('input[name=ID]',form).val();
                        plataforma_id = $(this).attr('data-id');
                        setVinculoContratoProjetoPlataforma(null,projeto_id,plataforma_id,'vincular');
                        return false;
                    });
                });
                $('.plataformas-disponiveis').show('fast');
            }
            else{
            }
		});
    }

    function setVinculoContratoProjetoPlataforma(contrato_id,projeto_id,plataforma_id,acao){
        // inicia o salvamento
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            //async: false,
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Plataforma',
                funcao              : 'setVinculoContratoProjetoPlataforma',
                acao                : acao,
                contrato_id         : contrato_id,
                projeto_id          : projeto_id,
                plataforma_id      : plataforma_id
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){
                if(json['erro']){alert(json['erro'].toString() )}
                $('body').removeClass('processando');
                return false;
            }
            if(json['status']){
                $('body').removeClass('processando');
                if(acao == 'vincular'){
                    let plataforma_tr = $('<tr id="PLAT_'+plataforma_id+'"></tr>').attr({ 'data-id' : plataforma_id });
                    td = $('<td>'+ $('.nome','#PLAA_'+plataforma_id).html() +'</td>').addClass('nome');
                    plataforma_tr.append(td);
                    td = $('<td><a href="#" class="desvincularPlataforma" data-id="'+plataforma_id+'"><i class="fa fa-trash" aria-hidden="true"></i></a></td>')
                    plataforma_tr.append(td);
                    $('.lista_plataformas_vinculadas tbody').append(plataforma_tr);
                    montaActionsButtonsListaPlataformasVinculadas();
                }
                if(acao == 'desvincular'){
                    $('#PLAT_'+plataforma_id).remove();
                    montaActionsButtonsListaPlataformasVinculadas();
                }
                return true;
            }
        });
    }
    
    // ABA LOCAIS
    function montaActionsButtonsAbaLocais(){
        // Busca auto-complete ajax
        $('.pesquisa_projeto_local .pesquisa_local').unbind('input');
        $('.pesquisa_projeto_local .pesquisa_local').bind('input',function(){
            clearTimeout(timer);
            timer = setTimeout(function () {
                getListaLocais_ajax($('.pesquisa_projeto_local .pesquisa_local').val());
            },600);
        });
        $('.pesquisa_projeto_local .pesquisa_local').unbind('focus');
        $('.pesquisa_projeto_local .pesquisa_local').bind('focus',function(){
            if($(this).val()!=""){
                clearTimeout(timer);
                timer = setTimeout(function () {
                    getListaLocais_ajax($('.pesquisa_projeto_local .pesquisa_local').val());
                },600);
            }
        });
        $('.pesquisa_projeto_local').mouseleave(function() {
            timer = setTimeout(function () {
                $('.pesquisa_projeto_local .lista_suspensa').hide('fast');
            },1000);
        });
    }

    function getListaLocais_ajax(s){
		$.ajax({
			type : "POST",
			url: $.fn.getUrlAjax(),
			beforeSend: function(){
				$('.pesquisa_projeto_local .lista_suspensa').html('<i class="fa fa-refresh fa-spin fa-lg fa-fw"></i>carregando ...').show();
			},
			data : {
				action 				: "Handler_SA",
                class               : "Local",
				funcao  			: 'getlistaLocais_ajax',
				s					: s
			}
		}).done(function(retorno){
			if(retorno){
                $('.pesquisa_projeto_local .lista_suspensa').html(retorno).show();
                $('.pesquisa_projeto_local .lista_suspensa').find('a').unbind( 'click' );
                $('.pesquisa_projeto_local .lista_suspensa').find('a').click( function(){
                    //$('.buscaajax').hide();
                    //$('#buscaContrato').show();   
                    projeto_id = $('input[name=ID]',form).val();
                    local_id = $(this).attr('data-id');
                    nome = $(this).find('.nome').html();
                    endereco = $(this).find('.endereco').html();
                    setVinculoContratoProjetoLocal(null,projeto_id,local_id,'vincular',nome,endereco);
                    return false;
                });
            }
            else{
                $('.pesquisa_projeto_local .lista_suspensa').html(retorno).hide();
            }
		});
	}

    function setVinculoContratoProjetoLocal(contrato_id,projeto_id,local_id,acao,nome,endereco){
        // inicia o salvamento
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            //async: false,
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Local',
                funcao              : 'setVinculoContratoProjetoLocal',
                acao                : acao,
                contrato_id         : contrato_id,
                projeto_id          : projeto_id,
                local_id            : local_id
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){
                if(json['erro']){alert(json['erro'].toString() )}
                $('body').removeClass('processando');
                return false;
            }
            if(json['status']){
                $('body').removeClass('processando');
                if(acao == 'vincular'){
                    let local_tr = $('<tr id="LOCAL_'+local_id+'"></tr>').attr({ 'data-id' : local_id });
                    td = $('<td>'+ nome +'</td>').addClass('nome');
                    local_tr.append(td);
                    td = $('<td>'+ endereco +'</td>').addClass('endereco');
                    local_tr.append(td);
                    td = $('<td><a href="#" class="desvincularLocal" data-id="'+local_id+'"><i class="fa fa-trash" aria-hidden="true"></i></a></td>');
                    local_tr.append(td);
                    $('.lista_locais_vinculados tbody').append(local_tr);
                    montaActionsButtonsListaLocaisVinculados();
                }
                if(acao == 'desvincular'){
                    $('#LOCAL_'+local_id).remove();
                    montaActionsButtonsListaLocaisVinculados();
                }
                return true;
            }
        });
    }
    
    function montaActionsButtonsListaLocaisVinculados(){
        $('.lista_locais_vinculados a.desvincularLocal').unbind('click');
        $('.lista_locais_vinculados a.desvincularLocal').click(function(){
            projeto_id = $('input[name=ID]',form).val();
            composicaof_id = $(this).attr('data-id');
            setVinculoContratoProjetoLocal(null,projeto_id,composicaof_id,'desvincular',null,null);
            return false;
        });
    }

});