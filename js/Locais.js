jQuery(function($) {
	
    var timer = 0;

    // FORMULÁRIO DE CADASTRO
    var form = "#form_cadastro_local";

	$(document).ready(function(){
 		montaActionsButtons();
	});

	function montaActionsButtons(){
        // mostrar botao de excluir caso esse cadastro ja exista no banco de dados.
        if( $('#ID',form).val() > 0 ){
            $('.bt_excluir_cadastro').show();
        }
        // BOTAO ADICIONAR
        $('.bt_adicionaLocal').click(function(){
            $page = $('input[name=page]').val();
            window.location = '?page='+$page+'&ID=-1';
        });
        // BOTAO DE VOLTAR
        $('.bt_voltar').click(function(){
            window.history.back();
        });
        // BOTAO SALVAR CADASTRO
        $('.bt_salvar_cadastro').click(function(){
            salvarCadastro(form);
		});
        // BOTAO EXCLUIR CADASTRO
        $('.bt_excluir_cadastro').click(function(){
            if( $('input[name=ID]',form).length > 0 ){
                var $delete = confirm("Deseja excluir a composição funcional: " + $('input[name=nome]',form).val() + " ?");
                if ($delete == true)
                excluirCadastro( $('input[name=ID]',form).val() );
                return true;
            }
        });
        //CAMPO DE CEP
        $('input[name=cep]').val( $.fn.formataCEP( $('input[name=cep]').val() ) );
        $('input[name=cep]').unbind('input');
        $('input[name=cep]').bind('input',function(){
            $(this).val( $.fn.formataCEP($(this).val()) );
            if( $(this).val().length == 9 ){
                $('.bt_procurarCEP').focus();
            }
		});
        //BOTAO BUSCAR CEP
        $('.bt_procurarCEP').click(function(){
            cep =$('input[name=cep]').val().replace(/[^0-9]+/g,'');
            if(cep.length==8){
                $('i',this).show();
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $('input[name=logradouro]').val(dados.logradouro);
                        $('input[name=bairro]').val(dados.bairro);
                        $('input[name=localidade]').val(dados.localidade);
                        $('input[name=uf]').val(dados.uf);
                        $('input[name=numero]').val('').focus();
                        $('i','.bt_procurarCEP').hide();
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        //limpa_formulário_cep();
                        alert("CEP não encontrado.");
                        $('i','.bt_procurarCEP').hide();
                    }
                });
            }

        });
        
    };

    function salvarCadastro(formid){
        local_id = $('input[name=ID]',formid).val();
        validaForm = $.fn.validaForm(formid);
        console.log($.fn.getUrlAjax());
        if(!validaForm)return false;
        // inicia o salvamento
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            //async: false,
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Local',
                funcao              : 'salvarCadastro',
                local_id            : local_id,
                nome                : $('input[name=nome]',formid).val(),
                tipolocal           : $('select[name=tipolocal]',formid).val(),
                cdregioal           : $('input[name=cdregioal]',formid).val(),
                cep                 : $('input[name=cep]',formid).val(),
                logradouro          : $('input[name=logradouro]',formid).val(),
                numero              : $('input[name=numero]',formid).val(),
                complemento         : $('input[name=complemento]',formid).val(),
                bairro              : $('input[name=bairro]',formid).val(),
                localidade          : $('input[name=localidade]',formid).val(),
                uf                  : $('input[name=uf]',formid).val()
            }
        }).done(function(retorno){
            var json = jQuery.parseJSON(retorno);
            if(!json['status']){
                if(json['erro']){alert(json['erro'].toString() )}
                $('body').removeClass('processando');
            }
            if(json['status']){
                if( $('input[name=urlRetorno]',form).val() != '' ){
                    window.location = '?page='+$('input[name=urlRetorno]',form).val();
                }else{
                    $page = $('input[name=page]').val();
                    if( local_id != json['local_id'] ){
                        window.location = '?page='+$page+'&ID='+json['local_id'];
                    }else
                    {
                        window.location = '?page='+$page+'&ID='+local_id;
                    }
                }
            }
        });
        return true;
    }
    function excluirCadastro(local_id,descricao=null){
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'Local',
                funcao              : 'excluirCadastro',
                local_id            : local_id,
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

});