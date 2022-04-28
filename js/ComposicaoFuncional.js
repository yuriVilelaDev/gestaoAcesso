jQuery(function($) {
	
    var timer = 0;

    // FORMULÁRIO DE CADASTRO
    var form = "#form_cadastro_composicaofuncional";

	$(document).ready(function(){
 		montaActionsButtons();
	});

	function montaActionsButtons(){
        // mostrar botao de excluir caso esse cadastro ja exista no banco de dados.
        if( $('#ID',form).val() > 0 ){
            $('.bt_excluir_cadastro').show();
        }
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
    };

    function salvarCadastro(formid){
        composicaof_id = $('input[name=ID]',formid).val();
        validaForm = $.fn.validaForm(formid);
        if(!validaForm)return false;
        // inicia o salvamento
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            //async: false,
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'ComposicaoFuncional',
                funcao              : 'salvarCadastro',
                composicaof_id      : composicaof_id,
                nome                : $('input[name=nome]',formid).val(),
                sigla               : $('input[name=sigla]',formid).val(),
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
                    if( composicaof_id != json['composicaof_id'] ){
                        window.location = '?page='+$page+'&ID='+json['composicaof_id'];
                    }else
                    {
                        window.location = '?page='+$page+'&ID='+composicaof_id;
                    }
                }
            }
        });
        return true;
    }
    function excluirCadastro(composicaof_id,descricao=null){
        $.ajax({
            beforeSend: function() {$('body').addClass('processando');},
            type : "POST",
            url: $.fn.getUrlAjax(),
            data : {
                action 	            : "Handler_SA",
                class               : 'ComposicaoFuncional',
                funcao              : 'excluirCadastro',
                composicaof_id      : composicaof_id,
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