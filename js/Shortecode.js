jQuery(function($) {
    var timer = 0;
	$(document).ready(function(){
        $("#form_shortecode_cadastro").hide();
        montaActionsButtons();
        $('#validaChave').on('click', function() {	

            $.ajax({
                beforeSend: function() {},
                url: $.fn.getUrlAjax(),
                type: "POST",
                data: {
                    action 	            : "Handler_SA",
                    class               : 'GestaoAcesso',
                    funcao              : 'validaChaveAutenticacao',	
                    chave_autenticacao  : $('#chave_autenticacao').val()
                }
            }).done(function(retorno){
                if(retorno){
                    $("#chave_autenticacao").prop("disabled", true);
                    $("#validaChave").hide();
                    $("#form_shortecode_cadastro").show();
                }
                else{
                    alert("");
                    return false;
                }
            });
        });

        $('#validaAcesso').on('click', function() {	
            alert( $('#inputEmail3').val());
			$.ajax({
				beforeSend: function() {},
				url: $.fn.getUrlAjax(),
				type: "POST",
				data: {
					action 	            : "Handler_SA",
					class               : 'GestaoAcesso',
					funcao              : 'validaAcesso',
					user_email			: $('#').val(),
					user_pass			: $('#').val()		
				}
			}).done(function(retorno){
              alert(retorno);
            });
               
		});

        $('#CadastraAcesso').on('click', function() {	
       
                
            $.ajax({
                beforeSend: function() {},
                url: $.fn.getUrlAjax(),
                type: "POST",
                data: {
                    action 	            : "Handler_SA",
                    class               : 'GestaoAcesso',
                    funcao              : 'cadastraAcesso',
                    user_email			: $('#email').val(),
                    nome			    : $('#nome').val(),
                    user_pass			: $('#nome').val(),
                    data_nascimento		: $('#dataNascimento').val(),  
                    sexo			    : $('#sexo').val(),
                    telefone			: $('#telefone').val(), 
                    tipo_telefone       : $('').val(),		
                    chave_autenticacao  : $('#chave_autenticacao').val()
                }
            });
			
           return false;       
		});
     
        
       return false;       
    });

	
    
    //ACOES GERAIS DA PÁGINA BASE
	function montaActionsButtons(){
        // BOTÃO DE ESQUECI A SENHA
        $('#bt_esqueceuasenha').click(function(){
            
            // $('#form_shortecode_login').hide('fast');
            // $('#form_shortecode_esqueceuasenha').show('fast');
            // return false;
        })
        // BOTÃO ESQUECI A SENHA CANCELAR
        
        $('.bt_cancelar','#form_shortecode_esqueceuasenha').click(function(){
            $('#form_shortecode_login').show('fast');
            $('#form_shortecode_esqueceuasenha').hide('fast');
            return false;
        })
    }

    // x.value=x.value.toUpperCase();
});
 

